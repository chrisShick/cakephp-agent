---
name: diagnose-orm-query
description: Diagnose CakePHP ORM query problems — wrong rows, N+1, over-fetching, contain vs matching, and bulk-update surprises.
---

# Diagnose ORM query

## Objective

Systematically find why an ORM query returns wrong data, performs poorly, or skips expected lifecycle behavior — then apply a CakePHP-correct fix.

## Use when

- Unexpected result sets, missing associations, or duplicate rows.
- Suspected N+1 or over-eager `contain`.
- Confusion about `contain` vs `matching` / joins.
- Bulk `update`/`delete` not running callbacks/rules as expected.

## Do not use when

- The issue is purely HTTP routing or template rendering with a correct query.
- You already know the fix and only need `create-finder` / `add-association` implementation steps.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Capture the query builder chain, contain graph, and SQL (debug/`sql()` / query logging as available).
3. Inspect associations, finders, and whether results are hydrated entities or arrays.
4. Note whether the path uses entity `save` vs query `update`/`delete`.

## Workflow

1. Reproduce with the smallest query that shows the bug.
2. Classify: wrong filter, wrong join type, hydration/contain issue, N+1, or lifecycle mismatch.
3. Apply decision guidance:
   - Filter parents by associated conditions → `matching` / `innerJoinWith` (not `contain` alone).
   - Load associations for use → `contain` selectively.
   - Repeated per-row queries → contain/select or batch strategy.
   - Need callbacks/rules → entity save path; bulk update skips them (`bulk-update-vs-entity-save`).
   - Tempted by `Connection::execute` for ordinary domain data → prefer ORM / expressions (`orm-vs-connection-sql`).
4. Prefer fixing in a finder when semantics are reusable.
5. Add a regression test that fails on the old behavior.

## Framework decisions

- `knowledge/decisions/contain-vs-matching`
- `knowledge/decisions/bulk-update-vs-entity-save`
- `knowledge/decisions/entity-accessor-vs-query-calculation`
- `knowledge/decisions/orm-vs-connection-sql`
- Anti-patterns: hidden-n-plus-one, over-eager-contain, bypassing-orm-for-convenience, unsafe-sql-concatenation

## Anti-patterns

- Adding more `contain` without measuring need.
- Fixing filter bugs with PHP loops over large result sets.
- Expecting `Query::update()` to fire `beforeSave` rules like `save()`.
- Reaching for Connection/raw SQL when Table finders or Query expressions fit.
- Eloquent-style global scopes as the mental model.

## Validation

- SQL/result set matches the intended semantics.
- Lifecycle expectations are explicit (entity vs bulk).
- Regression test exists for the bug class.

## Completion criteria

- Root cause named; fix applied at the correct layer; test or clear verification recorded.
