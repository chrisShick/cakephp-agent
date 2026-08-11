---
name: wire-crud-index-search
description: Wire FriendsOfCake CRUD index actions to FriendsOfCake Search filters without duplicating logic.
---

# Wire CRUD index search

## Objective

Connect CRUD index/query lifecycle to Table Search filters using project patterns — filters declared once, applied via CRUD+Search composition.

## Use when

- Both `friendsofcake/crud` and `friendsofcake/search` are installed and packs enabled.
- CRUD index needs request filtering.

## Do not use when

- Only one of the packages is present (use the corresponding single pack skill).

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Neighboring CRUD controllers that already use Search (listener vs `beforePaginate`).
3. Existing Table searchManager filters.

## Workflow

1. Ensure filters exist on the Table (Search pack) — do not redefine in listeners.
2. Wire CRUD index using the app’s established Search integration (Crud Search listener or query event).
3. Avoid duplicate `where` in CRUD listeners.
4. Test filtered index via CRUD action + pagination.

## Framework decisions

- Integration rule `crud-index-with-search`
- CRUD events vs ORM still apply for non-filter concerns

## Anti-patterns

- Copying filter SQL into `Crud.beforePaginate` while also defining Search filters
- Documenting the same guidance in both base packs instead of this integration

## Validation

- Index respects filters; filter definitions remain on the Table only.

## Completion criteria

- CRUD index uses Search; no duplicated filter logic; tests pass.
