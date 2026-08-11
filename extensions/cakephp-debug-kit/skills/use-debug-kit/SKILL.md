---
name: use-debug-kit
description: Use DebugKit panels in development to diagnose requests/SQL — not in production.
---

# Use DebugKit

## Objective

Leverage DebugKit in local/dev to inspect SQL and request panels, then fix code with proper CakePHP skills.

## Use when

- DebugKit is installed and you are diagnosing a slow/wrong request in development.

## Do not use when

- Production troubleshooting — use logs/APM the project already has.
- DebugKit is not installed.

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm require-dev DebugKit.
2. Reproduce under debug with toolbar visible.
3. Note SQL panel N+1/duplicates.

## Workflow

1. Reproduce the request with DebugKit enabled in dev.
2. Identify expensive queries/contains.
3. Hand off fixes to `review-query-performance` / `diagnose-orm-query` / finders.
4. Ensure DebugKit stays disabled in production config.

## Framework decisions

- Performance and ORM ownership skills for remediations

## Anti-patterns

- Production DebugKit
- Telescope inventions

## Validation

- Issue identified from panels; fix landed in code; prod remains clean.

## Completion criteria

- Diagnosis noted and remediation skill applied; no prod DebugKit.
