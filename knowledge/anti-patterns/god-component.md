---
id: god-component
type: anti-pattern
scope: http
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# god-component

## Symptoms

A Component becomes a dumping ground for unrelated controller utilities and business logic

## Why it matters

Bypasses middleware/Table ownership; couples controllers to a kitchen-sink collaborator

## False positives

A Component with a few related HTTP collaboration helpers is fine.

## Detection guidance

Inventory Component methods and which controllers load them. Flag Components used as general-purpose app services or persistence layers.

## Preferred refactoring

Split Components; move pipeline concerns to middleware; move persistence to Tables/rules

## When no refactor is warranted

Project-standard UI/request helpers documented in `.ai/` with clear HTTP-only scope.
