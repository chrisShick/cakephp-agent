---
name: review-crud-security
description: Review FriendsOfCake CRUD controllers/listeners for mass assignment, serialization, RelatedModels, and IDOR risks.
---

# Review CRUD security

## Objective

Security-review CRUD usage: entity accessibility, API serialization, RelatedModels, and id→mutate paths — capability-aware for Authorization.

## Use when

- Reviewing CRUD API/HTML controllers or listeners.
- Hardening before release on CRUD-heavy apps.

## Do not use when

- CRUD is not installed — use `review-cakephp-security` only.

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm `friendsofcake/crud` ^7.
2. Check whether `cakephp/authorization` is installed.
3. Inspect `_accessible`, `$_hidden`, RelatedModels, and find/edit/delete paths.
4. Note Api / JsonApi listeners in use.

## Workflow

1. Mass assignment: reject wide accessibility on sensitive fields and associations.
2. Serialization: ensure Api responses cannot leak secrets.
3. RelatedModels: allow-list associations deliberately.
4. IDOR: view/edit/delete must authorize or scope — not only load by id.
5. Hand off to AuthZ skills when the package is present; else flag missing server checks vs project model.
6. Pair with `review-crud-controller` for ownership issues.

## Framework decisions

- Core security anti-patterns; AuthZ pack when installed
- `crud-listener-vs-orm-callback` (invariants stay on Tables)

## Anti-patterns

- Assuming CRUD implies authorization
- Inventing Laravel Policies when Authorization is absent

## Validation

- Findings cite controller/listener/entity evidence and remediations.

## Completion criteria

- Prioritized CRUD security review with concrete fixes.
