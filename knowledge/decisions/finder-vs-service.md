---
id: finder-vs-service
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/orm/retrieving-data-and-resultsets.html
last_verified: 2026-08-10
related: [finder-vs-behavior, behavior-vs-service]
evaluations: [query-semantics-prefer-finder-not-service, reject-service-wrapper-for-single-finder]
---

# Finder vs service

## Use cases

- Reusable query semantics for a Table.
- Multi-step orchestration that coordinates several Tables, IO, or non-ORM work.

## Decision questions

1. Is the concern primarily composing a query on one Table?
2. Does it need HTTP, mail, queue, or multi-aggregate orchestration beyond one model?
3. Would a “service” exist only to call a single finder/save?

## Recommended outcome

- **Custom finder** for reusable query semantics (`findPublished`, `findForUser`).
- **Application service / use-case class** only when coordinating multiple models or side effects that do not belong on a Table.

## Rejected alternatives

- Introducing a service that only wraps `$table->find(...)` with no orchestration value.
- Putting shared query clauses in controllers or ad-hoc helpers instead of finders.

## Exceptions

- Project `.ai/` may standardize thin application services for all use cases — treat as project convention, not CakePHP default.
- Complex reporting across many Tables may use a dedicated query/report object without calling it a general “repository.”

## Examples

Published articles list filters → `ArticlesTable::findPublished()`. Checkout that reserves inventory, charges payment, and emails → application service coordinating Tables + adapters.

## Evaluations

- `query-semantics-prefer-finder-not-service`
- `reject-service-wrapper-for-single-finder`
