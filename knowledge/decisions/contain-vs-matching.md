---
id: contain-vs-matching
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: FRAMEWORK_DEFAULT
sources:
  - https://book.cakephp.org/5.x/orm/retrieving-data-and-resultsets.html
  - https://book.cakephp.org/5.x/orm/associations.html
last_verified: 2026-08-10
related: [finder-vs-behavior]
evaluations: [contain-vs-matching-filter, avoid-over-eager-contain]
---

# contain() vs matching()

## Use cases

- Eager-loading associations for use on hydrated entities.
- Filtering parent rows by associated data.

## Decision questions

1. Do you need associated records on the result entities?
2. Do you only need to filter parents by association conditions?
3. Are you accidentally loading large graphs “just in case”?

## Recommended outcome

- **`contain()`** to eager-load associations you will use.
- **`matching()` / `notMatching()` / `innerJoinWith()` / `leftJoinWith()`** to filter (or join) based on associations without necessarily hydrating them the same way.

## Rejected alternatives

- `contain()` solely because an association exists.
- Filtering with PHP after over-fetching associations.

## Exceptions

- Sometimes you both filter and contain; combine deliberately.
- Strategy differences (`join` vs `select`) matter for row multiplication — inspect explain/cardinality.

## Examples

List articles that have a tag “news” → `matching` (and contain only if you need tag entities). Article view with author → `contain('Authors')`.

## Evaluations

- `contain-vs-matching-filter`
- `avoid-over-eager-contain`