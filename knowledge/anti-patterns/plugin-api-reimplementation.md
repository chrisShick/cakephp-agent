---
id: plugin-api-reimplementation
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# plugin-api-reimplementation

## Symptoms

App reinvents installed plugin behavior

## Why it matters

Drift and duplicated bugs

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Detection guidance

App code reinventing behaviors already provided by an installed plugin API.

## Preferred refactoring

Use plugin extension points when the pack is enabled

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.