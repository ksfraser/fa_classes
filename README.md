# fa_classes

FrontAccounting-specific classes extracted from the historical `ksf_modules_common` folder.

This repository is the destination for all legacy `class.fa_*` files (and their eventual refactors).

## Status

- `legacy/`: staged copies of the original `class.fa_*` files.
- `src/`: new/refactored code (PSR-4).

The first migration step intentionally keeps behavior unchanged; refactoring (namespaces, DI, query separation) happens incrementally.

## Validation policy (PHP 7.3)

Legacy code often inherited from `origin` / `fa_origin` for runtime validation helpers.

New PSR-4 code in this repo should prefer `ksfraser/validation` (helpers/traits) rather than forcing DTOs/repositories to inherit from a heavy base class.

- Validation package: [../validation/README.md](../validation/README.md)

