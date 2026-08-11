---
name: migrate-controller-to-crud
description: Migrate a hand-rolled CakePHP controller toward FriendsOfCake CRUD without losing ORM ownership.
---

# Migrate controller to CRUD

## Objective

Move an existing controller onto FriendsOfCake CRUD incrementally, preserving Table validation/rules and project conventions.

## Use when

- Reducing boilerplate in apps that already standardized on CRUD.
- Aligning a legacy controller with neighboring CRUD controllers.

## Do not use when

- CRUD is not installed or the team rejected CRUD for this area.
- The controller’s flow is fundamentally non-CRUD (custom multi-step workflows) unless carefully scoped.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Diff against a reference CRUD controller in the same app.
3. Inventory custom logic that must become config, listeners, or stay on the Table.

## Workflow

1. Enable CRUD for one action at a time when risk is high.
2. Map each custom block → config / CRUD event / Table ownership.
3. Remove duplicated boilerplate only after parity tests pass.
4. Extract listeners for reusable lifecycle pieces.
5. Keep redirects/flash/API shaping in CRUD layer; keep invariants on Table.

## Framework decisions

- `crud-listener-vs-orm-callback`
- Do not drop application rules during migration

## Anti-patterns

- Big-bang rewrite without tests
- Moving uniqueness into listeners during migration
- Leaving dead hand-rolled code paths beside Crud execute

## Validation

- Behavior parity for migrated actions; Table tests still pass.

## Completion criteria

- Migrated actions use CRUD; remaining custom logic has explicit ownership; tests updated.
