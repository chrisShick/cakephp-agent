---
name: apply-strict-types
description: Add declare(strict_types=1) and tighten PHP types on touched code without drive-by refactors.
---

# Apply strict types

## Objective

Bring touched PHP files up to strict typing: `declare(strict_types=1);`, parameter/return/property types, honest nullability.

## Use when

- Editing or adding PHP classes in a CakePHP app/package that expects modern PHP.
- A review flagged untyped public APIs.

## Do not use when

- The task is unrelated and files are legacy intentionally untyped — do not mass-convert the whole app unasked.

## Inputs to discover

1. Follow **`inspect-before-coding`** for neighboring type style.
2. Note PHP version constraints in Composer.
3. Identify public methods lacking types.

## Workflow

1. Add `declare(strict_types=1);` to new/touched files matching project norms.
2. Add parameter, return, and property types where clear.
3. Fix resulting TypeErrors with correct nullability — not `@`.
4. Keep scope to touched units unless asked to widen.
5. Run the project’s tests/phpstan if available.

## Framework decisions

- Align with `rules/php/php.mdc`

## Anti-patterns

- Drive-by whole-codebase strict typing
- Suppressing type errors with `@`

## Validation

- Touched files declare strict types; public APIs typed; tests/phpstan clean for the change.

## Completion criteria

- Touched PHP units are strict-typed and consistent with neighbors.
