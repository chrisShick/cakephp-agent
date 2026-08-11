---
name: analyze-cakephp-project
description: Orient to a CakePHP application — versions, plugins, architecture, and where common extension points live.
---

# Analyze CakePHP project

## Objective

Produce a concise orientation of a CakePHP app so later work follows project reality instead of generic assumptions.

## Use when

- First contact with an unfamiliar CakePHP codebase.
- Before a larger feature or review when architecture context is missing.
- Preparing notes for humans or other skills.

## Do not use when

- You only need a narrow ownership decision (use `choose-cakephp-abstraction`).
- Discovery for a single small edit already covered by `inspect-before-coding`.

## Inputs to discover

1. Follow **`inspect-before-coding`** (Composer, `.ai/`, Application, routes, samples of Controllers/Tables).
2. Identify app type clues (web, API, hybrid), plugin list, auth packages if present.
3. Note test layout (`tests/TestCase`, fixtures) and coding standards.
4. Sample one vertical slice (route → controller → table → entity) in a representative area.

## Workflow

1. Run `inspect-before-coding` for versions and packages.
2. Summarize: CakePHP/PHP versions, notable plugins, `.ai/` highlights.
3. Map directory conventions (`src/`, `plugins/`, `templates/`, `config/`).
4. Describe established patterns (finders, listeners, middleware, components) with file examples.
5. Call out risks (missing `.ai/`, mixed conventions, optional plugins present but unused).
6. Stop at orientation — do not refactor unless asked.

## Framework decisions

- `knowledge/decisions/plugin-vs-application-code` when classifying custom vs package code.
- Prefer stating **what exists** over prescribing a new architecture.

## Anti-patterns

- Claiming plugin APIs that are not installed.
- Rewriting architecture docs automatically.
- Treating every app as API-only or DDD-mandatory.

## Validation

- Orientation names versions, key packages, and at least one concrete pattern with a path.
- Distinguishes project convention vs framework default when known.

## Completion criteria

- Short written orientation suitable as context for subsequent skills.
- Explicit “unknowns” listed where evidence was missing.
