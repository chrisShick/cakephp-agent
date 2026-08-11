---
id: bulk-update-vs-entity-save
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/orm/saving-data.html
last_verified: 2026-08-10
related: [table-callback-vs-application-rule]
evaluations: [bulk-update-skips-entity-callbacks, bulk-update-needs-entity-save-for-rules]
---

# Bulk update vs entity save

## Use cases

- Updating many rows efficiently.
- Persisting a single entity with validation, rules, and callbacks.

## Decision questions

1. Do you need validation, application rules, and entity callbacks?
2. Is the operation a mass update where skipping entity lifecycle is acceptable?
3. Are you updating associations that require entity marshalling?

## Recommended outcome

- **Entity `save()` / `saveMany()`** when lifecycle, rules, and marshaling matter.
- **Query `update()` / bulk APIs** when intentionally bypassing entity lifecycle for performance — document that callbacks/rules may not run.

## Rejected alternatives

- Assuming bulk updates fire the same callbacks as `save()`.
- Using entity-per-row saves for huge batches without measuring cost.

## Exceptions

- Some projects wrap bulk ops with explicit follow-up jobs for side effects.
- Database-level constraints still apply.

## Examples

Toggle one article’s published flag with rules → entity save. Mark 50k expired tokens → bulk update + documented tradeoffs.

## Evaluations

- `bulk-update-skips-entity-callbacks`
- `bulk-update-needs-entity-save-for-rules`
