---
name: review-abstraction-choice
description: Review whether an existing or proposed CakePHP abstraction choice is correct — decision units, rejected alternatives, and project conventions.
---

# Review abstraction choice

## Objective

Critique a proposed or existing ownership placement (controller, middleware, component, table, entity, finder, behavior, validator, rules, event/listener, command, service) against CakePHP decision units and project conventions.

## Use when

- Someone already picked an abstraction and you need to verify it.
- A PR introduces a service/repository/helper and ownership is unclear.
- After `detect-architectural-smells` finds a misplaced concern.

## Do not use when

- You are still discovering the project (start with `inspect-before-coding`).
- You need a greenfield ownership decision without an existing proposal (use `choose-cakephp-abstraction`).

## Inputs to discover

1. Run **`inspect-before-coding`** first.
2. Name the concern in one sentence and the chosen abstraction.
3. Locate neighboring patterns and `.ai/` architecture notes.
4. Confirm Composer plugins before accepting plugin-specific placements.
5. Identify the matching decision unit(s) under `knowledge/decisions/`.

## Workflow

1. Complete discovery via `inspect-before-coding`.
2. Restate the concern, lifecycle, and chosen abstraction.
3. Run the ownership questions from `choose-cakephp-abstraction` against the proposal.
4. Compare to relevant decision units (non-exhaustive):
   - `validation-vs-application-rule`
   - `table-callback-vs-application-rule`
   - `finder-vs-behavior` / `finder-vs-service` / `behavior-vs-service`
   - `component-vs-middleware` / `middleware-vs-event-listener`
   - `entity-accessor-vs-query-calculation`
   - `contain-vs-matching` / `contain-vs-join`
   - `bulk-update-vs-entity-save` / `transaction-vs-independent-save`
   - `event-vs-direct-call`
   - `plugin-vs-application-code`
5. Accept, adjust, or reject the choice with explicit rejected alternatives.
6. If rejected, name the preferred abstraction and the implementation skill to use next.
7. Note project-convention exceptions only when evidenced in `.ai/` or clear local patterns.

## Framework decisions

- Decision units own the rationale; this skill applies them to a concrete proposal.
- Plugin-capable placements require the plugin to be installed — no phantom APIs.
- Prefer thin HTTP layers and Table/rules/finders for persistence and query semantics.

## Anti-patterns

- Rubber-stamping services/repositories as “clean architecture.”
- Ignoring existing project conventions documented in `.ai/`.
- Reviewing abstraction choice without reading neighboring code.
- Conflating stylistic preference with CakePHP ownership errors.

## Validation

- Verdict includes: accept/adjust/reject, preferred abstraction, decision unit id(s), rejected alternatives.
- Recommendation is installable given Composer state.

## Completion criteria

- Written verdict ready for implementation or PR feedback.
- Next skill identified when a change is required.
