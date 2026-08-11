---
id: mass-assignment-overexposure
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# mass-assignment-overexposure

## Symptoms

_accessible is widened just to make patching easier

## Why it matters

Security and integrity risk

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Preferred refactoring

Keep accessibility tight; patch with field lists

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.