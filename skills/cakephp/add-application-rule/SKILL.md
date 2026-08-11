---
name: add-application-rule
description: Add CakePHP RulesChecker application rules for stateful save/delete invariants.
---

# Add application rule

## Objective

Enforce **stateful** persistence invariants with `buildRules()` / `RulesChecker` so they run on `save()` / `delete()` across all entry points.

## Use when

- Uniqueness, exists-in, or custom checks against persisted data.
- Invariants that must hold for CLI, jobs, and HTTP alike.
- Replacing ad-hoc `beforeSave` rejection that belongs in rules.

## Do not use when

- The check is only field format/shape — use `add-validation`.
- The work is a side effect after a successful save (callback/listener), not a pass/fail rule.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Confirm with **`choose-cakephp-abstraction`** / `validation-vs-application-rule` if needed.
3. Inspect `buildRules()`, existing rules, and all save paths for the Table.
4. Check associations needed for `existsIn`.

## Workflow

1. State the invariant in one sentence.
2. Prefer built-ins (`isUnique`, `existsIn`) before custom rule callables.
3. Implement in `buildRules()`; set clear error fields/messages.
4. Keep complementary validation for UX format checks if useful — do not drop the rule.
5. Add tests that `save()` fails with rule errors when the invariant is violated (including non-HTTP paths if practical).

## Framework decisions

- `knowledge/decisions/validation-vs-application-rule`
- `knowledge/decisions/table-callback-vs-application-rule`

## Anti-patterns

- Encoding uniqueness only in validation or only in `beforeSave` exceptions.
- HTTP-only checks in controllers for invariants that must apply everywhere.
- Silent rule failures without entity errors.

## Validation

- Violating entities fail `save()` with rule errors.
- Satisfying entities save successfully.
- Format-only concerns remain in Validator.

## Completion criteria

- Rule registered and tested; decision boundary vs validation documented in the change notes if both exist.
