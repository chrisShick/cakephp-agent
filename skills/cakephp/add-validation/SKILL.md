---
name: add-validation
description: Add CakePHP Validator rules for request/data shape and format during newEntity/patchEntity.
---

# Add validation

## Objective

Encode **stateless** field shape/format checks in Table validation (`validationDefault` or named validators) without mistaking them for persistence invariants.

## Use when

- Enforcing required fields, types, formats (email, uuid, length, custom format).
- Adding a named validator for a specific marshaling path.
- Pairing UX-early checks with application rules for the same field.

## Do not use when

- The check needs persisted state (uniqueness, exists-in) — use `add-application-rule`.
- The work is HTTP-only input filtering unrelated to entity marshaling (consider middleware/request handling).

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. If ownership is unclear, run **`choose-cakephp-abstraction`**.
3. Inspect existing `validationDefault` / named validators and patchEntity call sites (`validate` option).
4. Check whether an application rule already covers related invariants.

## Workflow

1. Confirm the check is shape/format (not uniqueness/existence alone).
2. Add rules on the appropriate Validator; keep messages clear.
3. Ensure controllers/commands use `newEntity`/`patchEntity` so validation runs.
4. If uniqueness is also required, add/keep an application rule (do not rely on validation alone).
5. Add Table tests that assert validation errors on invalid input.

## Framework decisions

- `knowledge/decisions/validation-vs-application-rule` (primary)
- `knowledge/decisions/table-callback-vs-application-rule` when tempted to validate in `beforeSave`

## Anti-patterns

- Putting uniqueness **only** in `validationDefault`.
- Skipping validation by saving arrays without entities.
- Inventing FormRequest-style classes as a CakePHP default.
- Duplicating the same rules in controller conditionals.

## Validation

- Invalid shapes produce entity validation errors.
- Valid shapes marshal; stateful invariants still enforced via rules on save when applicable.

## Completion criteria

- Validator updated; tests cover failure and success paths; related application rules identified or added separately.
