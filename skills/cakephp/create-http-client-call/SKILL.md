---
name: create-http-client-call
description: Add outbound HTTP calls with Cake\Http\Client and config-owned credentials.
---


# Create HTTP client call

## Objective

Implement outbound HTTP using CakePHP Http Client (or project wrapper) with config/DI-owned credentials and explicit error handling.

## Use when

- Integrating a remote JSON/HTTP API from CakePHP app code.

## Do not use when

- The work belongs in a queue/mailer — use those skills.
- Ownership unclear — `choose-cakephp-abstraction` first.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Find existing Http Client wrappers and `Application::services()` registrations.
3. Locate config keys for base URL/tokens.

## Workflow

1. Put credentials/base URL in config/env.
2. Reuse or register a client via DI when shared.
3. Call with timeouts; handle non-2xx explicitly.
4. Keep remote IO out of Entities.
5. Add a test with a mocked client if the project does so.

## Framework decisions

- `config-vs-hardcoded-service`

## Anti-patterns

- Hardcoded tokens
- Active-record Entities doing HTTP

## Validation

- Success and failure paths behave; secrets not in repo.

## Completion criteria

- Client call wired through config/DI; errors handled; tested or smoke-checked.

