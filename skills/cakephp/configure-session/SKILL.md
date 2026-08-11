---
name: configure-session
description: Configure CakePHP sessions/cookies safely via config and request APIs.
---


# Configure session

## Objective

Configure or adjust CakePHP session/cookie behavior using config and request/response APIs — not ad-hoc `$_SESSION` / `setcookie` sprawl.

## Use when

- Changing session engine, timeouts, or cookie security flags.
- Replacing raw PHP session usage with CakePHP APIs.

## Do not use when

- The work is Authentication plugin wiring — use Auth packs when installed.
- Ownership of flash UX only — follow `request-response` / Flash neighbors.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Read `config/app.php` session/cookie settings and Application middleware.
3. Inspect how controllers read session/Flash today.

## Workflow

1. Adjust session config for the environment (engine, timeout, cookie params).
2. Ensure controllers use `$request->getSession()` / Flash conventions.
3. Verify Secure/HttpOnly/SameSite for the deployment.
4. Avoid storing secrets or huge payloads in session.
5. Smoke-test login/flash flows if present.

## Framework decisions

- Prefer framework session APIs over `$_SESSION`
- Auth identity loading still follows Auth packs when installed

## Anti-patterns

- Raw `$_SESSION` in new app code
- Insecure cookie flags on HTTPS apps

## Validation

- Session persists as expected; cookies have intended flags.

## Completion criteria

- Config updated; call sites use CakePHP APIs; smoke-tested.

