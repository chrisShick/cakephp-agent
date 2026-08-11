---
name: add-search-filter
description: Add or refine a FriendsOfCake Search filter on a Table.
---

# Add search filter

## Objective

Add a focused Search filter for a request parameter without bloating the Table or controller.

## Use when

- New list filter fields (text, boolean, select, related, callback).
- Tightening an existing filter’s query semantics.

## Do not use when

- Search not installed.
- The logic is a reusable non-HTTP query semantic → prefer a custom finder (core `create-finder`).

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Existing `searchManager` / filter map on the Table.
3. Form/query parameter names in the UI.

## Workflow

1. Choose the appropriate filter type per installed Search docs.
2. Register on the Table search manager with clear param naming.
3. Keep callbacks small; delegate shared query bits to finders if needed.
4. Update forms/templates if param names change.
5. Test the filter’s include/exclude behavior.

## Framework decisions

- `rules/filters-and-manager.mdc`

## Anti-patterns

- God callback filters
- Silent SQL injection via raw user strings — use ORM expressions/bindings

## Validation

- Intended rows match; unrelated filters unaffected.

## Completion criteria

- Filter declared, wired, tested.
