---
name: review-query-performance
description: Review CakePHP ORM usage for N+1, over-eager contain, unbounded lists, and unjustified caching.
---

# Review query performance

## Objective

Audit a hot path for ORM performance smells and recommend CakePHP-native fixes (finders, contain budgets, pagination, selective caching).

## Use when

- A list/detail endpoint is slow or suspected of N+1.
- Reviewing a PR that adds contains/loops/cache.

## Do not use when

- The issue is pure PHP CPU unrelated to ORM — profile first.
- You need to implement a finder already identified — use `create-finder` / `diagnose-orm-query`.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Read the action/service path and resulting queries (DebugKit/logs when available).
3. Note pagination and association graphs.

## Workflow

1. Check for per-row queries (hidden N+1).
2. Check contain graphs for over-fetching.
3. Check lists for missing pagination/bounds.
4. Treat cache as last mile with invalidation — not a substitute for query ownership.
5. Hand off fixes to `paginate-results`, `create-finder`, or `diagnose-orm-query`.

## Framework decisions

- Anti-patterns `hidden-n-plus-one`, `over-eager-contain`
- `contain-vs-matching` / `contain-vs-join` when relevant

## Anti-patterns

- Recommending Redis before fixing the query.
- Laravel Eloquent mental models.

## Validation

- Findings cite code/query evidence and a CakePHP-native remediation.

## Completion criteria

- Written review with prioritized ORM fixes and next skills.
