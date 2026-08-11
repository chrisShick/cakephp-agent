---
name: configure-search
description: Configure FriendsOfCake Search on a Table and wire controller filtering.
---

# Configure search

## Objective

Enable `friendsofcake/search` for a resource: Table filters/manager plus thin controller application compatible with pagination.

## Use when

- Adding list filtering to an app that has Search installed.
- Standardizing ad-hoc controller `where` into Search filters.

## Do not use when

- Search package is not installed.
- The need is only CRUD without Search (use CRUD pack skills).

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm `friendsofcake/search` ^7.
2. Inspect neighboring Tables’ search configuration and controllers’ apply/paginate patterns.
3. Note whether CRUD is also installed — do not require CRUD APIs here.

## Workflow

1. Load/enable Search behavior/plugin patterns used by the project.
2. Declare filters on the Table search manager.
3. Apply search in the controller using existing project helpers/components.
4. Ensure pagination keeps filter query params.
5. Add tests for filtered and unfiltered lists.

## Framework decisions

- Filters vs finders (`rules/finder-boundaries.mdc`)
- No CRUD assumptions in Search-only apps

## Anti-patterns

- Laravel Scout mental model
- Duplicating filter logic in the controller

## Validation

- Filter params change result sets; empty params behave as designed.

## Completion criteria

- Table filters + controller wiring + tests; no invented CRUD APIs.
