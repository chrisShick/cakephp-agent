# Troubleshooting

## Wrong project root

The CLI walks upward from the current working directory (or `--project`) until it finds a `composer.json`.

| Symptom | Fix |
|---|---|
| Files land in a parent monorepo / wrong package | Run from the CakePHP **app** root, or pass `--project=/path/to/app` |
| `Unable to locate a Composer project root` | Ensure `composer.json` exists; path must be a real directory |
| Doctor shows unexpected CakePHP / extensions | Confirm `Project root:` in `doctor` / `detect` output |

```bash
vendor/bin/cakephp-agent doctor --project=/path/to/app
vendor/bin/cakephp-agent install --editor=cursor --project=/path/to/app --dry-run
```

## Doctor warnings

`vendor/bin/cakephp-agent doctor` exits non-zero when health checks fail.

| Output | Meaning | What to try |
|---|---|---|
| `WARNING: N lock-tracked path(s) missing on disk` | Lock expects managed files that were deleted | `install --editor=all` (or the editors in the lock) |
| `Lock file: not present` | Never installed (or fully uninstalled) | `install --editor=cursor --dry-run --verbose` then install |
| `CakePHP: (not detected)` | No `cakephp/cakephp` in installed packages for this root | Wrong root, or run Composer install in the app |
| `validate: FAILED` | Package content validation errors (usually while developing the package) | Fix reported issues; adopters should rarely see this on a released tree |
| `Codex agents: unsupported` | Informational | Expected — Codex gets rules/skills only |

Doctor also prints a **Suggested next step** (dry-run install, re-install, or `detect`).

## Preserved / skipped files

| Action | Meaning |
|---|---|
| **preserve** | Local edits to a managed file, or an unmanaged file in the way — not overwritten without `--force` |
| **skip** | Already up to date vs package source |
| **preserve** under `.ai/` | Always — project-owned |

```bash
vendor/bin/cakephp-agent install --editor=cursor --dry-run --verbose
# only if you intend to discard local edits to managed paths:
vendor/bin/cakephp-agent install --editor=cursor --force
```

## Codex has no agents

Expected. The Codex adapter does not define an agents directory. Use Cursor or Claude if you need packaged agents; Codex still receives rules and skills under `.codex/`.

## Uninstall leftovers

`uninstall` removes **lock-tracked files** and deletes `.cakephp-agent.lock.json` when nothing remains. It does **not** remove `.ai/`.

Empty parent directories (e.g. `.cursor/rules/cakephp-agent/`) may remain after file deletion. Safe to delete manually if empty:

- `.cursor/rules/cakephp-agent/`, `.cursor/skills/cakephp-agent/`, `.cursor/agents/cakephp-agent/`
- `.claude/…/cakephp-agent/`
- `.codex/…/cakephp-agent/`

```bash
vendor/bin/cakephp-agent uninstall --dry-run
vendor/bin/cakephp-agent uninstall
composer remove cakephp-agent/cakephp-agent
```

## Symlink failures

`--symlink` fails when the OS/PHP cannot create the link (common on Windows without Developer Mode / elevation). Drop `--symlink` and use the default copy install.

```bash
vendor/bin/cakephp-agent install --editor=cursor
```

## Extension missing or unexpected

| Symptom | What to try |
|---|---|
| Pack not enabled | Confirm the Composer package + version; run `explain` |
| Pack force-disabled | Check `.cakephp-agent.json` / `composer.json` `extra.cakephp-agent.extensions.disable` / `--without=` |
| Want pack without Composer detect | `--extension=ID` (or config `enable`) |
| Incompatible version | `explain` shows incompatible — upgrade/downgrade the plugin or disable the pack |

```bash
vendor/bin/cakephp-agent detect
vendor/bin/cakephp-agent explain
vendor/bin/cakephp-agent extensions
```

## Binary not found

Run Composer from the application root so `vendor/bin/cakephp-agent` exists. Prefer `require-dev` unless you intentionally ship the package in production.

## Related

- [Install guide](install-existing-app.md)
- [Editors](editors.md)
- [AI overlays](ai-overlays.md)
