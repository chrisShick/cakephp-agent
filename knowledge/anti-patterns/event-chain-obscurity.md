---
id: event-chain-obscurity
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# event-chain-obscurity

## Symptoms

Critical business flow is only understandable by chasing nested events across listeners

## Why it matters

Onboarding and incident response suffer; ordering bugs hide

## False positives

Open plugin event hooks for optional subscribers are expected extension points.

## Detection guidance

For a primary use case, ask whether a new engineer can name the success path without grepping event names. If not, flag obscurity.

## Preferred refactoring

Make required steps direct calls; reserve events for optional/decoupled reactions

## When no refactor is warranted

Intentionally pluggable platforms where the event bus is the public API.
