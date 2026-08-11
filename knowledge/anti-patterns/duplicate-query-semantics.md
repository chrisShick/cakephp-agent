---
id: duplicate-query-semantics
type: anti-pattern
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# duplicate-query-semantics

## Symptoms

Identical business filters exist as ad-hoc query fragments instead of a named abstraction

## Why it matters

Same as duplicate finders — inconsistency and review noise

## False positives

One-off admin reports may intentionally differ from canonical list finders.

## Detection guidance

Compare controller query builders and Table methods for the same domain filter expressed differently.

## Preferred refactoring

Promote to a custom finder or association-aware helper; document the canonical form

## When no refactor is warranted

Throwaway scripts/migrations outside the app runtime path.
