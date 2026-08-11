---
name: create-queue-job
description: Create a cakephp/queue job/listener when the plugin is installed.
---

# Create queue job

## Objective

Add a background job using `cakephp/queue` for deferred work (email, webhooks, fan-out) without Laravel queue APIs.

## Use when

- Deferred IO should leave the request/command quickly and queue is installed.

## Do not use when

- `cakephp/queue` is not installed — use Command/cron or ask to install the plugin.
- The work must be synchronous for correctness.

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm `cakephp/queue` ^2.
2. Inspect existing jobs/listeners and queue config.
3. Decide payload (IDs not whole entities when possible).

## Workflow

1. Create job/listener matching project patterns.
2. Pass identifiers; reload via Tables inside the job.
3. Handle failure/retry per config; log safely.
4. Enqueue from after-commit paths when paired with DB writes.
5. Add a test or documented manual worker run.

## Framework decisions

- Plugin honesty; `transaction-vs-independent-save` for after-commit

## Anti-patterns

- ShouldQueue / artisan queue inventions
- Embedding huge entities in payloads

## Validation

- Job runs via worker; failure path is visible.

## Completion criteria

- Job implemented, enqueued from the right place, runnable.
