---
id: god-behavior
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# god-behavior

## Symptoms

A Behavior accumulates unrelated finders, callbacks, and helpers for many concerns

## Why it matters

Hard to configure, test, and reuse; hides ownership; becomes an opaque mixin dump

## False positives

A focused Behavior with several related methods (Tree) is not a god Behavior. Shared Timestamp-style packs are fine.

## Detection guidance

List public methods/callbacks on Behaviors. Cluster by concern. Flag Behaviors that mix unrelated domains (e.g. soft-delete + billing + search) without a coherent theme.

## Preferred refactoring

Split by concern into smaller Behaviors or move Table-specific logic to Table finders/callbacks

## When no refactor is warranted

Thin plugin Behaviors that intentionally bundle a product feature set — keep if the plugin API is the unit of reuse.
