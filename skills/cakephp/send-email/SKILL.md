---
name: send-email
description: Send email with CakePHP Mailer classes and config transports.
---


# Send email

## Objective

Compose and send email via CakePHP Mailer, keeping controllers/commands thin and transports configured safely.

## Use when

- Adding transactional or notification email in a CakePHP app.

## Do not use when

- You are tempted to invent Laravel Mailables — stop.
- Heavy async delivery requires a queue pack — check Composer first.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect existing Mailer classes and email transport config.
3. Decide sync vs after-commit/queued sending.

## Workflow

1. Create/extend a Mailer class for the message.
2. Configure transport in config; secrets in env.
3. Call Mailer from controller/command/listener thinly.
4. Prefer after-commit when email must not undo DB success.
5. Test with project mailer test patterns (or dry-run transport).

## Framework decisions

- `transaction-vs-independent-save` for after-commit side effects

## Anti-patterns

- Mailable / Mail facade inventions
- SMTP passwords in code

## Validation

- Message renders; transport config resolves; failures are visible.

## Completion criteria

- Mailer implemented and invoked from the right layer; config-safe.

