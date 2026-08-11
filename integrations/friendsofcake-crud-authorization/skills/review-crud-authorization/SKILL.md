---
name: review-crud-authorization
description: Review CRUD + Authorization composition for IDOR and scoping gaps.
---

# Review CRUD authorization

## Objective

Review combined CRUD + Authorization usage for missing scopes, authorize calls, and duplicated ACL logic in listeners.

## Use when

- Both packs enabled; reviewing CRUD controllers touching resources.

## Do not use when

- Authorization or CRUD is absent.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Policies, middleware, CRUD listeners, index finders.

## Workflow

1. Map each CRUD action to its authorize/scope path.
2. Flag get-by-id without AuthZ.
3. Flag AuthZ-only-in-UI still present.
4. Recommend `authorize-crud-action` / `create-policy` / `scope-query`.

## Framework decisions

- `crud-actions-require-authorization` rule

## Anti-patterns

- Parallel permission systems inside CRUD listeners

## Validation

- Findings cite action + policy/scope evidence.

## Completion criteria

- Written IDOR/scoping review with remediations.
