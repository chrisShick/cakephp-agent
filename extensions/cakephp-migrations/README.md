# cakephp-migrations

Capability pack for **`cakephp/migrations`** `^4 || ^5` on CakePHP 5.

## Detection

Enabled when Composer has `cakephp/migrations` satisfying `^4.0 || ^5.0`.

## Contents

- Rules: migration ownership, schema-change hygiene
- Skills: `create-migration`, `review-migrations`
- Decision: `migration-vs-raw-sql`

## Honesty

If Migrations is not installed, do not invent Phinx/Laravel migration APIs — use the project’s existing schema strategy or recommend installing `cakephp/migrations` explicitly.
