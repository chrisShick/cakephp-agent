---
name: add-association
description: Define or adjust CakePHP ORM associations (belongsTo, hasMany, belongsToMany, etc.) with correct foreign keys and loading strategy.
---

# Add association

## Objective

Add or correct Table associations so relationships are explicit, conventional, and safe to `contain` / query.

## Use when

- Modeling a new relationship between Tables.
- Fixing wrong foreign keys, join tables, or association aliases.
- Introducing association-dependent finders or rules (`existsIn`).

## Do not use when

- You only need to load related data once without a lasting association (rare; prefer proper association).
- The task is bulk update strategy (see `bulk-update-vs-entity-save` via abstraction skill).

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect both Table classes, Entities, schema/migrations, and existing `initialize()` associations.
3. Note naming conventions (alias vs class, foreignKey, joinTable, through).
4. Check how related data is loaded today (`contain`, `matching`, manual joins).

## Workflow

1. Confirm Tables and schema columns for the relationship.
2. Choose association type (`belongsTo`, `hasOne`, `hasMany`, `belongsToMany`) from ownership of the FK / join table.
3. Configure association in `initialize()` with explicit `foreignKey` / `joinTable` when non-conventional.
4. Align Entity `_accessible` / `_hidden` only as needed; do not overexpose mass assignment.
5. Prefer `contain` for loading; use `matching` / `innerJoinWith` when filtering parents by child conditions (`contain-vs-matching`).
6. Add tests for association loading and, if relevant, `existsIn` rules.

## Framework decisions

- `knowledge/decisions/contain-vs-matching`
- `knowledge/decisions/plugin-vs-application-code` if a plugin Table is involved

## Anti-patterns

- Over-eager default `contain` of large graphs.
- Wrong FK silently returning empty associations.
- Mass-assignment overexposure of nested entities.
- Re-implementing joins in controllers instead of associations/finders.

## Validation

- Association loads expected related rows in a test or REPL-style check.
- Filtering uses `matching`/`innerJoinWith` when the intent is filter-not-just-load.
- No Laravel relationship API invented.

## Completion criteria

- Associations defined on the owning/non-owning sides as required by the domain.
- Documented aliases match call sites; tests cover load and/or constraint behavior.
