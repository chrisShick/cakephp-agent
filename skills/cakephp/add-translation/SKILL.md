---
name: add-translation
description: Add or update CakePHP I18n message strings and catalogs.
---


# Add translation

## Objective

Internationalize user-facing strings using CakePHP I18n helpers and locale catalogs consistent with the project.

## Use when

- The app is localized and new UI/API messages need catalogs.
- Fixing hardcoded strings that neighbors already wrap with `__()`.

## Do not use when

- The project is intentionally single-locale with no I18n practice — do not invent a translation platform.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect existing `__()` / `__d()` usage and `resources/locales`.
3. Note domains and pluralization patterns.

## Workflow

1. Wrap new user-facing strings with project helpers.
2. Add/update locale catalog entries.
3. Keep controllers free of per-language conditionals.
4. Format dates/numbers with I18n formatters when needed.
5. Spot-check locale switching if the app supports it.

## Framework decisions

- Prefer CakePHP I18n over parallel Laravel lang layouts

## Anti-patterns

- Parallel translation systems
- Language `if` forests in controllers

## Validation

- Strings resolve for the default locale; catalogs contain new keys.

## Completion criteria

- Strings wrapped; catalogs updated; matches project I18n style.

