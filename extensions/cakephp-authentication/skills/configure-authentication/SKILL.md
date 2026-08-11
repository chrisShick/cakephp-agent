---
name: configure-authentication
description: Configure CakePHP Authentication service, middleware, and login flow for cakephp/authentication.
---

# Configure authentication

## Objective

Wire `cakephp/authentication` into the app (service, middleware, authenticators/identifiers, unauthenticated handling) using project conventions.

## Use when

- Adding or reshaping Authentication plugin setup.
- Aligning login/logout with official ^4 patterns.

## Do not use when

- The package is not installed.
- The task is permission/policy work (Authorization pack / integration).

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm `cakephp/authentication` ^4.
2. Inspect `Application` middleware, existing AuthenticationService config, Users table, login templates.
3. Note whether Authorization is also installed — do not configure policies here.

## Workflow

1. Load plugin if the project uses explicit plugin loading.
2. Configure AuthenticationService (authenticators, identifiers, redirects) per docs + neighbors.
3. Add Authentication middleware in the correct queue position.
4. Apply controller allow-unauthenticated patterns for login/public actions.
5. Ensure password hashing on user persistence.
6. Add integration tests for login success/failure/logout.

## Framework decisions

- Authentication ≠ authorization (`rules/separation.mdc`)
- Prefer middleware for cross-cutting identity establishment

## Anti-patterns

- Recommending Policy APIs from this skill
- Storing plaintext passwords
- Laravel Guard/Sanctum assumptions

## Validation

- Unauthenticated users hit configured handling; valid credentials establish identity.

## Completion criteria

- Service + middleware + login path working with tests; no AuthZ APIs invented.
