# Add CakePHP Agent to an existing CakePHP application

This guide is for developers who already have a **CakePHP 5.x** app and want AI coding tools (Cursor, Claude Code, Codex) to follow CakePHP-native conventions.

> **Status:** Public beta **1.0.0-beta.1** — not stable 1.0 yet. Composer package: [`chrisshick/cakephp-agent`](https://packagist.org/packages/chrisshick/cakephp-agent) (lowercase vendor required by Composer); GitHub stays `chrisShick/cakephp-agent`.

## Prerequisites

- PHP 8.2+
- Composer 2
- A CakePHP 5 application root (the directory with `composer.json` / `config/`)
- At least one supported editor: **Cursor**, **Claude Code**, or **Codex**

## 1. Require the package

Prefer `require-dev` unless you intentionally want the package available in production deploys (usually you do not).

### Option A — Packagist (recommended)

```bash
composer require --dev chrisshick/cakephp-agent:^1.0@beta
```

Pin an exact beta if you prefer: `chrisshick/cakephp-agent:1.0.0-beta.1`.  
After stable `1.0.0`: `composer require --dev chrisshick/cakephp-agent:^1.0`.

### Option B — VCS (GitHub)

In your **application** `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/chrisShick/cakephp-agent.git"
    }
  ],
  "require-dev": {
    "chrisshick/cakephp-agent": "dev-main"
  }
}
```

Then:

```bash
composer update chrisshick/cakephp-agent
```

### Option C — Path repository (local clone)

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../cakephp-agent",
      "options": { "symlink": true }
    }
  ],
  "require-dev": {
    "chrisshick/cakephp-agent": "*"
  }
}
```

```bash
composer update chrisshick/cakephp-agent
```

### Confirm the binary

From the application root:

```bash
vendor/bin/cakephp-agent version
vendor/bin/cakephp-agent doctor
```

## 2. Preview what will be installed

Always dry-run first:

```bash
vendor/bin/cakephp-agent detect
vendor/bin/cakephp-agent explain
vendor/bin/cakephp-agent install --editor=cursor --dry-run --verbose
```

`detect` / `explain` show which **extension packs** will activate from Composer (Authentication, Authorization, FriendsOfCake CRUD, Search, and integrations).

## 3. Install into your editor

```bash
# Cursor only
vendor/bin/cakephp-agent install --editor=cursor

# Claude Code only
vendor/bin/cakephp-agent install --editor=claude

# Codex only (rules + skills; Codex has no agents path)
vendor/bin/cakephp-agent install --editor=codex

