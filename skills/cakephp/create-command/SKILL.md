---
name: create-command
description: Create a CakePHP console Command for CLI/batch work using bin/cake (not Artisan).
---

# Create command

## Objective

Implement a CakePHP Command that owns a CLI entry point: options/arguments, IO, exit codes, and orchestration of Table (or existing app) APIs.

## Use when

- Adding cron/scheduled jobs, one-shot ops, or non-HTTP batch processing.
- Replacing an HTTP-controller-as-cron pattern with a proper Command.

## Do not use when

- The primary entry point is HTTP — use `create-controller-action` / `create-api-endpoint`.
- Ownership of domain logic is unclear — use `choose-cakephp-abstraction`.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect existing Commands for option-parser and IO style.
3. Identify Tables/finders/services the Command should call.
4. Note transaction needs for multi-write jobs (`add-transaction`).

## Workflow

1. Create a Command class matching project namespace and naming.
2. Define `buildOptionParser` / arguments consistently with neighbors.
3. Orchestrate only: parse IO → call Tables → print results / set exit code.
4. Reuse application rules and finders; do not invent Artisan APIs.
5. For multi-write consistency, wrap with `Connection::transactional()` via `add-transaction`.
6. Add a Command test or documented manual invocation (`bin/cake …`).

## Framework decisions

- `knowledge/decisions/command-vs-controller-action`
- `knowledge/decisions/transaction-vs-independent-save` when batch writes are atomic

## Anti-patterns

- Artisan / Illuminate console inventions.
- Fat commands with embedded domain rules and raw SQL.
- Defaulting to curl-ing HTTP controllers for cron.

## Validation

- `bin/cake <name> --help` works; happy path and failure exit codes behave.
- Persistence goes through Tables.

## Completion criteria

- Command implemented, runnable, tested or runbooked, with thin orchestration.
