---
name: review-authentication-flow
description: Review CakePHP Authentication setup for correctness and AuthZ non-contamination.
---

# Review authentication flow

## Objective

Review Authentication configuration and usage for secure identity handling without inventing authorization APIs.

## Use when

- PR review of login/middleware/authenticator changes.
- Auditing identity establishment.

## Do not use when

- Reviewing policies/IDOR (use Authorization review skills).

## Inputs to discover

1. Follow **`inspect-before-coding`** before prescribing edits.
2. Composer majores; Application middleware; login/logout; password hashing.
3. Whether Authorization is present (mention boundary only).

## Workflow

1. Confirm package ^4 compatibility.
2. Review middleware order and unauthenticated handling.
3. Review authenticators/identifiers and secret handling.
4. Flag any assumption that login grants resource permissions.
5. Report findings with remediations.

## Framework decisions

- `rules/separation.mdc`

## Anti-patterns

- Demanding Authorization policies in an AuthN-only app
- Laravel auth review checklists

## Validation

- Findings cite files and installed packages only.

## Completion criteria

- Written review focused on identity/authn.
