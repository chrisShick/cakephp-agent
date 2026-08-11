---
name: modify-crud-action
description: Change FriendsOfCake CRUD action behavior via configuration or the narrowest lifecycle event.
---

# Modify CRUD action

## Objective

Adjust an existing CRUD action using configuration first, then the narrowest CRUD event/listener — without forking plugin internals.

## Use when

- Changing find/paginate behavior, messages, related models, redirects, or action-specific hooks.

## Do not use when

- Building a new controller (`create-crud-controller`)
- Choosing where a brand-new concern belongs (`select-crud-event` / `choose-cakephp-abstraction`)

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Read current action config and listeners (`analyze-crud-controller`).
3. Check CRUD docs for the installed version's config keys.

## Workflow

1. Prefer documented configuration.
2. If insufficient, select the correct `Crud.*` event (`select-crud-event`).
3. Implement in existing listener or create one (`create-crud-listener`).
4. Keep ORM invariants on the Table.
5. Update tests for the changed behavior.

## Framework decisions

- Decision order in `rules/crud.mdc` and `crud-listener-vs-orm-callback`

## Anti-patterns

- Copying Crud action class code into the app without need
- Fixing persistence bugs only in the HTTP listener

## Validation

- Behavior matches intent; unrelated actions unchanged.

## Completion criteria

- Change implemented at the lowest appropriate layer with tests.
