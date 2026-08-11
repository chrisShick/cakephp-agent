---
name: paginate-results
description: Wire CakePHP controller pagination over Table finders without unbounded lists or duplicated query graphs.
---

# Paginate results

## Objective

Paginate a list endpoint using CakePHP pagination over a Table finder (or Search filters when installed), keeping query reuse on the model layer.

## Use when

- Adding or fixing HTML/API list pages that must page results.
- Replacing unbounded `all()` / huge contains in list actions.

## Do not use when

- The work is only defining query semantics — use `create-finder` first.
- Ownership is unclear — use `choose-cakephp-abstraction`.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect neighboring paginate calls, page size config, and finder/filter patterns.
3. Note whether FriendsOfCake Search is installed (do not invent it).

## Workflow

1. Extract reusable conditions into a Table finder (or Search filters if pack applies).
2. In the controller, paginate that finder; keep the action thin.
3. Align sort/filter query param handling with project conventions.
4. Cap page size; avoid client-controlled absurd limits unless explicit.
5. Add an integration or controller test for page 1 / empty / out-of-range behavior as the project tests lists.

## Framework decisions

- `knowledge/decisions/paginate-controller-vs-custom-finder`
- Performance: avoid N+1 and over-eager contain (`diagnose-orm-query` / `performance` rule)

## Anti-patterns

- Duplicating query graphs in every paginate call.
- Unbounded lists for UI tables.
- Laravel paginator inventions.

## Validation

- Responses are paged; finder reuse is visible; contain budget is intentional.

## Completion criteria

- List action paginates a finder/filter; tests or manual check confirm paging.
