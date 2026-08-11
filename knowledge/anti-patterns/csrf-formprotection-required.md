---
id: csrf-formprotection-required
type: anti-pattern
scope: security
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: critical
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# csrf-formprotection-required

## Symptoms

State-changing HTML forms omit CakePHP FormProtection/CSRF middleware tokens, or “CSRF” is enforced only in JavaScript

## Why it matters

Cross-site request forgery can mutate data with the victim’s session

## False positives

Token-authenticated pure JSON APIs that intentionally use a different anti-CSRF strategy (custom headers, SameSite-only policies) documented by the project

## Detection guidance

For HTML form POST/PUT/PATCH/DELETE flows, confirm FormProtectionComponent and/or CsrfProtectionMiddleware participation matching Application setup. Flag forms that disable protection without an explicit documented exception.

## Preferred refactoring

Wire forms through CakePHP FormHelper / FormProtection and keep CsrfProtectionMiddleware enabled for browser sessions

## When no refactor is warranted

Non-browser machine clients using non-cookie auth with an explicit alternate CSRF strategy.
