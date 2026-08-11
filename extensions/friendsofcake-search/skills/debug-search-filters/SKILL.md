---
name: debug-search-filters
description: Diagnose FriendsOfCake Search filters that return wrong or empty results.
---

# Debug search filters

## Objective

Find why Search filters do not match expected rows (param names, filter config, apply path, pagination).

## Use when

- Filters ignored, always empty, or wrong SQL conditions.
- PRG/query-string state lost across pages.

## Do not use when

- Search not involved; use `diagnose-orm-query`.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Reproduce with known params; capture query string and SQL.
3. Inspect Table filters and controller apply/paginate path.

## Workflow

1. Confirm Search is applied to the query used for pagination.
2. Verify param names match filter configuration.
3. Check filter type/options (like vs value, empty values, defaults).
4. Fix Table/controller; add regression test.

## Framework decisions

- Prefer fixing filters over controller `where` band-aids

## Anti-patterns

- Disabling Search and hard-coding conditions permanently

## Validation

- Known fixtures filter correctly; pagination retains params.

## Completion criteria

- Root cause + fix + test.
