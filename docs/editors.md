# Editor install layouts

`cakephp-agent install` copies (or symlinks) core and enabled-extension rules, skills, and agents into editor-specific trees under your **application** root. It never writes into your app `src/`, config, or templates.

Managed paths are recorded in **`.cakephp-agent.lock.json`** so later `install` / `uninstall` / `--prune` runs stay safe.

## Where install puts files

| Editor | Rules | Skills | Agents |
|---|---|---|---|
| **Cursor** | `.cursor/rules/cakephp-agent/` | `.cursor/skills/cakephp-agent/` | `.cursor/agents/cakephp-agent/` |
| **Claude Code** | `.claude/rules/cakephp-agent/` | `.claude/skills/cakephp-agent/` | `.claude/agents/cakephp-agent/` |
| **Codex** | `.codex/rules/cakephp-agent/` | `.codex/skills/cakephp-agent/` | *(not supported)* |

Codex’s adapter reports `supportsAgents() === false` and returns no agents directory. Agent markdown from the package is installed for Cursor and Claude only.

### Commands

```bash
vendor/bin/cakephp-agent install --editor=cursor
vendor/bin/cakephp-agent install --editor=claude
vendor/bin/cakephp-agent install --editor=codex
vendor/bin/cakephp-agent install --editor=all
```

Default editor when `--editor` is omitted: **cursor**.

## Re-run, force, prune, symlink

| Flag | Behavior |
|---|---|
| *(normal re-install)* | Creates missing files; updates managed files that still match the lock hash (upstream changed); **preserves** locally edited managed files |
| `--force` | Overwrite managed or conflicting unmanaged targets |
| `--prune` | Delete lock-tracked paths that are no longer produced by this install |
| `--symlink` | Symlink package sources instead of copying (see Windows note) |
| `--dry-run` | Plan only — no writes |
| `--verbose` | Include skip/preserve actions in output |

Typical upgrade:

```bash
composer update cakephp-agent/cakephp-agent
vendor/bin/cakephp-agent install --editor=cursor
# optional after package removed files:
vendor/bin/cakephp-agent install --editor=cursor --prune
```

Preview first:

```bash
vendor/bin/cakephp-agent install --editor=all --dry-run --verbose
```

## Symlinks and Windows

`--symlink` uses PHP’s `symlink()`. On Unix this is usually fine for local package development. On Windows, creating symlinks often requires **Developer Mode** or an elevated shell; otherwise install fails with an unable-to-symlink error. Prefer the default copy mode on Windows unless you know symlinks work in your environment.

## Project overlays

Paths under **`.ai/`** are project-owned. The installer never overwrites or uninstalls them. See [ai-overlays.md](ai-overlays.md).

## Related

- [Install into an existing app](install-existing-app.md)
- [Troubleshooting](troubleshooting.md)
