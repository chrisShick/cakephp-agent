---
name: work-with-views
description: Adjust CakePHP templates, cells, or helpers following project conventions.
---


# Work with views

## Objective

Make presentation changes using CakePHP templates/cells/helpers while matching project conventions and keeping domain logic out of templates.

## Use when

- Updating templates/elements/cells/helpers for an existing CakePHP UI.
- Extracting a reusable cell/helper the project already patterns.

## Do not use when

- The request is primarily ownership/ORM — use those skills.
- Introducing Blade/Livewire into a vanilla CakePHP app — reject.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Mirror neighboring templates/layouts/helpers/cells.
3. Confirm FormHelper/Flash patterns.

## Workflow

1. Prefer smallest template/element change that matches neighbors.
2. Extract a cell/helper only when reuse is real and project-shaped.
3. Keep queries/persistence in Tables; templates display/submit.
4. Pair forms with `create-form` when posting.
5. Spot-check rendering for the action.

## Framework decisions

- `views-out-of-scope-for-core-skills` (still follow project; no new engine)

## Anti-patterns

- Blade/Livewire inventions
- Fat templates with ORM calls

## Validation

- Page renders; conventions match neighbors; no new template engine.

## Completion criteria

- Presentation change landed in CakePHP view layer project-style.

