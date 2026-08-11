---
id: repository-over-table
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# repository-over-table

## Symptoms

Every Table wrapped in a repository by default

## Why it matters

Hides CakePHP without benefit

## False positives

Large files alone are not proof. Evaluate ownership and duplication, not line count.

## Detection guidance

Flag repositories that exist solely to proxy Table methods without an anti-corruption or multi-source reason.

## Preferred refactoring

Use Tables directly unless a clear architectural boundary exists

## When no refactor is warranted

Established project conventions or temporary scaffolding with a clear follow-up may justify leaving code as-is — document the exception in `.ai/architecture.md`.