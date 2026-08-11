# CakePHP Agent

AI engineering knowledge for **CakePHP 5.x** — rules, skills, agents, and a safe installer for **Cursor**, **Claude Code**, and **Codex**.

> **Status: 0.9.0 adopter preview** (not 1.0). Docs/packaging slice is done. **Phase 11 next:** fill CakePHP rules/skills coverage gaps ([HANDOFF](docs/HANDOFF-phase-11.md), [coverage map](docs/coverage-rules-skills.md)). Composer name: **`chrisshick/cakephp-agent`**.

Repository: [github.com/chrisShick/cakephp-agent](https://github.com/chrisShick/cakephp-agent)

## What this is

Canonical CakePHP engineering knowledge (decisions, smells, workflows) delivered as editor rules/skills/agents — with behavioral evaluation fixtures for maintainers. Not “Laravel tips rewritten for CakePHP.”

Offline `eval` self-checks prove fixture/scorer plumbing. They are **not** live model quality scores.

## Requirements

- PHP 8.2+
- Composer 2
- CakePHP 5.x application (for install targets)

## Install into an existing CakePHP app

Follow **[docs/install-existing-app.md](docs/install-existing-app.md)**.

Quick start (until Packagist):

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

```bash
composer update chrisshick/cakephp-agent
vendor/bin/cakephp-agent doctor
vendor/bin/cakephp-agent install --editor=cursor --dry-run --verbose
vendor/bin/cakephp-agent install --editor=cursor
```

**Codex** gets rules/skills only (no agents directory).

## CLI

```bash
vendor/bin/cakephp-agent help
vendor/bin/cakephp-agent install --editor=cursor
vendor/bin/cakephp-agent uninstall --editor=all --dry-run
vendor/bin/cakephp-agent detect
vendor/bin/cakephp-agent extensions
vendor/bin/cakephp-agent explain
vendor/bin/cakephp-agent doctor
vendor/bin/cakephp-agent validate
vendor/bin/cakephp-agent eval
```

### Install options

| Flag | Meaning |
|---|---|
| `--editor=cursor\|claude\|codex\|all` | Target editor layout(s) |
| `--extension=ID` | Force-enable extension (repeatable) |
| `--without=ID` | Force-disable extension (repeatable) |
| `--force` | Overwrite managed / conflicting files |
| `--symlink` | Symlink package files instead of copying (Unix) |
| `--prune` | Remove previously managed files no longer present |
| `--dry-run` | Plan only |
| `--project=PATH` | Explicit project root |
| `--verbose` | Show skipped actions too |

### Safety

- Project-owned overlays live under **`.ai/`** and are never overwritten or uninstalled.
- Managed files are tracked in **`.cakephp-agent.lock.json`**.
- `--prune` / `uninstall` only delete paths recorded in that lock file.

## Configuration

Optional `composer.json`:

```json
{
  "extra": {
    "cakephp-agent": {
      "editor": "cursor",
      "extensions": {
        "enable": [],
        "disable": []
      }
    }
  }
}
```

Or `.cakephp-agent.json` in the project root (overrides `extra`).

Precedence: CLI flags > `.cakephp-agent.json` > `composer.json` extra > defaults.

## Documentation

| Doc | Topic |
|---|---|
| [Install into existing app](docs/install-existing-app.md) | Composer require, dry-run, upgrade, uninstall |
| [Editors](docs/editors.md) | Cursor / Claude / Codex paths |
| [`.ai` overlays](docs/ai-overlays.md) | Project-owned conventions |
| [Extension authoring](docs/extension-authoring.md) | Writing packs |
| [Troubleshooting](docs/troubleshooting.md) | Common failures |
| [Evaluation baselines](docs/evaluation-baselines.md) | Maintainer eval runner |
| [Architecture](docs/architecture.md) | Platform shape |
| [Pre-1.0 review](docs/pre-1.0-review.md) | Gap backlog |
| [Rules & skills coverage](docs/coverage-rules-skills.md) | CakePHP coverage audit |
| [Phase 11 handoff](docs/HANDOFF-phase-11.md) | Next agent: Wave A coverage fill |

## Package development

```bash
git clone https://github.com/chrisShick/cakephp-agent.git
cd cakephp-agent
composer install
composer test
composer phpstan
php bin/cakephp-agent validate
php bin/cakephp-agent eval
```

See [CONTRIBUTING.md](CONTRIBUTING.md).

## License

MIT
