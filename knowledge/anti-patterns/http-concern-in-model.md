---
id: http-concern-in-model
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# http-concern-in-model

## Symptoms

Tables/entities format redirects, flash, or response JSON

## Why it matters

Couples persistence to HTTP

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Preferred refactoring

Keep HTTP shaping in controllers/components/listeners

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.