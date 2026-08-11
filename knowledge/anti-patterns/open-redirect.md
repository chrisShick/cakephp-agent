---
id: open-redirect
type: anti-pattern
scope: security
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# open-redirect

## Symptoms

Redirect target comes from unsanitized user input (`?redirect=https://evil.test`)

## Why it matters

Phishing and token theft via trusted-domain redirects

## False positives

Allow-listed internal paths/routes validated against a known map

## Detection guidance

Search redirects using query/body parameters without allow-list checks.

## Preferred refactoring

Redirect only to named routes or allow-listed local paths

## When no refactor is warranted

Documented external redirect brokers with strict allow-lists
