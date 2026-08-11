---
id: bypassing-orm-for-convenience
type: anti-pattern
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-11
related: [orm-vs-connection-sql]
---

# bypassing-orm-for-convenience

## Symptoms

`Connection::execute`, PDO, or hand-written SQL used for ordinary Table finds/saves; controllers own SQL strings; associations reinvented with manual joins when `contain`/`matching` fit.

## Why it matters

Skips CakePHP conventions, associations, marshaling, validation/rules, and makes SQL injection and duplication more likely.

## False positives

Documented reporting SQL, vendor maintenance, or ORM-impossible queries that are isolated, bound, and tested are not this smell.

## Detection guidance

Look for `Connection::execute` / raw SQL next to domain Table names; controller SQL for CRUD-shaped work; comments like “faster to just write SQL” without measuring ORM alternatives.

## Preferred refactoring

Move to Table finders / Query expressions / `save` paths per `orm-vs-connection-sql`. Keep exceptional SQL behind a clear ownership boundary.

## When no refactor is warranted

Proven exceptional SQL with bindings and project documentation (`.ai/`) explaining why the ORM is insufficient.
