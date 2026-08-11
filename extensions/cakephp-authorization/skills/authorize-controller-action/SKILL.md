---
name: authorize-controller-action
description: Add Authorization checks to a CakePHP controller action (IDOR-safe).
---

# Authorize controller action

## Objective

Ensure a controller action authorizes the target resource before expose/mutate, preventing IDOR.

## Use when

- View/edit/delete-by-id endpoints lacking checks.
- Hardening APIs that accept resource ids.

## Do not use when

- Authorization not installed.
- Only configuring login.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. How neighboring actions call authorize/can.
3. Identity availability on the request.

## Workflow

1. Load resource by id using project Table/finder patterns.
2. Authorize against the loaded resource (not only “is logged in”).
3. Prefer scoped queries when listing collections.
4. Keep patch field lists / `_accessible` tight.
5. Add tests: other user’s id → forbidden/not found per convention.

## Framework decisions

- `rules/idor-and-scoping.mdc`
- Mass-assignment anti-pattern awareness

## Anti-patterns

- Authorizing a class/action without the instance when ownership matters
- Trusting client-supplied owner ids without checks

## Validation

- Cross-user id access denied; owner/admin paths succeed as designed.

## Completion criteria

- Action authorized; IDOR test coverage added.
