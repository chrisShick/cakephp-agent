---
name: debug-authentication
description: Diagnose CakePHP Authentication failures (middleware, identifiers, redirects).
---

# Debug authentication

## Objective

Find why identity is missing or login fails and fix at the Authentication layer.

## Use when

- Redirect loops, failed logins, missing identity in controllers.
- Authenticator order or identifier field mismatches.

## Do not use when

- The failure is “forbidden despite logged in” and Authorization is installed — use AuthZ debug (or integration review).

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Reproduce; note URL, method, session/token, middleware order.
3. Inspect AuthenticationService config and Users identifier query.

## Workflow

1. Confirm middleware runs and plugin is loaded.
2. Check authenticator selection for the request.
3. Verify identifier fields and password hasher.
4. Check allowUnauthenticated / unauthenticatedRedirect.
5. Fix config/code; add regression test.

## Framework decisions

- Do not “fix” by hard-coding a user in the controller

## Anti-patterns

- Disabling authentication globally to unblock a feature
- Mixing authorization denials into authn debugging without evidence

## Validation

- Identity present when credentials valid; public actions remain reachable.

## Completion criteria

- Root cause named; fix verified with a test or clear repro notes.
