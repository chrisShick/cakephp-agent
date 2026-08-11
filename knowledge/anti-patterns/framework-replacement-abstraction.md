---
id: framework-replacement-abstraction
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# framework-replacement-abstraction

## Symptoms

Custom layers exist only to hide CakePHP

## Why it matters

Onboarding cost; fights conventions

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Detection guidance

Custom layers whose only job is to hide CakePHP Tables/controllers behind generic ports.

## Preferred refactoring

Prefer CakePHP extension points; invent abstractions for real domain needs

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.