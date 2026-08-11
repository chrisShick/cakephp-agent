---
id: route-config-vs-controller-url-logic
type: decision
scope: http
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/development/routing.html
last_verified: 2026-08-10
related: [component-vs-middleware, command-vs-controller-action]
evaluations: [http-endpoint-prefers-route-config, reject-persistence-inside-route-closure]
---

# Route config vs controller URL logic

## Use cases

- Mapping URLs to controller actions.
- Deciding whether dispatch logic belongs in `config/routes.php` or elsewhere.

## Decision questions

1. Is the concern “which URL reaches which action”?
2. Does the mapping need scopes, prefixes, resources, or named routes?
3. Is someone tempted to put DB/auth/domain work inside a route callable?

## Recommended outcome

- **Route config** (`config/routes.php` / plugin routes) for URL → controller/action mapping.
- Controllers orchestrate HTTP after routing; they do not rebuild the route table ad hoc.

## Rejected alternatives

- Embedding persistence, validation, or authorization inside route closures.
- Inventing Laravel `Route::` facades or Artisan route commands in CakePHP apps.

## Exceptions

- Tiny fallback routes that only `connect`/`fallbacks` are fine.
- Plugin packs may ship their own route files — load them the CakePHP way when installed.

## Examples

`/articles/{id}` → `ArticlesController::view` belongs in routes. Saving the article belongs in the Table via the controller action — never in the route file.

## Evaluations

- `http-endpoint-prefers-route-config`
- `reject-persistence-inside-route-closure`
