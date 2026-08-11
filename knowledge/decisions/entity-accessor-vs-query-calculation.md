---
id: entity-accessor-vs-query-calculation
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/orm/entities.html
last_verified: 2026-08-10
related: [contain-vs-matching]
evaluations: [entity-not-active-record, aggregate-sort-prefers-query-not-accessor]
---

# Entity accessor vs query-time calculation

## Use cases

- Derived presentation values on a loaded entity.
- Aggregations/filters that must run efficiently in SQL.

## Decision questions

1. Is the value needed on an already-hydrated entity for display/domain methods?
2. Must the database compute/filter/sort by this value at query scale?
3. Would computing in PHP cause N+1 or large hydration cost?

## Recommended outcome

- **Entity accessor / virtual field** for derived values from already-loaded fields.
- **Query expression / select alias / finder** when filtering, sorting, or aggregating at the database.

## Rejected alternatives

- Active Record style entity methods that issue queries by default.
- Hydrating thousands of rows to compute a count in PHP.

## Exceptions

- Small admin screens may accept PHP derivation; document the tradeoff.
- Cached virtual values may still be entity-side.

## Examples

Full name from first/last → accessor. Count of related comments for index sorting → query aggregate / counter cache strategy.

## Evaluations

- `entity-not-active-record`
- `aggregate-sort-prefers-query-not-accessor`
