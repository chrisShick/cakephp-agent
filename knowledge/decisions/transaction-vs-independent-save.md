---
id: transaction-vs-independent-save
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/orm/database-basics.html#using-transactions
last_verified: 2026-08-10
related: [bulk-update-vs-entity-save, table-callback-vs-application-rule, command-vs-controller-action]
evaluations: [multi-write-consistency-prefers-transaction, reject-independent-saves-for-atomic-pair]
---

# Transaction vs independent save

## Use cases

- Multiple writes that must commit or roll back together.
- Independent writes where partial success is acceptable.

## Decision questions

1. Would a partial failure leave the system in an invalid business state?
2. Do the writes share a consistency boundary (order + line items, user + profile)?
3. Is the second write merely a best-effort side effect?

## Recommended outcome

- **Transaction** (`Connection::transactional()` / atomic save options) when writes are one consistency unit.
- **Independent saves** when each write is intentionally separable and partial success is OK.

## Rejected alternatives

- Saving related records in sequence without a transaction when atomicity is required.
- Wrapping unrelated best-effort side effects in the same transaction, forcing rollbacks on non-critical failures.

## Exceptions

- Some side effects (email, search index) belong after commit — use after-commit hooks/jobs, not the DB transaction.
- Cross-database or external systems need outbox/saga patterns beyond a single SQL transaction.

## Examples

Create invoice + line items → one transaction. Persist article then enqueue “notify subscribers” job → independent after successful commit.

## Evaluations

- `multi-write-consistency-prefers-transaction`
- `reject-independent-saves-for-atomic-pair`
