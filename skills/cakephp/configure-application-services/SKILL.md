---
name: configure-application-services
description: Wire CakePHP Application::services() / config for shared infrastructure without Laravel providers.
---

# Configure application services

## Objective

Register or adjust shared services and configuration using CakePHP config files and `Application::services()` as the project already does — not ServiceProviders/facades.

## Use when

- Adding a shared client/service that controllers/commands should receive via DI.
- Moving hardcoded credentials/hosts into config/env.

## Do not use when

- The need is a one-off Table call with no shared infrastructure.
- Ownership of domain logic is the real question — use `choose-cakephp-abstraction`.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect `config/app.php`, `app_local.php`, env usage, and `Application::services()`.
3. Copy neighboring registration patterns (interfaces vs concretes).

## Workflow

1. Put env-specific values in config/env.
2. Register shared services in `Application::services()` when DI is established.
3. Inject into controllers/commands the project’s way.
4. Avoid inventing Laravel providers/facades.
5. Smoke-test boot and one consumer path.

## Framework decisions

- `knowledge/decisions/config-vs-hardcoded-service`

## Anti-patterns

- Hardcoded secrets in domain classes.
- ServiceProvider / facade inventions.

## Validation

- App boots; consumer resolves the service; secrets are not committed.

## Completion criteria

- Config/DI wiring matches project patterns; consumer updated; smoke-tested.
