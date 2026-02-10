# FA migration notes

## Related docs (keep in sync)

- Overall split plan and repo map: [../../LIBRARY_SPLIT_ANALYSIS.md](../../LIBRARY_SPLIT_ANALYSIS.md)
- Shared SQL builder + adapter layer: [../ksf_ModulesDAO/README.md](../ksf_ModulesDAO/README.md)

## Goal

Move all `class.fa_*` files into this repo without breaking existing modules, then refactor toward:

- DTOs/Entities representing a row (or aggregate)
- DAOs/Repositories responsible for SQL
- Services/Handlers responsible for business workflows

## Phase 1 (current)

- Copy `class.fa_*` files into `legacy/` (and backups/variants into `legacy/archive/`).
- Add Composer autoloading using `classmap` so legacy classes can be loaded later without renaming.
- Remove ad-hoc embedded test classes (e.g. `pod_test`) from `legacy/` because they create duplicate class names under Composer classmap.

No behavioral changes are made in Phase 1.

## Phase 2 (next)

- For a chosen table class (start with one low-dependency class):
  - Introduce a namespaced DTO (e.g. `Ksfraser\\FA\\DTO\\BankAccount`).
  - Introduce a Repository/DAO (e.g. `Ksfraser\\FA\\Repository\\BankAccountsRepository`) using `ksfraser/ksf-modules-dao` adapters.
  - Use `Ksfraser\\ModulesDAO\\Sql\\LegacyArraySqlBuilder` for shared SQL construction (select/where/insert/update/create-table) to avoid duplicating the legacy builder logic in multiple repos.
  - Keep a compatibility shim so existing code calling `fa_bank_accounts` continues to work.

### Origin / validation guidance

The legacy FA classes frequently relied on inheriting from `origin` / `fa_origin` for:

- permissive getters/setters (`set()`, `get()`, magic access)
- runtime validation (`is_*` checks, length/size/value checks)

For the migration path:

- Keep `origin`/`fa_origin` for **legacy compatibility shims** (Phase 1/Phase 2). This is the lowest-risk way to keep existing modules working.
Do **not** require new PSR-4 DTOs to inherit from Origin. Prefer composition (a validator/helper) or narrow traits for specific checks.

Preferred shared helpers package:

- `ksfraser/validation` (local repo: [../validation/README.md](../validation/README.md))

Reason: we are still targeting PHP 7.3 in production, while the standalone `ksfraser/origin` package currently uses newer PHP features and should not be treated as a drop-in base class until it is explicitly made 7.3-compatible.
