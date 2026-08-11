---
name: select-lifecycle-hook
description: Choose the correct CakePHP lifecycle hook (middleware, validation, rules, callbacks, events) from concern ownership — not from similar event names.
---

# Select lifecycle hook

## Objective

Pick where in the CakePHP request/persistence lifecycle a concern should run, based on ownership and guarantees — not because another hook “sounds similar.”

## Use when

- Deciding among middleware, component callbacks, validation, application rules, Table callbacks, and event listeners.
- A side effect or check was placed in the wrong phase and needs relocation.
- Lightweight companion to `choose-cakephp-abstraction` focused on *when* code runs.

## Do not use when

- The abstraction and hook are already known and you only need implementation (`add-validation`, `add-application-rule`, `create-event-listener`, …).
- Plugin-specific lifecycles (e.g. CRUD listener events) are involved — only if that plugin is installed; otherwise stay on core CakePHP hooks.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Name the concern and the guarantee required (must block save? must see committed data? must run for every HTTP request?).
3. Inventory existing hooks in the same area to match project style.

## Workflow

1. Classify the concern:
   - Pipeline-wide HTTP → middleware (often)
   - Controller collaboration → component
   - Shape/format on marshal → validation
   - Stateful invariant on save/delete → application rule
   - Persistence side effect → Table callback and/or event listener
   - Query semantics → finder (not a “hook,” but often the right place)
2. Prefer the earliest *correct* phase that provides the needed guarantee — do not run persistence rules in middleware.
3. Reject hooks that only match by name (e.g. “before*” everywhere).
4. Document: selected hook, guarantee, rejected hooks.
5. Hand off to the implementation skill.

## Framework decisions

- `knowledge/decisions/validation-vs-application-rule`
- `knowledge/decisions/table-callback-vs-application-rule`
- `knowledge/decisions/component-vs-middleware`
- Pair with `choose-cakephp-abstraction` when the object type is also unclear.

## Anti-patterns

- Matching event names instead of ownership.
- Uniqueness in `beforeSave` instead of RulesChecker when rules fit.
- Domain work in middleware.
- Assuming CRUD/plugin lifecycle events in core skills.

## Validation

- Selected hook provides the required guarantee (blocking vs after-success, HTTP vs all entry points).
- Rejected alternatives listed with reasons.

## Completion criteria

- Hook choice recorded; next implementation skill identified.
