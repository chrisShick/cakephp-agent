---
name: create-behavior
description: Create or extend a CakePHP Behavior for cross-table ORM lifecycle capabilities.
---

# Create behavior

## Objective

Implement a focused Behavior that attaches reusable find/save/delete (or related) capabilities to Tables without becoming a god mixin or a substitute for a single-Table finder.

## Use when

- Multiple Tables need the same callbacks, finders, or field management.
- Adding Timestamp/Tree/soft-delete style capabilities not already provided by an installed plugin.

## Do not use when

- The concern is query semantics for one Table — use `create-finder`.
- Ownership is unclear — use `choose-cakephp-abstraction` / `review-abstraction-choice`.
- An installed plugin already provides the Behavior — prefer the plugin.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Search existing Behaviors and Composer plugins for the same capability.
3. List Tables that will `addBehavior` and required config keys.
4. Decide which callbacks/finders belong in the Behavior vs Table-specific code.

## Workflow

1. Confirm cross-table reuse; otherwise stop and use a finder/Table method.
2. Create a Behavior class in the project’s Behavior namespace/path.
3. Implement only the coherent theme (callbacks, finders, helpers).
4. Attach via `initialize()` / `addBehavior` with explicit config.
5. Avoid HTTP, authz, and unrelated domain dump.
6. Add Table tests covering behavior-enabled save/find paths.

## Framework decisions

- `knowledge/decisions/finder-vs-behavior`
- `knowledge/decisions/behavior-vs-service`
- Smell awareness: `god-behavior`

## Anti-patterns

- God behaviors mixing unrelated concerns.
- Behavior for a single finder used on one Table.
- Services that manually reimplement Behavior callbacks on every Table.

## Validation

- Attached Tables exhibit the capability; unattached Tables do not.
- Tests cover callback/finder behavior and config edge cases.

## Completion criteria

- Behavior implemented, attached where needed, tested, and scoped to one theme.
