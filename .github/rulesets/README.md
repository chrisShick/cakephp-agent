# GitHub rulesets (import)

Import these under **Settings → Rules → Rulesets → New ruleset → Import a ruleset**. Import each file separately, review, then **Create**.

| File | Protects | Intent |
|---|---|---|
| [`main-branch-protection.json`](main-branch-protection.json) | default branch (`main`) | Option A: PR required, 1 approval, code owners, no force-push/delete, **no bypass** |
| [`version-tag-protection.json`](version-tag-protection.json) | tags `v*` | No delete / no force-update; only **Admin** role may create/update |

## Before you import

1. Merge [`.github/CODEOWNERS`](../CODEOWNERS) (`* @chrisShick`) so code-owner review can resolve.
2. Let CI run once on a PR so status check names exist (see contexts below).
3. Collaborators: prefer **Triage** / **Read** unless they need write. Do not grant **Admin** casually — Admins can create `v*` tags under the tag ruleset.

## Status checks

Contexts must match job `name` values in [`.github/workflows/ci.yml`](../workflows/ci.yml):

- `Tests (PHP 8.2)` / `Tests (PHP 8.3)` / `Tests (PHP 8.4)`
- `PHPStan`
- `Content validation`
- `Eval`

If import fails on unknown checks, temporarily remove the `required_status_checks` rule, import, run CI on a PR, then edit the ruleset in the UI to re-add them.

## Solo-maintainer note (main)

With **no bypass** and **1 required approval**, GitHub normally blocks authors from approving their own PRs. Options if you get stuck merging:

1. Approve from a second trusted account, or
2. Temporarily add **Repository role → Admin** to `bypass_actors` with `bypass_mode: "pull_request"` (still requires a PR; allows merge without a second human), or
3. Lower `required_approving_review_count` to `0` while keeping the PR + status-check rules.

Option A intentionally starts with empty `bypass_actors`.

## Tag ruleset note

`update` + Admin bypass means only Admins can create `v*` tags. That same bypass also lets Admins override delete/force-push rules for those tags — keep Admin sparse. To forbid force-update/delete even for Admins, clear `bypass_actors` and remove the `update` rule (anyone with write could then *create* tags, but nobody could force-update or delete them).

## After import

- Push to `main` only via PR.
- Cut releases as annotated tags, e.g. `v1.0.0-beta.1`, then later `v1.0.0`.
