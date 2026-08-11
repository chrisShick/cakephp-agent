---
name: review-migrations
description: Review cakephp/migrations changes for safety, naming, and non-Laravel substitutions.
---

# Review migrations

## Objective

Review migration diffs for CakePHP Migrations idioms, destructive-change risk, and honesty about plugin presence.

## Use when

- Reviewing PRs that touch `config/Migrations`.
- Auditing schema drift vs migration history.

## Do not use when

- Migrations is not installed — report that instead of inventing APIs.

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm Composer has Migrations.
2. Read new migrations and neighboring history.
3. Check deploy docs for expand/contract expectations.

## Workflow

1. Confirm plugin detection before prescribing APIs.
2. Flag destructive changes without rollout notes.
3. Flag Artisan/Laravel migration inventions.
4. Check seeds for secrets.
5. Recommend split/reorder when unrelated changes are bundled opaquely.

## Framework decisions

- `migration-vs-raw-sql`

## Anti-patterns

- Approving credential-bearing seeds
- Demanding Laravel migrator tooling

## Validation

- Findings cite migration files and preferred CakePHP Migrations remediation.

## Completion criteria

- Written review with severity-ordered schema risks.
