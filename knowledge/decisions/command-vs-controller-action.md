---
id: command-vs-controller-action
type: decision
scope: console
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/console-commands/commands.html
  - https://book.cakephp.org/5.x/controllers.html
last_verified: 2026-08-10
related: [route-config-vs-controller-url-logic, transaction-vs-independent-save]
evaluations: [cli-batch-prefers-command, reject-http-controller-as-default-cron-entry]
---

# Command vs controller action

## Use cases

- Scheduled jobs, one-shot ops, and non-HTTP batch work.
- Browser/API request handling.

## Decision questions

1. Is the primary entry point CLI (`bin/cake`) rather than HTTP?
2. Does it need request/response, sessions, or HTML/JSON negotiation?
3. Can shared domain work live on Tables/services invoked by both Command and Controller?

## Recommended outcome

- **Command** for CLI/batch/cron entry points.
- **Controller action** for HTTP orchestration.
- Share persistence/query logic on Tables (and app services when warranted) — do not paste it into either layer.

## Rejected alternatives

- Hitting an HTTP controller from cron as the default batch pattern.
- Inventing Artisan command classes in a CakePHP app.

## Exceptions

- Rare operational “curl the internal endpoint” setups may exist; treat as project convention and document why, not as the CakePHP default.
- Plugins may ship Commands — prefer those when installed.

## Examples

Nightly purge of expired tokens → `bin/cake Tokens.purge` Command. User deletes a token in the UI → controller action calling the same Table API.

## Evaluations

- `cli-batch-prefers-command`
- `reject-http-controller-as-default-cron-entry`
