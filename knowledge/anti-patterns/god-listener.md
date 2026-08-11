---
id: god-listener
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# god-listener

## Symptoms

One listener handles many unrelated events with branching side effects

## Why it matters

Obscures control flow; makes failures and ordering hard to reason about

## False positives

A listener handling a small family of related events (order.*) can be coherent.

## Detection guidance

Map events handled per listener class. Flag listeners with large switch/if chains across domains.

## Preferred refactoring

Split listeners by bounded context; prefer direct calls for required local steps

## When no refactor is warranted

Framework/plugin bridge listeners that intentionally fan in many events to one adapter.
