---
id: crud-listener-vs-controller-action
type: decision
scope: plugins
framework: cakephp
package: friendsofcake/crud
framework_versions: [">=5.0 <6.0"]
package_versions: [">=7.0 <8.0"]
priority: high
truth_level: PLUGIN_SEMANTIC
sources:
  - https://crud.readthedocs.io/en/latest/actions.html
  - https://crud.readthedocs.io/en/latest/events.html
last_verified: 2026-08-11
related: [crud-config-vs-listener, crud-listener-vs-orm-callback]
evaluations: [prefer-crud-listener-over-manual-action-fork, reject-reimplemented-crud-action]
---

# CRUD listener vs controller action

## Use cases

- Customizing an enabled CRUD action’s lifecycle.
- Deciding whether to abandon CRUD for a hand-written action.

## Decision questions

1. Is the action still fundamentally index/view/add/edit/delete (or an enabled CRUD action)?
2. Can config + listeners keep the CRUD execute flow?
3. Is the flow so different that CRUD no longer owns it?

## Recommended outcome

- **Stay on CRUD** with config/listeners when the action shape remains CRUD’s.
- **Hand-written controller action** only when the flow is no longer CRUD (different resource lifecycle, non-CRUD orchestration).

## Rejected alternatives

- Reimplementing index/add/edit boilerplate beside an enabled CRUD action.
- Disabling CRUD and rewriting the action for a small query tweak.

## Exceptions

- Legacy controllers mid-migration may temporarily mix — prefer `migrate-controller-to-crud` directionally.
- Truly custom endpoints (reports, wizards) should not pretend to be CRUD.

## Examples

Scoped index query → `Crud.beforePaginate` listener. Multi-step checkout wizard → normal controller action, not fake CRUD.

## Evaluations

- `prefer-crud-listener-over-manual-action-fork`
- `reject-reimplemented-crud-action`
