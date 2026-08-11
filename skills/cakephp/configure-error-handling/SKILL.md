---
name: configure-error-handling
description: Align CakePHP exception rendering and expected HTTP failure mapping without silent catch-alls.
---

# Configure error handling

## Objective

Ensure unexpected exceptions flow through CakePHP error middleware/renderers, while expected domain failures map to clear HTTP outcomes consistent with the app.

## Use when

- Standardizing API/HTML error shapes.
- Cleaning up broad try/catch blocks that hide failures.
- Adding custom exception renderer behavior the CakePHP way.

## Do not use when

- The issue is a specific validation/rule failure — fix the Table layer.
- You only need a one-off not-found — a local `NotFoundException` may suffice.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect `Application` error middleware, existing renderers, and API `viewClasses`.
3. Note logging configuration and production debug flag behavior.

## Workflow

1. Prefer framework rendering for unexpected errors.
2. Map expected failures explicitly (404/403/422) without swallowing `\Throwable`.
3. Keep client payloads free of secrets/stack traces in production.
4. Log server-side detail where the project already logs.
5. Add a test for one expected and one unexpected path if the project tests errors.

## Framework decisions

- `knowledge/decisions/exception-renderer-vs-controller-catch`

## Anti-patterns

- Empty catch-alls returning 200.
- Duplicating ad-hoc error JSON in every action when a renderer exists.

## Validation

- Unexpected errors render via framework path; expected failures keep stable shapes.

## Completion criteria

- Error path documented/implemented; no new silent catches; tests or manual check done.
