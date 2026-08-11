---
name: authorize-crud-action
description: Wire CakePHP Authorization into FriendsOfCake CRUD actions (scope index, authorize view/edit/delete).
---

# Authorize CRUD action

## Objective

Ensure CRUD actions participate in Authorization: scoped finds and per-resource authorize checks matching project Policy style.

## Use when

- Both `friendsofcake/crud` and `cakephp/authorization` are installed.
- Hardening CRUD view/edit/delete/index against IDOR.

## Do not use when

- Either package is missing — stop; do not invent the missing APIs.

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm both packages.
2. Inspect existing policies, RequestAuthorizationMiddleware, and CRUD listeners.
3. Note how neighboring CRUD controllers authorize today.

## Workflow

1. Scope index via finder or `Crud.beforePaginate` using Authorization scope APIs the project uses.
2. Authorize the loaded subject on view/edit/delete (listener or controller hook matching neighbors).
3. Keep persistence invariants on Tables; AuthZ is permission, not validation.
4. Add integration tests for allowed and forbidden ids.
5. Pair with `review-crud-security` / `create-policy` as needed.

## Framework decisions

- Authorization pack decisions; CRUD listener vs ORM callback

## Anti-patterns

- CRUD find-by-id as sole gate
- Inventing Laravel Policies/Gates

## Validation

- Forbidden ids 403/404 per project; allowed ids succeed; index never leaks others’ rows.

## Completion criteria

- CRUD actions scoped/authorized; tests cover positive and negative access.
