---
id: duplicate-finders
type: anti-pattern
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# duplicate-finders

## Symptoms

The same query semantics are copy-pasted across controllers, Tables, or similarly named finders

## Why it matters

Drift and inconsistent filters; harder performance fixes

## False positives

Similar finders on different Tables with different meanings are not duplicates.

## Detection guidance

Search for repeated where/contain clauses or near-duplicate findX methods across the codebase.

## Preferred refactoring

Consolidate into one named finder (or shared Behavior finder) and reuse it

## When no refactor is warranted

Temporarily diverging experiments with an explicit follow-up to consolidate.
