---
id: migration-vs-raw-sql
type: decision
scope: database
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PLUGIN_SEMANTIC
package: cakephp/migrations
package_versions: [">=4.0 <6.0"]
sources:
  - https://book.cakephp.org/migrations/5/
last_verified: 2026-08-10
related: [plugin-vs-application-code]
evaluations: [schema-change-prefers-migration-when-installed, reject-artisan-migrate-in-cakephp, migrations-absent-no-phinx-apis]
---

# Migration vs raw SQL

## Use cases

- Changing database schema in apps that use `cakephp/migrations`.
- Emergency one-off SQL in break-glass situations.

## Decision questions

1. Is `cakephp/migrations` installed?
2. Is the change a durable schema change that other environments must repeat?
3. Is this a documented emergency hotfix with a follow-up migration?

## Recommended outcome

- **Migration** when the plugin is installed and the change must be reproducible.
- **Raw SQL** only for break-glass, with a follow-up migration when Migrations is standard.

## Rejected alternatives

- Laravel Artisan migration commands in CakePHP apps.
- Silent production schema edits with no history when Migrations is the team standard.

## Exceptions

- Apps without Migrations may use another documented schema strategy — do not invent plugin APIs.

## Examples

Add `articles.slug` → new migration. Production pager duty column rename → careful SQL + follow-up migration.

## Evaluations

- `schema-change-prefers-migration-when-installed`
- `reject-artisan-migrate-in-cakephp`
- `migrations-absent-no-phinx-apis`
