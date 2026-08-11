---
id: reimplemented-crud-action
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

# reimplemented-crud-action

## Symptoms

Controller reimplements index/view/add/edit/delete while CRUD actions are enabled or available

## Why it matters

Duplicates plugin flow; drifts from listeners/config; fights the pack

## False positives

Custom non-CRUD endpoints (reports, wizards) that never claimed to be CRUD actions

## Detection guidance

Compare controller methods to enabled CRUD actions; flag parallel boilerplate

## Preferred refactoring

Return to Crud->execute / config / listeners; use migrate-controller-to-crud

## When no refactor is warranted

Explicit project decision to leave CRUD for that resource, documented in .ai/
