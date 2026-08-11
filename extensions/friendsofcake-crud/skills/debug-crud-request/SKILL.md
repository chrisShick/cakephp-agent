---
name: debug-crud-request
description: Diagnose a failing FriendsOfCake CRUD request — config, events, subject, and ORM boundaries.
---

# Debug CRUD request

## Objective

Find why a CRUD action misbehaves (wrong entity, skipped listener, bad redirect, unexpected validation) and fix at the correct layer.

## Use when

- CRUD action returns unexpected results, skips customization, or errors during execute.
- Confusion between CRUD and ORM lifecycles.

## Do not use when

- The bug is clearly a pure SQL/ORM issue with no CRUD involvement (`diagnose-orm-query`).

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Reproduce the action; note HTTP method, action name, Crud config, listeners.
3. Check whether `return $this->Crud->execute()` is present where required.
4. Inspect Table validation/rules and SQL if persistence fails.

## Workflow

1. Confirm CRUD handles the action (enabled + execute).
2. Trace listeners/`on()` hooks for that action.
3. Classify: config mistake, event choice, subject misuse, ORM validation/rules, or routing.
4. Fix with `modify-crud-action` / listener / Table as appropriate.
5. Add regression coverage.

## Framework decisions

- `crud-listener-vs-orm-callback`
- Core `diagnose-orm-query` when the query itself is wrong

## Anti-patterns

- Adding more listeners without confirming registration
- “Fixing” uniqueness only in the listener after a race

## Validation

- Root cause named; fix verified on the failing request path.

## Completion criteria

- Diagnosis + fix + test or clear verification notes.
