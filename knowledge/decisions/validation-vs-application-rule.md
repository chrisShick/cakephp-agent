---
id: validation-vs-application-rule
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: critical
truth_level: FRAMEWORK_DEFAULT
sources:
  - https://book.cakephp.org/5.x/orm/validation.html
last_verified: 2026-08-10
related: [table-callback-vs-application-rule]
evaluations: [unique-email-uses-application-rule, email-format-uses-validation, unique-email-must-not-only-validation]
---

# Validation vs application rules

## Use cases

- Validating request/field shape and formats.
- Enforcing uniqueness, existence, or other state-dependent constraints on save/delete.

## Decision questions

1. Is this check about the shape/type/format of incoming data?
2. Does the check require comparing against persisted state?
3. Must the check hold for all persistence paths, including non-form code?

## Recommended outcome

- **Validation (`Validator` via `validationDefault` / named validators):** stateless shape/format checks applied during `newEntity()` / `patchEntity()`.
- **Application rules (`RulesChecker` via `buildRules()`):** stateful constraints applied during `save()` / `delete()`.

Example: email *format* → validation; email *uniqueness* → application rule (`isUnique`).

## Rejected alternatives

- Putting uniqueness only in `validationDefault` — races and non-form paths can bypass reliable enforcement; application rules run with save.
- Treating all checks as one “validation” layer — loses CakePHP’s two-layer model.

## Exceptions

- You may also surface uniqueness early in validation for UX, but still keep the application rule for persistence integrity.
- Project conventions may add domain services *in addition to* rules; they do not replace RulesChecker for Table saves.

## Examples

```php
// Validation — format
public function validationDefault(Validator $validator): Validator
{
    $validator->email('email');
    return $validator;
}

// Application rule — uniqueness
public function buildRules(RulesChecker $rules): RulesChecker
{
    $rules->add($rules->isUnique(['email']));
    return $rules;
}
```

## Evaluations

- `unique-email-uses-application-rule`
- `email-format-uses-validation`
- `unique-email-must-not-only-validation`