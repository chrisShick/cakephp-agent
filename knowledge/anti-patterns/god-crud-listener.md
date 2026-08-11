---
id: god-crud-listener
type: anti-pattern
scope: plugins
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
package: friendsofcake/crud
package_versions: [">=7.0 <8.0"]
last_verified: 2026-08-11
---

# god-crud-listener

## Symptoms

A CRUD listener accumulates unrelated query, authz, mail, billing, and response logic for many controllers

## Why it matters

Opaque mixin; hard to test; hides ORM/HTTP ownership boundaries

## False positives

A focused Api\ArticlesListener with several related Crud.* hooks is fine

## Detection guidance

List implementedEvents and methods; cluster by concern; flag cross-domain dumps

## Preferred refactoring

Split listeners by resource/concern; move invariants to Table rules; mail to Mailer

## When no refactor is warranted

Thin plugin-style listeners that intentionally bundle one product feature
