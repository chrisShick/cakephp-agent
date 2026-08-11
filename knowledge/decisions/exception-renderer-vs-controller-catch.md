---
id: exception-renderer-vs-controller-catch
type: decision
scope: http
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/development/errors.html
last_verified: 2026-08-10
related: [route-config-vs-controller-url-logic]
evaluations: [unexpected-error-prefers-framework-renderer, reject-silent-catchall-returning-200]
---

# Exception renderer vs controller catch

## Use cases

- Unexpected runtime failures.
- Expected domain outcomes (not found, forbidden, validation).

## Decision questions

1. Is the failure unexpected infrastructure/domain crash?
2. Is it an expected branch the API must represent stably?
3. Would catching hide logging/monitoring?

## Recommended outcome

- **Framework exception renderer / error middleware** for unexpected errors.
- **Explicit** controller/domain mapping for expected HTTP outcomes (throw `NotFoundException`, return validation errors, etc.).

## Rejected alternatives

- Broad `catch (\Throwable)` that returns HTTP 200 with an error string.
- Per-action bespoke JSON error envelopes that diverge from the renderer.

## Exceptions

- Localized catch to translate a known library exception into a domain exception is fine if rethrown/logged.

## Examples

DB down → error middleware/renderer. Missing article id → `NotFoundException`.

## Evaluations

- `unexpected-error-prefers-framework-renderer`
- `reject-silent-catchall-returning-200`