# All supported editors
vendor/bin/cakephp-agent install --editor=all
```

If the app root is not the current directory:

```bash
vendor/bin/cakephp-agent install --editor=cursor --project=/path/to/app
```

Deep dive on paths, re-run / `--force` / `--prune` / `--symlink`, and the Codex agents gap: **[editors.md](editors.md)**.

### Where files land (summary)

| Editor | Rules | Skills | Agents |
|---|---|---|---|
| Cursor | `.cursor/rules/cakephp-agent/` | `.cursor/skills/cakephp-agent/` | `.cursor/agents/cakephp-agent/` |
| Claude | `.claude/rules/cakephp-agent/` | `.claude/skills/cakephp-agent/` | `.claude/agents/cakephp-agent/` |
| Codex | `.codex/rules/cakephp-agent/` | `.codex/skills/cakephp-agent/` | *(not supported)* |

Core CakePHP rules/skills plus any **enabled extension** packs are installed under those trees. The installer also writes **`.cakephp-agent.lock.json`** so later runs can update/prune/uninstall safely.

The installer does **not** modify your application `src/`, config, or templates.

## 4. Optional configuration

### `composer.json` extra

```json
{
  "extra": {
    "cakephp-agent": {
      "editor": "cursor",
      "extensions": {
        "enable": [],
        "disable": ["friendsofcake-crud"]
      }
    }
  }
}
```

### Or `.cakephp-agent.json` in the app root

```json
{
  "editor": "cursor",
  "extensions": {
    "enable": ["friendsofcake-search"],
    "disable": []
  }
}
```

Precedence: **CLI flags** > `.cakephp-agent.json` > `composer.json` extra > defaults.

Force packs on/off for one install:

```bash
vendor/bin/cakephp-agent install --editor=cursor --extension=friendsofcake-crud --without=friendsofcake-search
```

## 5. Project overlays (`.ai/`)

Put **project-specific** architecture notes and conventions under **`.ai/`** (for example `.ai/architecture.md`). Paths under `.ai/` are **never overwritten or uninstalled**.

See **[ai-overlays.md](ai-overlays.md)** and the sample at [`examples/ai-overlay/architecture.md`](../examples/ai-overlay/architecture.md).

## 6. Git hygiene (recommended)

For team consistency, **commit**:

- `.cursor/rules/cakephp-agent/` (and/or Claude/Codex equivalents)
- `.cursor/skills/cakephp-agent/`
- `.cursor/agents/cakephp-agent/` (if using Cursor/Claude)
- `.cakephp-agent.lock.json`
- `.ai/` overlays

Ignore only if your team regenerates editor trees in CI for every clone (unusual).

## 7. Day-to-day use

1. Open the app in your editor.
2. Prefer CakePHP Agent skills/agents when implementing or reviewing CakePHP code.
3. Keep Composer accurate — packs follow installed plugins; don’t document CRUD APIs if CRUD isn’t installed.

Useful commands:

```bash
vendor/bin/cakephp-agent detect
vendor/bin/cakephp-agent extensions
vendor/bin/cakephp-agent explain
vendor/bin/cakephp-agent doctor
```

## 8. Upgrade

```bash
composer update chrisshick/cakephp-agent
vendor/bin/cakephp-agent install --editor=cursor
```

| Situation | Flag |
|---|---|
| Package files changed; local copies untouched | normal re-install updates them |
| You edited a managed file and want to keep it | default **preserves**; use `--force` only to overwrite |
| Package removed some files | add `--prune` to delete lock-tracked stale paths |
| Preview first | `--dry-run --verbose` |

## 9. Uninstall

```bash
# Preview
vendor/bin/cakephp-agent uninstall --dry-run

# Remove lock-tracked managed editor files (all editors in the lock by default)
vendor/bin/cakephp-agent uninstall

# Or limit to one editor
vendor/bin/cakephp-agent uninstall --editor=cursor

# Then remove the Composer package
composer remove chrisshick/cakephp-agent
```

`.ai/` overlays are left untouched. Empty editor directories may remain — delete them manually if desired. See [troubleshooting.md](troubleshooting.md).

## 10. Troubleshooting

Common issues (wrong project root, doctor warnings, preserved files, Codex agents, leftover trees, symlink failures) are covered in **[troubleshooting.md](troubleshooting.md)**.

Quick checks:

| Symptom | What to try |
|---|---|
| Wrong project detected | Pass `--project=/path/to/app` |
| File skipped / preserved | Local edit or unmanaged file; use `--force` only if overwrite is intentional |
| Extension missing | Confirm the Composer package is installed; run `explain` |
| Codex has no agents | Expected — Codex adapter has no agents directory |
| Binary not found | Run Composer from the app root; ensure `vendor/bin/cakephp-agent` exists |

## What this is not

- Not a CakePHP runtime plugin — it does not boot inside your HTTP app.
- Not a substitute for reading CakePHP docs.
- Offline `eval` / `validate` are for **package maintainers**; adopters usually only need `install` / `detect` / `doctor` / `uninstall`.

## Next reading

- [Editors](editors.md)
- [AI overlays](ai-overlays.md)
- [Troubleshooting](troubleshooting.md)
- [Extension authoring](extension-authoring.md) (maintainers / pack authors)
- [Architecture overview](architecture.md)
- [Evaluation baselines](evaluation-baselines.md) (maintainers)
- [Pre-1.0 review & improvement backlog](pre-1.0-review.md)
