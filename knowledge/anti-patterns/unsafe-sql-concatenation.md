---
id: unsafe-sql-concatenation
type: anti-pattern
scope: security
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: critical
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-11
related: [orm-vs-connection-sql]
---

# unsafe-sql-concatenation

## Symptoms

User or request data interpolated into SQL with string concat / interpolation; unbound fragments in `execute()` or `query()`; “escaping” by hand instead of bindings.

## Why it matters

SQL injection and data exfiltration risk; bypasses CakePHP’s expression/binding protections.

## False positives

Fully static SQL with no external input, or fragments built only from allow-listed identifiers with bound values, are not this smell.

## Detection guidance

Search for SQL strings using `$request`, `$this->request`, query params, or untrusted variables; `Connection::execute` with interpolated values; absence of `bind` / placeholders.

## Preferred refactoring

Prefer ORM Query expressions and bound values. If raw SQL is required, use placeholders and bindings only — never concatenate untrusted input.

## When no refactor is warranted

None for untrusted input. Static admin SQL with no user data may remain if reviewed.
