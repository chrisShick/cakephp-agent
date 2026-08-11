---
id: premature-service-layer
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# premature-service-layer

## Symptoms

Services introduced solely to shorten controllers

## Why it matters

Extra indirection without architectural value

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Detection guidance

Flag new *Service classes that only wrap a single Table find/save with no multi-step orchestration.

## Preferred refactoring

Prefer Table/Entity/Behavior/component/listener first; add services for real orchestration

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.