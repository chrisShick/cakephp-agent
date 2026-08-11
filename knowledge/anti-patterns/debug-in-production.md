---
id: debug-in-production
type: anti-pattern
scope: security
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: critical
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# debug-in-production

## Symptoms

`debug` true, DebugKit enabled, or stack traces exposed on production

## Why it matters

Leaks paths, queries, config, and sometimes secrets

## False positives

Ephemeral staging with authenticated access and no sensitive data

## Detection guidance

Check `app_local`/env for `DEBUG`, DebugKit plugin load, and error renderer verbosity in prod.

## Preferred refactoring

Disable debug/DebugKit in production; log server-side instead

## When no refactor is warranted

None for true production internet-facing apps
