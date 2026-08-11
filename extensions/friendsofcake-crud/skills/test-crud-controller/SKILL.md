---
name: test-crud-controller
description: Add or improve tests for FriendsOfCake CRUD controllers and listeners.
---

# Test CRUD controller

## Objective

Cover CRUD HTTP actions and listener behavior with tests that match project style, plus Table-level coverage for invariants.

## Use when

- Adding CRUD features or listeners without tests.
- Hardening migration from hand-rolled controllers.

## Do not use when

- CRUD is not installed.
- The change is purely Table-side (use core testing guidance / Table tests).

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Existing CRUD test cases, fixtures, and helpers.
3. Behaviors claimed by config/listeners.

## Workflow

1. Prefer integration/controller tests hitting real actions.
2. Assert status, flash/redirect/JSON shape as applicable.
3. Cover validation and application-rule failures.
4. Add focused listener tests only when valuable; still verify registration via an action test.
5. Keep persistence invariant assertions at Table level too.

## Framework decisions

- `rules/testing.mdc` in this extension
- Invariants remain Table-owned

## Anti-patterns

- Only unit-testing listener methods while config is wrong
- Skipping failure paths

## Validation

- Tests fail on the bug class they guard; green on correct behavior.

## Completion criteria

- Meaningful CRUD coverage added for the changed actions/listeners.
