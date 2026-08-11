---
id: active-record-entity
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# active-record-entity

## Symptoms

Entities issue queries or save themselves by default

## Why it matters

Breaks CakePHP Table/Entity separation

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Preferred refactoring

Keep queries/saves on Tables; entities hold state

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.