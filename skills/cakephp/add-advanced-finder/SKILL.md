---
name: add-advanced-finder
description: Encode subquery/EXISTS/matching or counter-cache patterns in Table finders/behaviors.
---


# Add advanced finder

## Objective

Implement advanced ORM query shapes (subquery, EXISTS, matching) or counter maintenance using CakePHP finders/behaviors — not controller SQL.

## Use when

- Filtering by related existence/aggregates.
- Maintaining denormalized counts.

## Do not use when

- A simple finder/`contain` suffices — use `create-finder`.
- Search filters belong to FriendsOfCake Search when installed.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect related associations and existing finders.
3. Check whether CounterCache already exists.

## Workflow

1. Prefer a named finder on the owning Table.
2. Use Query expressions for subqueries/EXISTS; bind values.
3. For counters, prefer CounterCache behavior over scattered increments.
4. Keep controllers calling the finder only.
5. Add Table tests for the query shape.

## Framework decisions

- `contain-vs-matching`, advanced-orm rule

## Anti-patterns

- Unbound string SQL
- Controller-owned subquery graphs

## Validation

- Finder returns correct IDs; no N+1 surprise; tests cover edge cases.

## Completion criteria

- Finder/behavior landed; controller thin; tested.

