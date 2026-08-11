---
name: review-authorization
description: Review CakePHP Authorization usage for policy quality and IDOR gaps.
---

# Review authorization

## Objective

Review policies and authorize call sites for correctness, IDOR risks, and identity-source honesty.

## Use when

- PR review of Policy/controller authorization changes.
- Security pass on mutating endpoints.

## Do not use when

- AuthZ package not involved in the change.

## Inputs to discover

1. Follow **`inspect-before-coding`** before edits.
2. Composer; policy map; controller authorize calls; mass-assignment on entities.

## Workflow

1. Confirm ^3 package compatibility.
2. Check every mutating/sensitive read path for authorization.
3. Check IDOR on id-based get+update/delete.
4. Check identity source assumptions vs installed packages.
5. Report findings with concrete remediations.

## Framework decisions

- `rules/idor-and-scoping.mdc`, `rules/identity-source.mdc`

## Anti-patterns

- Requiring Authentication APIs in AuthZ-only apps
- Nitpicking style while missing missing authorize calls

## Validation

- Findings cite evidence and package reality.

## Completion criteria

- Prioritized authorization review written.
