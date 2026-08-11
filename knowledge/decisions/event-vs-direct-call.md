---
id: event-vs-direct-call
type: decision
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/core-libraries/events.html
last_verified: 2026-08-10
related: [table-callback-vs-application-rule, plugin-vs-application-code]
evaluations: [decoupled-side-effect-prefers-event, reject-event-for-required-local-invariant]
---

# Event vs direct call

## Use cases

- Side effects that should not tightly couple the caller to every listener.
- Required next steps that are part of the same use case’s success path.

## Decision questions

1. Is the follow-up optional / multi-subscriber / cross-module?
2. Must the follow-up succeed for the primary operation to be considered complete?
3. Will an event chain hide critical control flow from readers and tests?

## Recommended outcome

- **Direct call** for required, local, synchronous steps that belong to the same use case.
- **Event / listener** for decoupled side effects (notifications, projections, plugin hooks) where subscribers may vary.

## Rejected alternatives

- Firing events for every internal method call “for flexibility.”
- Hiding must-succeed business steps only in listeners with no obvious owner.

## Exceptions

- CakePHP/plugin lifecycle events are the framework’s extension API — use them when extending core/plugin behavior.
- Async queue jobs may be triggered from listeners; still keep the decision to enqueue explicit.

## Examples

After save, send optional newsletter → event/listener. Inside checkout, decrement stock that must succeed with the order → direct call in the owning use case/Table transaction.

## Evaluations

- `decoupled-side-effect-prefers-event`
- `reject-event-for-required-local-invariant`
