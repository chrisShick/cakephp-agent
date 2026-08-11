---
id: authorization-only-in-ui
type: anti-pattern
scope: security
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: critical
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# authorization-only-in-ui

## Symptoms

Permissions enforced only by hiding buttons/links or client routing, without server-side authorization

## Why it matters

IDOR and direct URL/API access bypass UI constraints

## False positives

UI hiding as a convenience on top of real server authorization is fine.

## Detection guidance

Trace sensitive actions: if policy/authorize/isAuthorized checks are missing on the server path, flag UI-only gating.

## Preferred refactoring

Enforce authorization in policies/middleware/controller server paths; keep UI as UX only

## When no refactor is warranted

Purely cosmetic UI states with no sensitive capability behind them.
