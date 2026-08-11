---
name: create-crud-controller
description: Create a CakePHP controller wired to FriendsOfCake CRUD actions with thin execute flow.
---

# Create CRUD controller

## Objective

Add a controller that uses FriendsOfCake CRUD for standard actions instead of hand-rolled boilerplate.

## Use when

- Scaffolding a resource controller in an app that already uses CRUD.
- Replacing duplicated index/view/add/edit/delete boilerplate with CRUD.

## Do not use when

- CRUD is not installed.
- The endpoint is a one-off non-CRUD action (use core `create-controller-action` / `create-api-endpoint`).

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm `friendsofcake/crud`.
2. Copy patterns from neighboring CRUD controllers (component load, listeners, prefixes).
3. Identify Table/Entity and route prefixes.

## Workflow

1. Match project Crud component / AppController patterns.
2. Enable only required actions; configure per project norms.
3. Keep actions returning `$this->Crud->execute()` where CRUD-handled.
4. Wire routes; add mirrored listener only if customization is needed.
5. Ensure Table validation/rules exist for persistence invariants.
6. Add controller/integration tests consistent with the app.

## Framework decisions

- `crud-listener-vs-orm-callback`
- Core ownership still applies for validation/rules/finders

## Anti-patterns

- Fat actions beside Crud execute
- Skipping application rules because CRUD is present
- Inventing undocumented Crud config keys

## Validation

- CRUD actions respond; validation/rule failures surface; routes resolve.

## Completion criteria

- Controller + routes + tests; persistence invariants owned by the Table layer.
