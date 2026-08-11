---
name: review-authn-authz-boundaries
description: Review combined Authentication + Authorization setup for clear identity vs permission boundaries.
---

# Review AuthN/AuthZ boundaries

## Objective

When both plugins are present, verify identity establishment and permission checks are composed correctly without conflation.

## Use when

- Both `cakephp-authentication` and `cakephp-authorization` packs are enabled.
- Reviewing end-to-end secured endpoints.

## Do not use when

- Only one of the packages/packs is present.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Middleware order; identity access; authorize call sites; tests for anonymous/authenticated/forbidden/allowed.

## Workflow

1. Confirm Authentication establishes identity before Authorization needs it.
2. Confirm policies use that identity and still deny unauthorized resources.
3. Flag login-success treated as global permission.
4. Recommend AuthN vs AuthZ skills for fixes — do not duplicate their workflows.

## Framework decisions

- Integration rule `identity-feeds-authorization`

## Anti-patterns

- Duplicate parallel current-user mechanisms
- Skipping authorize because middleware authenticated

## Validation

- Matrix covered: anonymous, authed-forbidden, authed-allowed.

## Completion criteria

- Boundary review with actionable split remediations.
