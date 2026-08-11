---
id: trash-vs-hard-delete
type: decision
scope: orm
framework: cakephp
package: muffin/trash
framework_versions: [">=5.0 <6.0"]
package_versions: [">=4.0 <5.0"]
priority: high
truth_level: PLUGIN_SEMANTIC
sources:
  - https://github.com/usemuffin/trash
last_verified: 2026-08-11
related: [behavior-vs-service, bulk-update-vs-entity-save]
evaluations: [soft-delete-prefers-muffin-trash-when-installed, reject-laravel-softdeletes-trait, trash-absent-no-softdeletes-api]
---

# Trash vs hard delete

## Use cases

- User-facing delete that must be recoverable.
- Permanent purge of trashed data.

## Decision questions

1. Is `muffin/trash` installed on this Table?
2. Must the row remain for audit/restore?
3. Is this an admin empty-trash / GDPR-style purge?

## Recommended outcome

- **Soft delete (Trash)** for normal delete when the behavior is attached.
- **Hard delete / purge** only with explicit intent and authorization.

## Rejected alternatives

- Laravel SoftDeletes trait / global scopes as CakePHP defaults.
- Hand-rolled deleted flags that fight Trash’s beforeFind.

## Exceptions

- Tables without Trash may hard-delete or use another documented strategy — do not invent Trash APIs.

## Examples

User clicks Delete on an article → `delete()` / `trash()`. Nightly purge of 90-day trash → `emptyTrash` with authz.

## Evaluations

- `soft-delete-prefers-muffin-trash-when-installed`
- `reject-laravel-softdeletes-trait`
- `trash-absent-no-softdeletes-api`
