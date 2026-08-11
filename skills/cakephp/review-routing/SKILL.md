---
name: review-routing
description: Audit CakePHP routes for ownership mistakes, conflicts, and non-CakePHP router inventions.
---

# Review routing

## Objective

Review the application’s route table and route files for correct CakePHP ownership: declarative maps, no domain logic in routes, consistent scopes/prefixes, and no Laravel router substitutions.

## Use when

- Auditing why a URL 404s or hits the wrong action.
- Reviewing a PR that changes `config/routes.php` or plugin routes.
- Suspecting duplicated or conflicting connects.

## Do not use when

- You need to add a single known route — use `add-route`.
- The issue is controller/Table logic after a successful match.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Read route files and run `bin/cake routes` when available.
3. Compare new paths against existing scopes, prefixes, and fallbacks.
4. Check for disabled FormProtection/CSRF only if routes relate to form POSTs (pair with security review).

## Workflow

1. Inventory scopes/prefixes/resources and fallback order.
2. Flag persistence/authz/domain logic inside route callables.
3. Flag Laravel Route/Artisan patterns and inventing parallel routers.
4. Note ambiguous overlaps (two connects matching the same path).
5. Recommend `add-route` fixes or controller moves; keep recommendations CakePHP-native.

## Framework decisions

- `knowledge/decisions/route-config-vs-controller-url-logic`
- Anti-Laravel awareness for router/CLI commands

## Anti-patterns

- Reviewing routes without reading `config/routes.php`.
- Prescribing Laravel routing as “cleaner.”
- Demanding a microservice gateway for a simple connect.

## Validation

- Findings cite route file evidence and preferred CakePHP fix.
- No phantom plugin routers recommended.

## Completion criteria

- Written routing review with prioritized fixes and clear next skill (`add-route`, `create-controller-action`).
