---
id: duplicate-application-rules
type: anti-pattern
scope: validation
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# duplicate-application-rules

## Symptoms

The same persistence invariant is reimplemented in callbacks, controllers, and RulesChecker

## Why it matters

Bypass paths and conflicting error handling

## False positives

Early UX validation mirroring a rule is OK if the application rule remains authoritative.

## Detection guidance

Search for uniqueness/existence checks outside buildRules that mirror RulesChecker rules.

## Preferred refactoring

Keep stateful invariants in buildRules; remove redundant save-path duplicates

## When no refactor is warranted

Read-model guards that are not persistence authorities.
