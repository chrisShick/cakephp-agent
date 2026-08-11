---
id: orm-invariant-in-crud-listener
type: anti-pattern
scope: plugins
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: critical
truth_level: PACKAGE_RECOMMENDATION
package: friendsofcake/crud
package_versions: [">=7.0 <8.0"]
last_verified: 2026-08-11
---

# orm-invariant-in-crud-listener

## Symptoms

Uniqueness, existence, or other save invariants enforced only in Crud.beforeSave / listeners

## Why it matters

Bypassed by CLI, jobs, and non-CRUD controllers

## False positives

CRUD-only flash/redirect after a failed save that already used Table rules

## Detection guidance

Search listeners for uniqueness/exists checks not mirrored in buildRules/Validator

## Preferred refactoring

Move to Validator or RulesChecker; keep listener for HTTP/CRUD lifecycle only

## When no refactor is warranted

None for true persistence invariants
