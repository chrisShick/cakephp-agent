---
name: add-transaction
description: Wrap multi-write CakePHP persistence in a connection transaction when atomicity is required.
---

# Add transaction

## Objective

Ensure multiple related writes commit or roll back together using CakePHP connection transactions, without stuffing best-effort side effects into the same atomic unit.

## Use when

- Creating/updating parent + children or other consistency pairs.
- A partial failure would leave invalid business state.

## Do not use when

- Writes are intentionally independent — see `transaction-vs-independent-save`.
- The “failure” is a remote side effect that should not undo the primary save.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. If ownership of the writes is unclear, run `choose-cakephp-abstraction`.
3. Identify the connection (`$table->getConnection()`) and existing transactional patterns.
4. Separate after-commit side effects (mail, webhooks, search index).

## Workflow

1. Confirm the writes are one consistency unit.
2. Wrap them in `Connection::transactional()` (or project-equivalent atomic save).
3. Keep the closure free of remote IO when possible.
4. Let application rules/validation failures abort the transaction naturally.
5. Schedule non-critical side effects after successful commit.
6. Add Table/integration tests that assert rollback on mid-unit failure.

## Framework decisions

- `knowledge/decisions/transaction-vs-independent-save` (primary)
- Related: `bulk-update-vs-entity-save` when choosing save style inside the transaction

## Anti-patterns

- Independent saves for an atomic pair.
- Bundling emails/HTTP calls inside the DB transaction by default.
- Using transactions instead of application rules for invariants.

## Validation

- Forced failure after the first write leaves no partial consistency unit.
- Success path persists all members of the unit.

## Completion criteria

- Transaction boundary documented in code, tested for rollback, side effects placed correctly.
