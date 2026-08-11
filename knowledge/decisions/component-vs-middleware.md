---
id: component-vs-middleware
type: decision
scope: http
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/controllers.html
  - https://book.cakephp.org/5.x/controllers/middleware.html
last_verified: 2026-08-10
related: [route-config-vs-controller-url-logic, command-vs-controller-action]
evaluations: [auth-middleware-not-component-default, reusable-controller-helper-prefers-component, reject-middleware-for-controller-only-flash-helper]
---

# Component vs middleware

## Use cases

- Cross-cutting HTTP concerns before/around controllers.
- Reusable controller-layer helpers tightly coupled to controller callbacks.

## Decision questions

1. Does it need to run for many requests regardless of controller?
2. Is it about transforming the request/response pipeline?
3. Is it a reusable controller collaboration that uses controller callbacks?

## Recommended outcome

- **Middleware** for PSR-style request/response pipeline concerns (auth gate placement, HTTPS redirects, rate limits, request mutation).
- **Components** for reusable controller-level behavior and callback integration.

## Rejected alternatives

- Using middleware as a dumping ground for domain/persistence logic.
- Using components for global request pipeline work that belongs earlier.

## Exceptions

- Installed plugins may prescribe one or the other — follow plugin docs when that pack is enabled.
- Legacy apps may already standardize on components; honor project conventions.

## Examples

Authentication identity loading typically belongs in middleware; a pagination helper used by several controllers may be a component.

## Evaluations

- `auth-middleware-not-component-default`
- `reusable-controller-helper-prefers-component`
- `reject-middleware-for-controller-only-flash-helper`
