---
name: create-controller-action
description: Add or refine a CakePHP controller action that orchestrates HTTP flow without owning persistence logic.
---

# Create controller action

## Objective

Implement a controller action that handles HTTP concerns (authz hooks already in project, request data, redirects/render/status) while delegating query and persistence to Tables and other CakePHP layers.

## Use when

- Adding or changing a standard web controller action.
- Thinning a fat action by moving logic to the correct layer.

## Do not use when

- Building a JSON/API endpoint with API-specific response patterns — prefer `create-api-endpoint` (may still share steps).
- The real question is ownership of domain logic — run `choose-cakephp-abstraction` first.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect routes, neighboring actions, Components, Middleware, Form/CSRF patterns, and flash/redirect conventions.
3. Locate Tables/finders already used for the resource.
4. Check authorization approach **only as the project already implements it** (do not invent plugin APIs).

## Workflow

1. Confirm route → controller → action naming matches project conventions.
2. Keep the action orchestration-only: read request, call Table/finder, branch on success/failure, set response.
3. Use `newEntity`/`patchEntity` + `save` rather than raw SQL or `Connection::execute` in the controller.
4. Avoid embedding complex query building; extract a finder if needed (`create-finder`).
5. Respect mass-assignment (`_accessible`) and CSRF/form patterns already in the app.
6. Add controller/integration tests consistent with project style.

## Framework decisions

- Prefer Table/Entity/finder/rules ownership over controller domain logic (`choose-cakephp-abstraction`).
- `knowledge/decisions/component-vs-middleware` for cross-cutting HTTP reuse.
- Anti-pattern awareness: fat-controller, persistence-concern-in-controller.

## Anti-patterns

- Fat controllers with business rules and query graphs inline.
- Calling remote HTTP APIs from models instead of appropriate app layers (and vice versa dumping persistence in controllers).
- Assuming Authentication/Authorization plugin APIs when not installed.

## Validation

- Action is reachable via routes; success/failure paths behave.
- Persistence goes through Table APIs; validation/rules errors surfaced appropriately.

## Completion criteria

- Action implemented thinly, wired to routes, tested, with ownership of heavy logic outside the controller.
