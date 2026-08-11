---
id: callback-side-effect-explosion
type: anti-pattern
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# callback-side-effect-explosion

## Symptoms

Table callbacks trigger many unrelated side effects (mail, remote APIs, cascading writes)

## Why it matters

Surprise failures on save; hard to test; violates transaction boundaries

## False positives

Small, closely related maintenance (touching a counter cache) can be legitimate.

## Detection guidance

Read beforeSave/afterSave/afterCommit callbacks for IO and multi-model writes unrelated to the entity being saved.

## Preferred refactoring

Move decoupled side effects to listeners/jobs after commit; keep required invariants in rules

## When no refactor is warranted

Plugin-defined callbacks that are the documented extension point.
