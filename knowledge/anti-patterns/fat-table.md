---
id: fat-table
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# fat-table

## Symptoms

Table classes accumulate unrelated query, UI, and integration logic

## Why it matters

Becomes an untestable god object

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Preferred refactoring

Extract finders/behaviors/listeners; keep Table focused on persistence boundary

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.