---
name: use-bake
description: Use cakephp/bake to scaffold stubs, then immediately review ownership and security.
---

# Use Bake

## Objective

Generate CakePHP stubs with Bake when installed, then edit them to match project ownership, validation/rules, and security norms.

## Use when

- Scaffolding a new resource in an app that has `cakephp/bake`.
- Speeding up boilerplate before hand-tuning.

## Do not use when

- Bake is not installed — write code by hand with core skills.
- You need a one-line fix — do not re-bake.

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm `cakephp/bake` ^3.
2. Note Bake themes/templates the project customizes.
3. Plan post-bake edits (rules, finders, authz).

## Workflow

1. Run the appropriate `bin/cake bake …` command for the resource.
2. Review generated controllers for fatness; move logic to Tables/finders/rules.
3. Tighten `_accessible`, validation, and application rules.
4. Add tests; do not trust Bake coverage.
5. Run `cakephp-code-review` / smell detection on the diff.

## Framework decisions

- Core ownership decisions still apply after generation
- Plugin honesty: no Bake APIs when undetected

## Anti-patterns

- Artisan make-* inventions
- Committing Bake output with mass-assignment wide open

## Validation

- App boots; generated routes/actions work; ownership review notes addressed.

## Completion criteria

- Stubs generated (if useful) and edited to project standards with tests.
