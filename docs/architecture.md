# Architecture

See the unified plan for the full blueprint:

- [Unified plan](plans/cakephp-agent-unified-plan.md)
- [Original project plan](plans/cakephp-agent-project-plan.md)
- [Expansion plan](plans/cakephp-ai-knowledge-platform-expansion-plan.md)

## Agent handoff

**Starting Phase 4?** Read **[HANDOFF-phase-4.md](HANDOFF-phase-4.md)** first (completed work, constraints, kickoff prompt).

## Current phase

**Phases 0–3 complete** on `main` (through `c90043c`).

| Phase | Status |
|---|---|
| 0–1 Installer foundation | Done |
| 2 Extension engine | Done |
| 3 Canonical knowledge + core rules + eval seed | Done |
| **4 P0 skills** | **Next** |
| 5 FriendsOfCake CRUD extension | Later |

Phase 3 delivered:

- `knowledge/decisions/` v1 decision catalog (8 units)
- `knowledge/anti-patterns/` (12 smells)
- CakePHP core rules with `truth_level`, `priority`, and source provenance
- `evaluations/` seed (17 scenarios) + schemas
- `cakephp-agent validate` content lint + CI `content-validation` job

**Skills are still empty** — Phase 4 owns `skills/cakephp/*/SKILL.md`.
