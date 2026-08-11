---
id: contain-vs-join
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/orm/retrieving-data-and-resultsets.html
last_verified: 2026-08-10
related: [contain-vs-matching, entity-accessor-vs-query-calculation]
evaluations: [hydrate-associations-prefer-contain, reject-manual-join-for-simple-association-load]
---

# contain() vs join

## Use cases

- Loading associated records into entities.
- Filtering/selecting with SQL joins without necessarily hydrating associations.

## Decision questions

1. Do you need associated entities hydrated on the result set?
2. Are you only filtering or selecting columns via SQL?
3. Would a manual join duplicate what `contain` / `matching` already express?

## Recommended outcome

- **`contain()`** to eager-load associations into entities (with select strategy when appropriate).
- **Explicit join / `innerJoinWith` / query expressions** when you need join semantics for filtering or partial selects without the contain hydration model — prefer CakePHP association helpers over raw join soup when possible.

## Rejected alternatives

- Hand-written joins that reimplement association foreign keys for simple eager loads.
- Using `contain()` solely to filter parents when `matching()` / `innerJoinWith()` is the intent (see `contain-vs-matching`).

## Exceptions

- Reporting queries may use custom joins/subqueries for performance — document why contain is insufficient.
- Some plugins expose custom finder join patterns; follow plugin APIs when installed.

## Examples

Article index showing author name on each entity → `contain('Authors')` (narrow fields). Filter articles that have a published comment without loading all comments → `matching` / join helpers.

## Evaluations

- `hydrate-associations-prefer-contain`
- `reject-manual-join-for-simple-association-load`
