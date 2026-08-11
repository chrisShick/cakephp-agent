---
name: review-crud-controller
description: Review FriendsOfCake CRUD usage for config/event/ORM boundary correctness.
---

# Review CRUD controller

## Objective

Review CRUD controllers/listeners for correct ownership, event choice, and non-contamination of ORM concerns.

## Use when

- PR review of CRUD-related changes.
- Auditing listeners after feature work.

## Do not use when

- CRUD is not part of the change and package is absent.

## Inputs to discover

1. Follow **`inspect-before-coding`** — **discover before prescribing edits**.
2. Composer CRUD version; `.ai/` listener conventions; neighboring patterns.
3. Diff of controllers, listeners, and Table rules/validation.

## Workflow

1. Confirm package presence/major compatibility (^7).
2. Check config-before-listener discipline.
3. Check CRUD vs ORM event ownership.
4. Check listener size, mirroring/registration, and tests.
5. Check CRUD smells: `god-crud-listener`, `orm-invariant-in-crud-listener`, `reimplemented-crud-action`.
6. For security surfaces, run or hand off to `review-crud-security`.
7. Report findings with severity and remediations (`modify-crud-action`, `create-crud-listener`, `review-crud-security`, core rule skills).

## Framework decisions

- `crud-listener-vs-orm-callback`, `crud-config-vs-listener`, `crud-listener-vs-controller-action`
- Smells: `god-crud-listener`, `orm-invariant-in-crud-listener`, `reimplemented-crud-action`
- Core `cakephp-code-review` / `review-cakephp-security` for non-CRUD issues

## Anti-patterns

- Reviewing without opening listener registration
- Demanding Laravel-like layers
- Requiring Search/CRUD integration APIs when Search is absent

## Validation

- Each finding cites evidence and an installable recommendation.

## Completion criteria

- Written review with prioritized CakePHP/CRUD-native fixes.
