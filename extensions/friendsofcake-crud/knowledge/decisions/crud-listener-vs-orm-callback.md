---
id: crud-listener-vs-orm-callback
type: decision
scope: plugins
framework: cakephp
package: friendsofcake/crud
framework_versions: [">=5.0 <6.0"]
package_versions: [">=7.0 <8.0"]
priority: critical
truth_level: PLUGIN_SEMANTIC
sources:
  - https://crud.readthedocs.io/en/latest/events.html
  - https://book.cakephp.org/5.x/orm/table-objects.html
last_verified: 2026-08-10
related: [validation-vs-application-rule, table-callback-vs-application-rule]
evaluations: [crud-event-not-orm-callback, crud-response-uses-crud-listener]
---

# CRUD listener vs ORM callback

## Use cases

- Customizing FriendsOfCake CRUD action flow (query, find, save outcome, render/redirect, flash).
- Enforcing persistence invariants or normalization for **all** save paths, including non-CRUD code.

## Decision questions

1. Does this concern only apply when a CRUD action runs?
2. Is it about HTTP/API response, flash, redirect, or CRUD subject/query for that action?
3. Must the same invariant hold for CLI, jobs, and non-CRUD controllers?
4. Is it field format (validation) or stateful uniqueness/existence (application rule)?

## Recommended outcome

- **CRUD listener / `Crud.*` event:** CRUD-action lifecycle concerns — adjust find/paginate query for the action, shape response metadata, redirects, flash, related models for that request.
- **Table callback / ORM event:** persistence-side effects and normalizations that must apply whenever the Table saves/deletes.
- **Application rule:** stateful pass/fail invariants on save/delete.
- **Validator:** request/data shape and format on marshal.

Similarly named events are **not** interchangeable (e.g. `Crud.beforeSave` vs Table `Model.beforeSave`).

## Rejected alternatives

- Putting uniqueness only in a CRUD listener — bypassed by non-CRUD saves.
- Using Table callbacks to set flash messages or API response envelopes.
- Recreating full CRUD action boilerplate in the controller instead of config/events.

## Exceptions

- Project `.ai/` may prescribe listener location/registration; honor it.
- Thin controller `Crud->on(...)` closures are OK for tiny one-off hooks; prefer dedicated listeners for reusable/substantial behavior.

## Examples

- Ensure slug uniqueness for Articles → `buildRules` / `isUnique`, not only `Crud.beforeSave`.
- Add JSON meta on successful CRUD edit → CRUD listener on `afterSave` / `beforeRender` as appropriate.
- Change index query filters for a CRUD index action → `Crud.beforePaginate` / configured finder.

## Evaluations

- `crud-event-not-orm-callback`
- `crud-response-uses-crud-listener`
