---
id: table-callback-vs-application-rule
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: critical
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/orm/table-objects.html
  - https://book.cakephp.org/5.x/orm/validation.html
last_verified: 2026-08-10
related: [validation-vs-application-rule]
evaluations: [unique-email-uses-application-rule]
---

# Table callback vs application rule

## Use cases

- Enforcing invariants on save/delete.
- Side effects around persistence (notifications, denormalized updates).

## Decision questions

1. Is this a pass/fail invariant about entity state vs persisted data?
2. Is this a side effect that should run after a successful decision to persist?
3. Must failures surface as rule/validation errors on the entity?

## Recommended outcome

- **Application rule** for stateful invariants (`isUnique`, `existsIn`, custom rules returning false + error).
- **Table callback** (`beforeSave`, `afterSave`, …) for lifecycle side effects and field normalization that is not primarily a rule error.

## Rejected alternatives

- Encoding uniqueness only in `beforeSave` with ad-hoc exceptions when RulesChecker fits.
- Putting response shaping in Table callbacks.

## Exceptions

- Behaviors may use callbacks for cross-cutting persistence features.
- Some normalizations (trim, default timestamps via Timestamp behavior) are callback/behavior territory.

## Examples

Reject duplicate slug → `buildRules`. Touch a search index after save → `afterSave` / `afterSaveCommit`.

## Evaluations

- `unique-email-uses-application-rule`