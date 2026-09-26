#!/usr/bin/env bash
#
# Lint every PHP file in this package with a PHP 7.4 interpreter.
#
# Why this exists: composer.json declares "php": ">=7.4" and the FA runtime
# container is 7.4.33, but the dev box runs 8.1. Syntax introduced in 8.0/8.1
# lints clean and passes the unit suite on 8.1, then fatals in production the
# first time the file is included. CustomerPaymentRequest.php shipped with
# readonly promoted constructor properties (8.1+) and a trailing comma in the
# parameter list (8.0+) and passed 263 tests locally; it was a hard parse error
# on 7.4.
#
# Resolution order for the 7.4 binary:
#   1. $PHP74_BIN
#   2. php7.4 / php74 on PATH
#   3. the FA runtime container, which is 7.4.33. The repo is not mounted into
#      that container, so src/ is copied to a temp dir before linting.
#
# Usage: tools/lint-php74.sh
# Exit:  0 = clean, 1 = parse errors, 2 = no 7.4 interpreter available

set -uo pipefail

cd "$(dirname "$0")/.." || exit 2

# src/ only: that is the code Composer ships and FA loads at runtime.
# legacy/ is excluded on purpose - 34 of those FA core reference dumps do not
# parse on ANY version (mangled comment terminators), so they are not a 7.4
# signal. tests/ is excluded because it is dev-box only and legitimately uses
# 8.0 named arguments; it never loads inside FA.
TARGETS=(src)

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

find_php74() {
	if [ -n "${PHP74_BIN:-}" ]; then
		echo "$PHP74_BIN"
		return 0
	fi
	local c
	for c in php7.4 php74; do
		if command -v "$c" >/dev/null 2>&1; then
			echo "$c"
			return 0
		fi
	done
	return 1
}

is_php74() {
	"$@" -r 'exit(PHP_MAJOR_VERSION === 7 && PHP_MINOR_VERSION === 4 ? 0 : 1);' >/dev/null 2>&1
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

BIN="$(find_php74 || true)"

if [ -n "$BIN" ] && is_php74 "$BIN"; then
	echo "Linting ${#FILES[@]} files with $BIN ($("$BIN" -r 'echo PHP_VERSION;'))"
	lint_with_binary "$BIN"
	exit $?
fi

# Fall back to the FA runtime container, which is 7.4.33.
RT=""
RT_RUNTIME=""
RT_CONTAINER=""
for r in podman docker; do
	command -v "$r" >/dev/null 2>&1 || continue
	for c in ${FA_CONTAINER:-ksfii_app-fa}; do
		if "$r" exec "$c" php -r 'exit(PHP_MAJOR_VERSION === 7 && PHP_MINOR_VERSION === 4 ? 0 : 1);' >/dev/null 2>&1; then
			RT="$r exec $c"
			RT_RUNTIME="$r"
			RT_CONTAINER="$c"
			break 2
		fi
	done
done

if [ -n "$RT" ]; then
	echo "No local PHP 7.4 found; copying package into $RT for linting."
	$RT rm -rf /tmp/fa-classes-lint >/dev/null 2>&1
	$RT mkdir -p /tmp/fa-classes-lint >/dev/null 2>&1
	# podman cp / docker cp: no stdin plumbing, and it preserves the tree.
	RC="$RT_RUNTIME"
	cp_ok=1
	for t in "${TARGETS[@]}"; do
		[ -d "$t" ] || continue
		$RT_RUNTIME cp "$PWD/$t" "$RT_CONTAINER:/tmp/fa-classes-lint/$t" >/dev/null 2>&1 || cp_ok=0
	done
	if [ "$cp_ok" -ne 1 ]; then
		echo "Failed to copy package into $RT" >&2
		exit 2
	fi
	$RT_RUNTIME exec "$RT_CONTAINER" sh -c '
		cd /tmp/fa-classes-lint || exit 2
		fail=0
		count=0
		for f in $(find . -type f -name "*.php" | sort); do
			count=$((count + 1))
			out=$(php -l "$f" 2>&1)
			case "$out" in
			"No syntax errors"*) ;;
			*) echo "$out" | grep -E "Parse error|Fatal error|Errors parsing"; fail=1 ;;
			esac
		done
		echo "Checked $count files with $(php -r "echo PHP_VERSION;")"
		exit $fail
	'
	status=$?
	$RT_RUNTIME exec "$RT_CONTAINER" rm -rf /tmp/fa-classes-lint >/dev/null 2>&1
	exit $status
fi

cat >&2 <<EOF
No PHP 7.4 interpreter available.

Set PHP74_BIN to a 7.4 binary, or start the FA runtime container, or run the
lint against a 7.4 host directly:

    PHP74_BIN=/path/to/php7.4 tools/lint-php74.sh
    FA_CONTAINER=<container> tools/lint-php74.sh
EOF
exit 2
