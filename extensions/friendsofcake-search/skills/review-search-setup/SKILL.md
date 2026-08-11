---
name: review-search-setup
description: Review FriendsOfCake Search usage for filter ownership and non-CRUD contamination.
---

# Review search setup

## Objective

Review Search configuration for correct Table ownership, thin controllers, and no false CRUD assumptions.

## Use when

- PR review of Search filters/controllers.
- Auditing list endpoints after Search adoption.

## Do not use when

- Search package not in the change and not installed.

## Inputs to discover

1. Follow **`inspect-before-coding`** before prescribing edits.
2. Composer major; Table filters; controller apply path; CRUD presence.

## Workflow

1. Confirm ^7 compatibility.
2. Check filters live on Tables; controllers stay thin.
3. Flag duplicated `where` logic.
4. If CRUD absent, flag any CRUD API recommendations.
5. Report findings with remediations.

## Framework decisions

- Search vs finders; Search vs CRUD boundaries

## Anti-patterns

- Demanding CRUD Search listener when CRUD is absent

## Validation

- Findings cite packages actually installed.

## Completion criteria

- Written Search-focused review.
