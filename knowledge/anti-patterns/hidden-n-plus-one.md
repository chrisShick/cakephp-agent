---
id: hidden-n-plus-one
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# hidden-n-plus-one

## Symptoms

Associations accessed in loops without contain/select strategy

## Why it matters

Silent performance collapse

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Preferred refactoring

Eager-load deliberately; inspect query count

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.