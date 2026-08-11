---
id: behavior-vs-service
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/orm/behaviors.html
last_verified: 2026-08-10
related: [finder-vs-behavior, finder-vs-service]
evaluations: [cross-table-capability-prefers-behavior, reject-service-for-timestamp-style-concern]
---

# Behavior vs service

## Use cases

- Reusable Table capabilities (callbacks, finders, field management) shared across models.
- Application workflows that orchestrate domain steps outside the ORM lifecycle.

## Decision questions

1. Should the capability attach to Tables and participate in save/find/delete lifecycle?
2. Is it reusable across many Tables with configuration?
3. Is it a one-off application workflow rather than a Table mixin?

## Recommended outcome

- **Behavior** for cross-cutting Table capabilities (Timestamp, Tree, soft-delete, counter-cache style features).
- **Application service** for multi-step use cases that should not live inside ORM callbacks.

## Rejected alternatives

- A free-floating service that mutates entities during save when a Behavior is the CakePHP extension point.
- God services that reimplement Behavior APIs for every Table manually.

## Exceptions

- Installed plugins may already provide the Behavior — prefer the plugin over a custom service.
- Very app-specific one-Table logic may stay on the Table or a finder instead of a Behavior.

## Examples

Auto-set `created`/`modified` → Behavior. “Approve membership then notify reviewers” → application service calling Tables/mailer.

## Evaluations

- `cross-table-capability-prefers-behavior`
- `reject-service-for-timestamp-style-concern`
