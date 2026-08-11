---
name: restore-or-purge-trash
description: Restore or permanently purge soft-deleted records via muffin/trash APIs.
---

# Restore or purge trash

## Objective

Restore trashed entities or permanently purge them using Trash Table APIs with proper authorization.

## Use when

- Admin undelete / empty-trash flows when Trash is installed.

## Do not use when

- Trash is not installed.
- The operation is a normal user soft-delete — use standard delete/trash.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Confirm AuthZ expectations for purge (destructive).
3. Inspect existing restore/purge commands or controllers.

## Workflow

1. Load trashed rows via the project’s with-trashed / only-trashed finder pattern.
2. Restore with `restoreTrash` / cascading restore as needed.
3. Purge with `emptyTrash` or `delete(..., ['purge' => true])` only when intended.
4. Authorize destructive purge; audit log if the project does.
5. Test restore and purge paths.

## Framework decisions

- `trash-vs-hard-delete`; security review for purge

## Anti-patterns

- Nulling deleted in controllers
- Unauthenticated emptyTrash

## Validation

- Restore returns row to default finds; purge removes permanently.

## Completion criteria

- Restore/purge entry points use Trash APIs and are authorized/tested.
