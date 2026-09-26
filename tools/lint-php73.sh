#!/usr/bin/env bash
#
# Lint every PHP file in this package with a real PHP 7.3 interpreter.
#
# Why: the cross-module floor is PHP 7.3 (AGENTS.md §1; prod runs 7.3 on
# Fedora 30), but the dev box runs 8.1 and the FA container runs 7.4.33.
# Syntax introduced in 7.4/8.x lints clean and passes the unit suite on those
# interpreters, then fatals in production the first time the file is included.
# CustomerPaymentRequest.php shipped with readonly promoted constructor
# properties (8.1+) and a trailing comma in the parameter list (8.0+): 263
# green tests locally, fatal on the first include in a real runtime.
#
# Resolution order for a 7.3 interpreter:
#   1. $PHP73_BIN
#   2. php7.3 / php73 on PATH
#   3. a container runtime (podman, then docker) using the php:7.3-alpine
#      image, with this directory bind-mounted
#
# A 7.4 interpreter is deliberately NOT accepted as a substitute: passing on
# 7.4 says nothing about 7.3, which is the version that has to work.
#
# Usage: tools/lint-php73.sh
# Exit:  0 = clean, 1 = incompatible with 7.3, 2 = no 7.3 interpreter found

set -uo pipefail

cd "$(dirname "$0")/.." || exit 2

# src/ is the code Composer ships and FA loads at runtime. tests/ is included
# too: it is dev-only, but a test that cannot parse on the floor interpreter
# is a test nobody can run on the floor, and it hid 108 PHP 8.0 named arguments
# that would have failed on first run in CI.
# legacy/ is excluded on purpose - those are FA core reference dumps, many of
# which do not parse on ANY version (mangled comment terminators), so they are
# not a 7.3 signal.
TARGETS=(src tests)
IMAGE="${PHP73_IMAGE:-docker.io/library/php:7.3-alpine}"

descend() {
	local d="$1"
	[ -d "$d" ] || return 0
	find "$d" -type d \( -name vendor -o -name archive \) -prune -o -type f -name '*.php' -print
}

FILES=()
for t in "${TARGETS[@]}"; do
	while IFS= read -r f; do
		FILES+=("$f")
	done < <(descend "$t")
done

if [ "${#FILES[@]}" -eq 0 ]; then
	echo "No PHP files found to lint." >&2
	exit 2
fi

is_php73() {
	"$@" -r 'exit(PHP_MAJOR_VERSION === 7 && PHP_MINOR_VERSION === 3 ? 0 : 1);' >/dev/null 2>&1
}

lint_with_binary() {
	local bin="$1" fail=0 f out
	for f in "${FILES[@]}"; do
		out="$("$bin" -l "$f" 2>&1)"
		case "$out" in
		"No syntax errors"*) ;;
		*)
			echo "$out" | grep -E 'Parse error|Fatal error|Errors parsing'
			fail=1
			;;
		esac
	done
	return $fail
}

# 1/2: a local 7.3 binary.
for c in "${PHP73_BIN:-}" php7.3 php73; do
	[ -n "$c" ] || continue
	command -v "$c" >/dev/null 2>&1 || [ -x "$c" ] || continue
	if is_php73 "$c"; then
		echo "Linting ${#FILES[@]} files with $c ($("$c" -r 'echo PHP_VERSION;'))"
		lint_with_binary "$c"
		exit $?
	fi
done

# 3: a container runtime with the official 7.3 image.
for rt in podman docker; do
	command -v "$rt" >/dev/null 2>&1 || continue
	"$rt" run --rm "$IMAGE" php -r 'exit(PHP_MAJOR_VERSION === 7 && PHP_MINOR_VERSION === 3 ? 0 : 1);' >/dev/null 2>&1 || continue

	echo "No local PHP 7.3 found; linting ${#FILES[@]} files in $IMAGE."
	# -v "$PWD:/r:ro" needs SELinux relabelling on some hosts.
	if ! "$rt" run --rm -v "$PWD:/r:ro,Z" "$IMAGE" true >/dev/null 2>&1; then
		"$rt" run --rm -v "$PWD:/r:ro" "$IMAGE" true >/dev/null 2>&1 || {
			echo "Could not bind-mount $PWD into $IMAGE." >&2
			exit 2
		}
		MOUNT="$PWD:/r:ro"
	else
		MOUNT="$PWD:/r:ro,Z"
	fi

	# The target list is passed as arguments, not interpolated into the script:
	# $TARGETS is a host-side array and would be empty inside the container.
	"$rt" run --rm -v "$MOUNT" "$IMAGE" sh -c '
		cd /r || exit 2
		fail=0
		count=0
		for f in $(find "$@" -type d -name vendor -prune -o -type f -name "*.php" -print | sort); do
			count=$((count + 1))
			out=$(php -l "$f" 2>&1)
			case "$out" in
			"No syntax errors"*) ;;
			*) echo "$out" | grep -E "Parse error|Fatal error|Errors parsing"; fail=1 ;;
			esac
		done
		echo "Checked $count files with PHP $(php -r "echo PHP_VERSION;")"
		exit $fail
	' sh "${TARGETS[@]}"
	exit $?
done

cat >&2 <<EOF
No PHP 7.3 interpreter available.

This package targets PHP 7.3, so a 7.4 or 8.x interpreter cannot validate it.
Either install php7.3, point at an existing binary, or make a container
runtime available:

    PHP73_BIN=/path/to/php7.3 tools/lint-php73.sh
    PHP73_IMAGE=docker.io/library/php:7.3-alpine tools/lint-php73.sh
EOF
exit 2
