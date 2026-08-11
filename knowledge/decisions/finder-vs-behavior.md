---
id: finder-vs-behavior
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/orm/retrieving-data-and-resultsets.html
  - https://book.cakephp.org/5.x/orm/behaviors.html
last_verified: 2026-08-10
related: [contain-vs-matching]
evaluations: [prefer-custom-finder-for-query-semantics, reject-behavior-for-single-table-query-semantics]
---

# Finder vs behavior

## Use cases

- Reusable query semantics for one Table.
- Cross-cutting Table capabilities shared by many Tables.

## Decision questions

1. Is this primarily query composition for one model?
2. Does it need lifecycle callbacks across many tables?
3. Is it already provided by a CakePHP/core or installed behavior?

## Recommended outcome

- **Custom finder** for reusable query semantics on a Table (`findActive`, `findPublished`).
- **Behavior** for reusable persistence/query/lifecycle capabilities across Tables (Timestamp, Tree, soft-delete packs, etc.).

## Rejected alternatives

- God behaviors that accumulate unrelated query helpers for one app Table.
- Duplicating the same `where` clauses in controllers instead of a finder.

## Exceptions

- A behavior may expose finders; still prefer a Table finder when the semantics are domain-specific to one Table.
- Project may standardize certain cross-cutting finders via a shared behavior — label as project convention.

## Examples

Prefer `$articles->find('published')` over copying status conditions into every controller.

## Evaluations

- `prefer-custom-finder-for-query-semantics`
- `reject-behavior-for-single-table-query-semantics`
