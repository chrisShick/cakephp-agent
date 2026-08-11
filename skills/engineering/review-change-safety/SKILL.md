---
name: review-change-safety
description: Review a change for clean-code scope, testing discipline, and dependency honesty.
---

# Review change safety

## Objective

Quick engineering review: scope control, tests at the right layer, and Composer honesty — complementary to CakePHP/PHP security reviews.

## Use when

- Finishing a PR checklist.
- An agent change looks sprawling or under-tested.

## Do not use when

- You need deep CakePHP security — use `review-cakephp-security`.

## Inputs to discover

1. Follow **`inspect-before-coding`** on the diff.
2. Note Composer changes and test paths.
3. Identify skipped tests or drive-by files.

## Workflow

1. Flag unrelated refactors.
2. Flag missing tests for the changed layer.
3. Flag inventing undeclared packages.
4. Flag silent catches.
5. Summarize must-fix vs nice-to-have.

## Framework decisions

- Engineering rules: clean-code, testing-discipline, dependency-honesty

## Anti-patterns

- Expanding review into a rewrite
- Requiring plugins that are not installed

## Validation

- Notes cite files and concrete follow-ups.

## Completion criteria

- Short engineering review with clear blockers.
