---
name: cakephp-code-review
description: Review CakePHP changes for ownership boundaries, ORM usage, conventions, and unsafe assumptions — discover before prescribing.
---

# CakePHP code review

## Objective

Review a diff or area for CakePHP-native quality: correct abstraction ownership, ORM/validation/rules usage, plugin honesty, and project convention alignment.

## Use when

- Reviewing a PR or agent-produced CakePHP changes.
- Auditing a module after a feature lands.
- Teaching why a change violates CakePHP ownership norms.

## Do not use when

- You need to implement a fix immediately (switch to the matching task skill after notes).
- The review is purely stylistic PHP with no CakePHP semantics.

## Inputs to discover

1. Follow **`inspect-before-coding`** on the touched areas — **do not review in a vacuum**.
2. Read `.ai/` and neighboring patterns for local conventions.
3. Confirm Composer packages before judging plugin usage.
4. Identify related decision units and anti-patterns for the change type.

## Workflow

1. Discover context (`inspect-before-coding`); refuse to invent project conventions.
2. Check ownership: controllers thin? invariants in rules? queries in finders?
3. Check ORM: contain/matching, N+1 risk, bulk vs entity save, mass assignment.
4. Check validation vs application rules boundary.
5. Check plugin/API honesty — no reimplementation or phantom packages.
6. Check security-sensitive surfaces lightly (exposure, IDOR patterns as evidenced) without assuming Auth plugins.
7. For structural issues, use `detect-architectural-smells`; for ownership disputes, use `review-abstraction-choice`.
8. Produce findings: severity, file references, preferred CakePHP fix, rejected alternatives.
9. Suggest follow-up skills for fixes (`add-application-rule`, `create-finder`, etc.).

## Framework decisions

- Use `choose-cakephp-abstraction` reasoning and linked `knowledge/decisions/*`.
- Anti-patterns under `knowledge/anti-patterns/` as a checklist, not a scoreboard.

## Anti-patterns

- Reviewing without opening surrounding project code.
- Demanding Laravel-like layers as “best practice.”
- Requiring optional plugins.
- Nitpicking style while missing ownership bugs.

## Validation

- Each finding cites evidence (code + convention/decision).
- Recommendations are installable given Composer state.

## Completion criteria

- Written review with prioritized findings and concrete CakePHP-native remediations.
