---
id: over-eager-contain
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# over-eager-contain

## Symptoms

contain() used because associations exist

## Why it matters

Over-fetching and row explosion

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Detection guidance

contain() lists that load associations unused by the view/API serializer.

## Preferred refactoring

Contain only what the use case needs; prefer matching/joins for filters

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.