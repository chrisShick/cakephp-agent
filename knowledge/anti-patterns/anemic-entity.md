---
id: anemic-entity
type: anti-pattern
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: medium
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# anemic-entity

## Symptoms

Entities are bags of fields with all domain meaning forced into Tables/services, including trivial derived values

## Why it matters

Misses CakePHP entity accessors/methods for loaded-state domain behavior; pushes noise elsewhere

## False positives

Keeping Entities free of persistence/IO is correct — that is not anemia. Avoid Active Record.

## Detection guidance

Check whether simple derived values from already-loaded fields are computed only in templates/controllers instead of entity accessors.

## Preferred refactoring

Add entity methods/accessors for derived values from loaded data; keep queries on Tables/finders

## When no refactor is warranted

DTOs or API resources intentionally separate from ORM entities.
