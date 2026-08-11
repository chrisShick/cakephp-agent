---
id: fat-controller
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# fat-controller

## Symptoms

Controllers contain substantial persistence/domain logic

## Why it matters

Hard to test; duplicates model rules; blurs HTTP vs domain ownership

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Detection guidance

Look for persistence, complex queries, and domain invariants inside controller actions instead of HTTP orchestration.

## Preferred refactoring

Move persistence to Tables/rules/finders; keep controllers as HTTP orchestration

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.