---
name: review-php-safety
description: Review PHP code for typing, dangerous APIs, password handling, path traversal, and secret hygiene.
---

# Review PHP safety

## Objective

Review PHP changes for baseline safety issues that matter in CakePHP apps: types, crypto, dangerous functions, path handling, and production error exposure.

## Use when

- Reviewing PHP diffs alongside or before CakePHP security review.
- Hardening legacy PHP touched by a feature.

## Do not use when

- The issue is purely CakePHP ownership — use `review-cakephp-security` / abstraction skills.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Scan for `eval`, `unserialize`, `shell_exec`, weak password storage, raw path joins, `@`.
3. Note production error/debug configuration if touched.

## Workflow

1. Flag missing strict types on new public APIs.
2. Flag dangerous APIs on untrusted input.
3. Flag weak password handling.
4. Flag path traversal from client filenames.
5. Flag secrets in source or verbose prod errors.
6. Hand remediations to `apply-strict-types` or CakePHP security/form/upload skills.

## Framework decisions

- `rules/php/php.mdc`, `rules/php/php-safety.mdc`

## Anti-patterns

- Demanding frameworks foreign to the project
- Ignoring exploitable issues for style nits

## Validation

- Findings cite code and concrete PHP/CakePHP fixes.

## Completion criteria

- Safety review written with prioritized fixes.
