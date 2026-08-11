---
name: review-crud-search-integration
description: Review combined CRUD + Search setup for non-duplication and correct ownership.
---

# Review CRUD + Search integration

## Objective

When both packs are enabled, verify Search filters and CRUD lifecycle are composed without duplicated or conflicting guidance.

## Use when

- Both packages/packs present.
- Reviewing CRUD index filtering changes.

## Do not use when

- Only CRUD or only Search is installed.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Table filters; CRUD listeners/config; whether Search listener is loaded.

## Workflow

1. Confirm filters are single-sourced on Tables.
2. Confirm CRUD index applies Search once via project pattern.
3. Flag duplicated conditions or cross-pack API invention.
4. Split remediations to Search vs CRUD vs this integration skill.

## Framework decisions

- `crud-index-with-search`

## Anti-patterns

- Recommending Search-only patterns that ignore existing CRUD execute flow
- Recommending CRUD-only query hacks that ignore Search

## Validation

- Review cites both packages and a single ownership story for filters.

## Completion criteria

- Integration review with clear non-duplication findings.
