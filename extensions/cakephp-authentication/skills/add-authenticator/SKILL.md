---
name: add-authenticator
description: Add or adjust a CakePHP Authentication authenticator/identifier.
---

# Add authenticator

## Objective

Add a documented authenticator and matching identifier without breaking existing login paths.

## Use when

- Supporting form, session, token, or other authenticators provided by the plugin/project.
- Changing credential field maps.

## Do not use when

- Authentication package absent.
- Building authorization policies.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Read current AuthenticationService authenticator stack.
3. Confirm credential sources (body, headers, session).

## Workflow

1. Choose authenticator/identifier classes from installed plugin docs.
2. Register with correct config and priority/order.
3. Keep secrets out of source; use config/env patterns already in the app.
4. Update login clients/forms if fields change.
5. Test the new path and regression on existing authenticators.

## Framework decisions

- Stay within Authentication plugin APIs for the installed major

## Anti-patterns

- Custom header parsing that bypasses authenticators without need
- Committing API keys in authenticator config

## Validation

- New authenticator succeeds/fails as expected; others still work.

## Completion criteria

- Authenticator/identifier configured and tested.
