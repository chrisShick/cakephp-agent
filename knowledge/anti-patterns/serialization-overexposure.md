---
id: serialization-overexposure
type: anti-pattern
scope: security
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: critical
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# serialization-overexposure

## Symptoms

Entities/API resources expose hidden/sensitive fields via $_virtual, $_hidden mistakes, or broad toArray

## Why it matters

Leaks secrets, tokens, internal flags

## False positives

Admin-only serializers with explicit allow-lists are acceptable when gated by authz.

## Detection guidance

Review Entity $_hidden/$_virtual and API transform layers for password hashes, tokens, and internal columns.

## Preferred refactoring

Default-deny serialization; hide sensitive fields; use explicit API shaping

## When no refactor is warranted

Internal CLI dumps never returned to clients.
