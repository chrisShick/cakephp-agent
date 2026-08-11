# Architecture

See the unified plan for the full blueprint:

- [Unified plan](plans/cakephp-agent-unified-plan.md)
- [Original project plan](plans/cakephp-agent-project-plan.md)
- [Expansion plan](plans/cakephp-ai-knowledge-platform-expansion-plan.md)

## Current phase

**Phase 0–1 complete in skeleton form:**

- Composer package + CLI
- Project root / Composer metadata discovery
- Editor adapters: Cursor, Claude Code, Codex
- Managed-file installer with lock state, dry-run, force, symlink, prune
- `.ai/` project overlay reserved and never overwritten
- Stub engineering / PHP / CakePHP convention rules

**Next:** Phase 2 — extension manifests, detection, enable/disable, fake extension + fixture matrix.
