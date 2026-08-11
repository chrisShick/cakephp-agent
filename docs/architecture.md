# Architecture

See the unified plan for the full blueprint:

- [Unified plan](plans/cakephp-agent-unified-plan.md)
- [Original project plan](plans/cakephp-agent-project-plan.md)
- [Expansion plan](plans/cakephp-ai-knowledge-platform-expansion-plan.md)

## Current phase

**Phase 0–1 complete.** Phase 2 extension engine is in progress / landed:

- Extension manifests + loader/validator
- Composer detection with semver constraints
- Enable/disable, dependsOn, conflicts, cycles
- `detect` / `extensions` / `explain` CLI
- Fake reference extensions: `test-fake-plugin`, `test-fake-addon`
- Installer installs enabled extension rules/skills under `extensions/<id>/`

**Next:** Phase 3 — canonical knowledge + CakePHP core rules (source-verified).
