---
name: detect-architectural-smells
description: Detect CakePHP architectural smells using the anti-pattern catalog — evidence first, no line-count scoring.
---

# Detect architectural smells

## Objective

Identify architectural smells in a CakePHP codebase or diff using `knowledge/anti-patterns/*`, with evidence, false-positive checks, and preferred refactors — without treating file length as proof.

## Use when

- Auditing a module for ownership and structure problems.
- Reviewing whether a change introduces known CakePHP smells.
- Prioritizing refactors before adding features.

## Do not use when

- You only need to pick an abstraction for a new concern (use `choose-cakephp-abstraction` / `review-abstraction-choice`).
- The task is a narrow bugfix with no structural question.

## Inputs to discover

1. Run **`inspect-before-coding`** first (Composer, `.ai/`, neighboring classes, tests).
2. Identify the area under review (controllers, Tables, entities, behaviors, components, listeners, serializers).
3. Note installed plugins so plugin-specific patterns are not mislabeled as smells.
4. Collect runtime/query evidence when performance smells are suspected (N+1, over-eager contain).

## Workflow

1. Complete discovery via `inspect-before-coding`.
2. Scan for candidates using the anti-pattern catalog (not class length alone):
   - Ownership: `fat-controller`, `fat-table`, `persistence-concern-in-controller`, `http-concern-in-model`
   - ORM style: `active-record-entity`, `anemic-entity`, `hidden-n-plus-one`, `over-eager-contain`
   - Abstraction misuse: `premature-service-layer`, `repository-over-table`, `framework-replacement-abstraction`, `unnecessary-trait`
   - Duplication: `duplicate-finders`, `duplicate-query-semantics`, `duplicate-validation`, `duplicate-application-rules`
   - God objects: `god-behavior`, `god-component`, `god-listener`
   - Lifecycle opacity: `callback-side-effect-explosion`, `event-chain-obscurity`
   - Security exposure: `mass-assignment-overexposure`, `serialization-overexposure`, `authorization-only-in-ui`
   - Plugin honesty: `plugin-api-reimplementation`
3. For each candidate, apply the smell’s **Detection guidance** and **False positives**.
4. Rank by risk (security/correctness first, then maintainability/performance).
5. Recommend preferred refactoring and note when no refactor is warranted.
6. Hand off ownership questions to `review-abstraction-choice` / `choose-cakephp-abstraction`.

## Framework decisions

- Pair smells with decision units when the fix is an ownership choice (`knowledge/decisions/*`).
- Prefer CakePHP extension points over framework-replacement layers.
- Respect project conventions in `.ai/` when they explicitly document an exception.

## Anti-patterns

- Scoring “smelliness” by line count alone.
- Demanding repositories/services as the default cleanup.
- Flagging plugin APIs as smells when the plugin is installed and used correctly.
- Reporting smells without file/evidence references.

## Validation

- Each finding cites evidence and a catalog smell id.
- False positives considered; severity justified.
- Remediation matches CakePHP ownership norms and Composer reality.

## Completion criteria

- Written smell report with prioritized findings, preferred refactors, and explicit non-issues.
- Clear next skills for fixes (`create-finder`, `add-application-rule`, `review-abstraction-choice`, etc.).
