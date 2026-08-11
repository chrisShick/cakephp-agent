---
name: create-event-listener
description: Add a CakePHP event listener for cross-cutting side effects without burying them in unrelated layers.
---

# Create event listener

## Objective

Implement event-driven side effects (notifications, denormalized updates, integrations) via CakePHP events/listeners when that matches project architecture — without using listeners as a dumping ground for core invariants.

## Use when

- A side effect should react to Table/controller/app events across multiple call sites.
- Decoupling secondary work from the primary save path after success.

## Do not use when

- The logic is a pass/fail persistence invariant — use `add-application-rule`.
- A simple Table callback is clearer and project convention prefers callbacks for that case.
- Ownership is unclear — use `choose-cakephp-abstraction` / `select-lifecycle-hook` first.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Locate existing listeners, `EventManager` usage, and where events are dispatched (`Model.afterSave`, custom events, etc.).
3. Read `.ai/` for listener vs callback conventions.
4. Identify failure/retry expectations (must the primary save fail if the listener fails?).

## Workflow

1. Confirm an event already exists or define a clear custom event name in the owning layer.
2. Prefer listening to the correct lifecycle (e.g. after successful commit when durability matters).
3. Implement a dedicated listener class consistent with project location/naming.
4. Keep listener focused; do not re-implement authorization or primary invariants.
5. Register the listener the way this app already does (Application bootstrap, Table `implementedEvents`, etc.).
6. Test by triggering the event path and asserting the side effect.

## Framework decisions

- `knowledge/decisions/table-callback-vs-application-rule` (invariants vs side effects)
- Prefer application rules for invariants; listeners/callbacks for side effects.
- Do not assume plugin event naming (e.g. CRUD) unless that package is installed.

## Anti-patterns

- Event-chain obscurity (deep cascading listeners hard to trace).
- Encoding uniqueness in a listener.
- HTTP response shaping inside model listeners.
- Inventing global event buses foreign to CakePHP’s Event system.

## Validation

- Side effect runs on the intended event and does not run on failed saves when that is required.
- Registration is discoverable; tests cover the happy path.

## Completion criteria

- Listener implemented, registered, tested, and documented briefly where the project expects it.
