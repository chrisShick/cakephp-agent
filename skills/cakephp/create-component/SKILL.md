---
name: create-component
description: Create a CakePHP Component for reusable controller-layer collaboration (not middleware or persistence).
---

# Create component

## Objective

Add a Component that shares controller-layer helpers and callbacks across controllers without owning persistence or replacing middleware.

## Use when

- Several controllers need the same HTTP orchestration helper tightly coupled to controller callbacks.
- Extracting repeated flash/redirect/loadModel-style collaboration (project-dependent).

## Do not use when

- The concern is request/response pipeline-global — use middleware (`component-vs-middleware`).
- The concern is persistence/query — use Table/finder/behavior skills.
- Ownership is unclear — use `choose-cakephp-abstraction`.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect existing Components and how controllers `loadComponent`.
3. Confirm the work is controller-scoped, not Application middleware.
4. Check Auth plugin conventions — do not invent plugin APIs.

## Workflow

1. Confirm Component beats middleware for this reuse.
2. Create the Component with a narrow API and needed callbacks.
3. Load it from controllers that need it; avoid AppController god-loading unrelated Components.
4. Keep persistence on Tables; Component only orchestrates.
5. Add controller/integration tests for the shared behavior.

## Framework decisions

- `knowledge/decisions/component-vs-middleware`
- Smell awareness: `god-component`

## Anti-patterns

- God components.
- Persistence inside Components.
- Using Components for global auth gates that belong in middleware.

## Validation

- Controllers share the helper without duplicated code.
- No Table ownership leaked into the Component.

## Completion criteria

- Component created, loaded where needed, tested, and documented if non-obvious.
