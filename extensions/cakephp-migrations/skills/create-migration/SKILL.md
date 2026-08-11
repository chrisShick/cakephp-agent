---
name: create-migration
description: Create a cakephp/migrations schema migration when the plugin is installed.
---

# Create migration

## Objective

Add a versioned schema migration using `cakephp/migrations` that matches project naming and backend conventions.

## Use when

- Changing tables/columns/indexes in an app that has Migrations installed.
- Replacing ad-hoc SQL with a tracked migration.

## Do not use when

- `cakephp/migrations` is not installed — stop; do not invent Laravel/Artisan migrations.
- The change is application data logic, not schema — use Table/commands.

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm `cakephp/migrations` ^4/^5.
2. Inspect existing `config/Migrations` style (builtin vs legacy patterns).
3. Note deploy/rollback expectations.

## Workflow

1. Generate or create a migration class the project’s way (`bin/cake bake migration` only if Bake is installed and used).
2. Encode the schema change clearly; keep unrelated changes out.
3. Provide `down()`/reverse when the project expects reversibility.
4. Run migrate in the project’s documented environment.
5. Update Table schema usage/tests as needed.

## Framework decisions

- Extension decision `migration-vs-raw-sql`
- Do not assume Bake APIs unless `cakephp/bake` is installed

## Anti-patterns

- Artisan `make:migration` mental model
- Irreversible destructive changes without a plan
- Skipping Migrations when it is the project standard

## Validation

- `bin/cake migrations migrate` (or project equivalent) applies cleanly on a fresh DB path used by the team.

## Completion criteria

- Migration committed; applied in dev; schema consumers updated.
