---
name: select-crud-event
description: Choose the correct FriendsOfCake CRUD event from concern ownership, not event-name similarity.
---

# Select CRUD event

## Objective

Pick the right `Crud.*` event (or decide the concern does not belong in CRUD) based on guarantees and ownership.

## Use when

- Unsure which CRUD lifecycle hook to use.
- A similarly named ORM callback is tempting.

## Do not use when

- Config already solves it.
- CRUD is not installed.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Name the concern and required guarantee (before query, after save, before render, etc.).
3. List events the target action emits (CRUD docs / existing listeners).

## Workflow

1. Ask: CRUD-only HTTP/action concern vs all-entry-points persistence concern?
2. If persistence invariant → application rule / Table (stop).
3. If field format → validation (stop).
4. If reusable query outside CRUD → Table finder (stop).
5. Otherwise map to the narrowest CRUD event (`beforePaginate`, `beforeFind`, `beforeSave`, `afterSave`, `beforeRender`, `beforeRedirect`, `setFlash`, …).
6. Hand off to `create-crud-listener` / `modify-crud-action`.

## Framework decisions

- `crud-listener-vs-orm-callback`
- Core `select-lifecycle-hook` for non-CRUD layers

## Anti-patterns

- Matching on “beforeSave” without distinguishing `Crud.beforeSave` vs Model callbacks
- Putting redirects in Table callbacks

## Validation

- Selected event provides the needed timing/subject; rejected alternatives listed.

## Completion criteria

- Event (or non-CRUD layer) chosen with rationale.
