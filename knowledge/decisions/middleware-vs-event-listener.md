---
id: middleware-vs-event-listener
type: decision
scope: http
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/controllers/middleware.html
  - https://book.cakephp.org/5.x/core-libraries/events.html
last_verified: 2026-08-10
related: [component-vs-middleware, event-vs-direct-call]
evaluations: [request-pipeline-prefers-middleware, reject-listener-for-http-auth-gate]
---

# Middleware vs event listener

## Use cases

- Cross-cutting HTTP request/response pipeline concerns.
- Application/domain reactions to framework or model events.

## Decision questions

1. Is the concern about the PSR-7 request/response pipeline?
2. Must it run for many routes before controllers?
3. Is it reacting to a domain/ORM/application event rather than HTTP?

## Recommended outcome

- **Middleware** for HTTP pipeline concerns (auth gate at request boundary, HTTPS force, request timing, body parsing integration).
- **Event listener** for non-HTTP or lifecycle reactions (ORM afterSave, custom app events).

## Rejected alternatives

- Using a global listener to approximate request gating that belongs in middleware.
- Stuffing domain afterSave reactions into HTTP middleware.

## Exceptions

- Authentication/Authorization plugins define their own middleware — use those APIs when installed.
- Some CakePHP internals still expose events around requests; prefer documented plugin/framework middleware when both exist.

## Examples

Require identity for `/admin/*` → middleware. On `Model.afterSave` for Orders, update a read model → listener.

## Evaluations

- `request-pipeline-prefers-middleware`
- `reject-listener-for-http-auth-gate`
