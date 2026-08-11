---
id: duplicate-validation
type: anti-pattern
scope: validation
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# duplicate-validation

## Symptoms

The same field rules are redefined in multiple validators, controllers, or frontend-only checks without a single owner

## Why it matters

Divergent acceptance criteria; bypass risk on alternate entry points

## False positives

UX-only client checks that still rely on server validators are expected.

## Detection guidance

Compare validationDefault/named validators and controller-side checks for the same fields.

## Preferred refactoring

Own shape/format rules in Table validators; share providers where needed

## When no refactor is warranted

Intentionally stricter admin validators vs public validators — document the split.
