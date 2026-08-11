# CakePHP Agent

AI engineering knowledge platform for **CakePHP 5.x** — rules, skills, extensions, and a safe installer for Cursor, Claude Code, and Codex.

> Status: early development (Phases 0–4). P0 CakePHP skills and evaluation corpus (≥20) are in place. FriendsOfCake CRUD extension is next.

## What this is

Not “Cursor rules for CakePHP.” The product is **canonical CakePHP engineering knowledge** plus evidence (evaluations) that agents reason correctly. Editor rule files are delivery adapters.

## Requirements

- PHP 8.2+
- Composer 2

## Install (development)

```bash
git clone <repo> cakephp-agent
cd cakephp-agent
composer install
```

## CLI

```bash
vendor/bin/cakephp-agent help
vendor/bin/cakephp-agent install --editor=cursor
vendor/bin/cakephp-agent install --editor=all --dry-run
vendor/bin/cakephp-agent detect
vendor/bin/cakephp-agent extensions
vendor/bin/cakephp-agent explain
vendor/bin/cakephp-agent doctor
vendor/bin/cakephp-agent validate
```

### Install options

| Flag | Meaning |
|---|---|
| `--editor=cursor\|claude\|codex\|all` | Target editor layout(s) |
| `--extension=ID` | Force-enable extension (repeatable) |
| `--without=ID` | Force-disable extension (repeatable) |
| `--force` | Overwrite managed / conflicting files |
| `--symlink` | Symlink package files instead of copying |
| `--prune` | Remove previously managed files no longer present |
| `--dry-run` | Plan only |
| `--project=PATH` | Explicit project root |
| `--verbose` | Show skipped actions too |

### Safety

- Project-owned overlays live under **`.ai/`** and are never overwritten.
- Managed files are tracked in **`.cakephp-agent.lock.json`**.
- `--prune` only deletes paths recorded in that lock file.

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

## Architecture (short)

```text
knowledge/ (canonical — growing)
    ↓
rules/ + skills/ + agents/
    ↓
installer + editor adapters
    ↓
Cursor / Claude / Codex + .ai project overlays
```

See [docs/plans/cakephp-agent-unified-plan.md](docs/plans/cakephp-agent-unified-plan.md).

**New agent / Phase 5 pickup:** [docs/HANDOFF-phase-5.md](docs/HANDOFF-phase-5.md)

## Development

```bash
composer test
composer phpstan
composer check
```

## License

MIT
