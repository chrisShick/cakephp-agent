---
name: write-table-test
description: Write CakePHP Table-layer PHPUnit tests for validation, rules, finders, and persistence behavior.
---

# Write table test

## Objective

Add deterministic Table tests that cover marshaling/validation, application rules, finders, and save/delete behavior through Table APIs — not only controller redirects.

## Use when

- Changing Table validation, `buildRules`, finders, Behaviors, or save paths.
- A bug is rooted in persistence invariants rather than HTTP wiring.

## Do not use when

- The change is purely HTTP routing/response shaping — prefer an integration/controller test.
- The project already mandates a different Table test harness; follow `.ai/` / existing tests first.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect existing tests under `tests/TestCase/Model/Table` (or project equivalent).
3. Note fixture / factory strategy already in use.
4. Identify the behaviors under test (validation vs rules vs finder).

## Workflow

1. Choose Table test case base classes matching the project.
2. Load fixtures/factories deterministically; avoid production network services.
3. Assert invalid shapes produce validation errors; stateful invariants fail via rules on save.
4. Assert finders return expected IDs/conditions with minimal contain.
5. For transactional multi-writes, assert rollback behavior when relevant (`add-transaction`).
6. Keep tests focused — do not re-test the entire HTTP stack here.

## Framework decisions

- Pair with `add-validation`, `add-application-rule`, `create-finder`, `create-behavior` as the code under test.
- Respect `rules/cakephp/testing.mdc` boundary choices.

## Anti-patterns

- Only asserting controller flash/redirect while skipping Table rule failures.
- Live external HTTP/DB services without isolation.
- Inventing Pest/Laravel feature-test defaults in a PHPUnit CakePHP app.

## Validation

- Tests fail when the invariant is broken and pass when fixed.
- Fixtures/factories remain deterministic in CI.

## Completion criteria

- Table tests added/updated for the changed persistence behavior and green in the project’s test runner.
