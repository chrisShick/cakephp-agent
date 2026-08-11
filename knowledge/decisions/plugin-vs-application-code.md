---
id: plugin-vs-application-code
type: decision
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/plugins.html
last_verified: 2026-08-10
related: []
evaluations: [plugin-only-when-installed, no-plugin-api-reimplementation]
---

# Plugin vs application code

## Use cases

- Sharing reusable CakePHP features across apps.
- Implementing domain behavior used by one application.

## Decision questions

1. Is this reused across applications or distributable?
2. Does an installed plugin already own this concern?
3. Would extracting a plugin obscure a simple app-local feature?

## Recommended outcome

- Keep app-specific domain logic in the application.
- Use/enable a plugin when the project intentionally adopts it (Composer + load).
- Extract a plugin when there is a clear reuse/distribution boundary.

## Rejected alternatives

- Reimplementing an installed plugin’s API in app code.
- Recommending plugin APIs that are not in `composer.lock` / loaded plugins.
- Creating a plugin for a one-off controller action.

## Exceptions

- Thin app wrappers around plugins for project conventions are fine.
- Package-maintainer mode (future) uses different criteria.

## Examples

If `friendsofcake/crud` is installed, prefer CRUD configuration/listeners over reinventing CRUD actions. If it is not installed, do not invent CRUD APIs.

## Evaluations

- `plugin-only-when-installed`
- `no-plugin-api-reimplementation`