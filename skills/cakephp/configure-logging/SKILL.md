---
name: configure-logging
description: Configure CakePHP Log engines and keep secrets out of logs.
---


# Configure logging

## Objective

Set up CakePHP Log engines/levels and ensure application logging is useful without leaking secrets.

## Use when

- Adding/changing log channels or production log levels.
- Cleaning secret leakage from log lines.

## Do not use when

- You only need a one-off debug dump locally (do not commit it).

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Read logging config and existing `Log::` call sites.
3. Note PII/secret fields in entities.

## Workflow

1. Configure engines/scopes/levels per environment.
2. Log failures with safe context arrays.
3. Scrub passwords/tokens from contexts.
4. Remove committed `debug()`/`var_dump` hot paths.
5. Verify a failure path appears in logs.

## Framework decisions

- Pair with `security` secret-hygiene expectations

## Anti-patterns

- Logging credentials or raw cards
- Leaving debug dumps in production code

## Validation

- Operators can diagnose failures; secrets absent from samples.

## Completion criteria

- Logging config + safe call sites verified.

