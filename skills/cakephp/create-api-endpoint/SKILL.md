---
name: create-api-endpoint
description: Add or refine a CakePHP API/JSON endpoint with correct HTTP semantics and thin controller orchestration.
---

# Create API endpoint

## Objective

Deliver an HTTP API endpoint (typically JSON) using CakePHP controllers/routing while keeping persistence and query ownership on Tables and related layers.

## Use when

- Adding REST-ish or RPC-style JSON endpoints in a CakePHP app.
- Aligning status codes, serialization, and error shapes with project API conventions.

## Do not use when

- The endpoint is a classic HTML form/action — use `create-controller-action`.
- Plugin-specific API layers (e.g. CRUD) are required — only use those if installed and an extension pack applies.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect existing API controllers, `viewClasses` / negotiation, exception renderers, and route prefixes (`Api/`).
3. Note authentication/authorization **as already used** in this app’s API.
4. Check Entity serialization (`_hidden`, `_virtual`) and mass-assignment exposure.

## Workflow

1. Match existing API controller patterns (prefix, inheritance, components).
2. Wire routes for the resource/action.
3. Keep actions thin: parse input, call Table/finder, map domain outcomes to HTTP status + body.
4. Surface validation/rule errors with the project’s error envelope (do not invent a new global format if one exists).
5. Avoid overexposing entities in JSON; hide secrets and internal fields.
6. Add API/integration tests for status codes and payloads.

## Framework decisions

- Same ownership rules as web actions — `choose-cakephp-abstraction`.
- `knowledge/decisions/component-vs-middleware` for API-wide cross-cutting concerns.
- Anti-patterns: mass-assignment-overexposure, serialization-overexposure, fat-controller.

## Anti-patterns

- Duplicating a new response format per action.
- Returning full Entity graphs with sensitive fields.
- Assuming Sanctum/Passport/Laravel Resource classes.
- Requiring FriendsOfCake CRUD listener patterns when CRUD is not installed.

## Validation

- Endpoint returns agreed content type and sensible status codes.
- Invalid input fails via validation/rules without persisting bad state.
- Auth assumptions match installed packages only.

## Completion criteria

- Route + action + tests in place; serialization and error shape match project API conventions.
