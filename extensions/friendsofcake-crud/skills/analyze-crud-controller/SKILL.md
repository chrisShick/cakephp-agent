---
name: analyze-crud-controller
description: Orient to an existing FriendsOfCake CRUD controller — actions, config, listeners, and ownership gaps.
---

# Analyze CRUD controller

## Objective

Summarize how a controller uses FriendsOfCake CRUD and where customization lives (config vs listeners vs Table).

## Use when

- Entering an unfamiliar CRUD controller.
- Before modifying CRUD behavior or migrating more actions to CRUD.

## Do not use when

- `friendsofcake/crud` is not installed (stop; do not invent CRUD APIs).
- You already know the layout and only need a specific change skill.

## Inputs to discover

1. Follow **`inspect-before-coding`** (Composer must show `friendsofcake/crud` ^7).
2. Controller Crud component setup, enabled actions, `Crud->execute()` usage.
3. Related listeners (mirrored `src/Listener/...` or project convention / `.ai/`).
4. Table validation, rules, and finders used by the resource.

## Workflow

1. Confirm CRUD package presence and version major.
2. Map enabled actions and config.
3. List listeners/events hooked for this controller.
4. Note ORM ownership (rules/validation) vs CRUD-only hooks.
5. Report gaps/risks; do not refactor unless asked.

## Framework decisions

- Extension decision `crud-listener-vs-orm-callback`
- Prefer config → CRUD event → model layer per `rules/crud.mdc`

## Anti-patterns

- Assuming Search/Auth listeners without those packages
- Treating CRUD as replacing RulesChecker

## Validation

- Notes cite concrete files and whether each concern is config, CRUD event, or ORM.

## Completion criteria

- Written orientation ready for `modify-crud-action`, `create-crud-listener`, or `review-crud-controller`.
