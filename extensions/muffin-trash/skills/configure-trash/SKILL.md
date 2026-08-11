---
name: configure-trash
description: Attach and configure muffin/trash soft-delete on a CakePHP Table.
---

# Configure Trash

## Objective

Enable `Muffin/Trash.Trash` on a Table with the correct datetime field and event options for the project.

## Use when

- Adding soft-delete to a resource and `muffin/trash` is installed.

## Do not use when

- Package absent — stop; do not invent SoftDeletes.
- The project intentionally hard-deletes this Table.

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm `muffin/trash` ^4.
2. Inspect neighboring Tables’ Trash config and column names.
3. Check migrations for `deleted`/`trashed`/custom field.

## Workflow

1. Ensure nullable datetime column exists (migration if needed).
2. `addBehavior('Muffin/Trash.Trash', …)` with field/events as neighbors do.
3. Verify default finds omit trashed rows.
4. Document purge/restore entry points (admin command/controller).
5. Add Table tests for trash, find exclusion, restore.

## Framework decisions

- `trash-vs-hard-delete`

## Anti-patterns

- Laravel SoftDeletes trait
- Manual deleted_at filters duplicating Trash

## Validation

- delete soft-marks; find skips trashed; restore works.

## Completion criteria

- Behavior configured; column present; tests cover trash/find/restore.
