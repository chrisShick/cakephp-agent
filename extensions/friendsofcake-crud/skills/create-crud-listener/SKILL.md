---
name: create-crud-listener
description: Create or extend a FriendsOfCake CRUD listener for action-lifecycle customization.
---

# Create CRUD listener

## Objective

Implement narrowly scoped CRUD lifecycle behavior in a dedicated listener, using the project's registration and path conventions.

## Use when

- Customizing query, save outcome handling, render/redirect/flash, or related data for CRUD actions.
- Replacing substantial inline `Crud->on` closures with a reusable listener.

## Do not use when

- Config alone can express the change.
- The concern is a global persistence invariant (use `add-application-rule` / Table callbacks).
- CRUD is not installed.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Locate controller path and existing mirrored listener / `.ai/` convention.
3. Inspect controller CRUD config and already-implemented events.
4. Confirm the concern belongs in CRUD lifecycle (`select-crud-event`).

## Workflow

1. Identify target controller and relative path under `src/Controller`.
2. Prefer mirrored `src/Listener/...` unless `.ai/` overrides (PACKAGE_RECOMMENDATION).
3. Reuse an existing listener when appropriate.
4. Extend `Crud\Listener\Base` (or project base) and implement only needed events.
5. Register via the project's established pattern.
6. Keep methods narrow; add tests; verify the request lifecycle.

## Framework decisions

- `crud-listener-vs-orm-callback`
- Listener path mirroring is a recommendation, not a CakePHP requirement

## Anti-patterns

- Giant listeners; uniqueness only in listeners; ORM callbacks for response shaping; CRUD callbacks for rules that must apply outside CRUD

## Validation

- Event fires on the intended action; invariants still enforced on Table saves.

## Completion criteria

- Listener created/updated, registered, tested, with explicit event rationale.
