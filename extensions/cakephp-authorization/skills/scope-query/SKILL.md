---
name: scope-query
description: Apply authorization-aware query scoping for CakePHP Authorization.
---

# Scope query

## Objective

Limit query results to what the identity may see using Authorization scoping patterns already used in the project (or plugin-supported scope APIs).

## Use when

- Index/list endpoints leak other tenants’/users’ rows.
- Policies define scope methods the app already relies on.

## Do not use when

- Package absent.
- A single-resource authorize-after-get is sufficient and scoping APIs unused in the project.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Existing `scope` usage, finder conventions, multi-tenant patterns in `.ai/`.

## Workflow

1. Prefer extending existing scoping patterns over inventing new ones.
2. Apply scope before pagination/serialization.
3. Still authorize mutating actions on loaded entities.
4. Test that out-of-scope rows never appear.

## Framework decisions

- Scoping complements, not replaces, per-resource authorize on writes

## Anti-patterns

- Filtering only in the UI
- Silent empty results without understanding tenant conventions

## Validation

- Lists contain only allowed rows for each identity fixture.

## Completion criteria

- Scoped query path tested for at least two identities.
