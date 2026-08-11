---
name: add-route
description: Add or adjust CakePHP Router mappings in config/routes.php without putting domain logic in route files.
---

# Add route

## Objective

Connect a URL to the correct controller action using CakePHP routing (`connect`, scopes, prefixes, resources, named routes) consistent with the app’s existing route style.

## Use when

- Exposing a new HTTP endpoint or changing an existing path.
- Adding prefixed/scoped/API resource routes.

## Do not use when

- Ownership of the action’s domain logic is unclear — run `choose-cakephp-abstraction` first.
- You only need to audit existing routes — use `review-routing`.
- The work is CLI-only — use `create-command`.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Read `config/routes.php` and plugin route files already loaded.
3. Note dashed vs underscored, prefixes, extensions, and named-route conventions.
4. Confirm the target controller/action exists or will be created (`create-controller-action` / `create-api-endpoint`).

## Workflow

1. Prefer the same routing style as neighboring endpoints (scoped resources vs explicit `connect`).
2. Add the route in the appropriate scope/prefix; keep the callable declarative.
3. Do not put persistence, validation, or authorization inside the route file.
4. Name the route if the project relies on `_name` / path helpers.
5. Verify with `bin/cake routes` (never Artisan).
6. Add or update an integration test that hits the path if the project tests routes.

## Framework decisions

- `knowledge/decisions/route-config-vs-controller-url-logic`
- Hand off HTTP orchestration to `create-controller-action` / `create-api-endpoint`

## Anti-patterns

- Laravel `Route::` facades or Artisan mental models.
- Domain/persistence logic in route closures.
- Duplicate conflicting connects for the same path.

## Validation

- `bin/cake routes` shows the expected mapping.
- Request reaches the intended action; no route-file side effects.

## Completion criteria

- Route added in the correct file/scope, documented if non-obvious, and reachable in tests or manual check.
