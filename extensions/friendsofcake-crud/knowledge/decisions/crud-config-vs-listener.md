---
id: crud-config-vs-listener
type: decision
scope: plugins
framework: cakephp
package: friendsofcake/crud
framework_versions: [">=5.0 <6.0"]
package_versions: [">=7.0 <8.0"]
priority: high
truth_level: PLUGIN_SEMANTIC
sources:
  - https://crud.readthedocs.io/en/latest/
last_verified: 2026-08-11
related: [crud-listener-vs-orm-callback, crud-listener-vs-controller-action]
evaluations: [crud-config-before-listener, reject-listener-when-crud-config-suffices]
---

# CRUD config vs listener

## Use cases

- Changing which actions run, finders, related models, or simple redirects/flash via CRUD config.
- Customizing lifecycle when config cannot express the behavior.

## Decision questions

1. Can `Crud` component/action config already express it?
2. Is it a one-line redirect/finder/relatedModels tweak?
3. Does it need multi-step logic across events?

## Recommended outcome

- **CRUD configuration** first for actions enabled, finders, serialize, related models, simple messages.
- **CRUD listener** when behavior needs event-time logic config cannot express cleanly.

## Rejected alternatives

- Writing a listener that only sets what config already supports.
- Forking the whole action into a manual controller method for a config-sized change.

## Exceptions

- Project `.ai/` may prefer listeners for all customization — honor it and document why.
- Tiny `Crud->on()` closures are OK for throwaway hooks.

## Examples

Enable only index/view → config. Add JSON meta after save → listener.

## Evaluations

- `crud-config-before-listener`
- `reject-listener-when-crud-config-suffices`
