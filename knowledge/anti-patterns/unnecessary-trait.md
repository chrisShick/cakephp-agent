---
id: unnecessary-trait
type: anti-pattern
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: medium
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# unnecessary-trait

## Symptoms

Traits used to share logic that belongs in a Behavior, Component, inheritance, or plain collaboration

## Why it matters

Hidden coupling; harder discovery than CakePHP extension points

## False positives

Narrow PHP reuse (small pure helpers) can be fine.

## Detection guidance

Traits mixed into Tables/controllers that look like Behaviors/Components or copy ORM helpers.

## Preferred refactoring

Prefer Behavior/Component/listener/service collaboration per ownership decisions

## When no refactor is warranted

Vendor/PHP ecosystem traits with clear purpose.
