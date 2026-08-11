# Architecture

See the unified plan for the full blueprint:

- [Unified plan](plans/cakephp-agent-unified-plan.md)
- [Original project plan](plans/cakephp-agent-project-plan.md)
- [Expansion plan](plans/cakephp-ai-knowledge-platform-expansion-plan.md)

## Agent handoff

**Starting Phase 11?** Read **[HANDOFF-phase-11.md](HANDOFF-phase-11.md)** first.

## Current phase

**Phases 0–10 complete** on `main`.

| Phase | Status |
|---|---|
| 0–1 Installer foundation | Done |
| 2 Extension engine | Done |
| 3 Canonical knowledge + core rules + eval seed | Done |
| 4 P0 skills | Done |
| 5 FriendsOfCake CRUD extension | Done |
| 6 AuthN / AuthZ | Done |
| 7 Search + CRUD↔Search integration | Done |
| 8 Agents + deeper reviews | Done |
| 9 Decision intelligence hardening | Done |
| 10 Evaluation platform | Done |
| **11 Docs & public 1.0** | **Next** |

Phase 10 delivered:

- `cakephp-agent eval` offline runner (deterministic catalog load + heuristic self-check)
- Baseline write/compare (`docs/evaluation-baselines.md`)
- Anti-Laravel suite expansion
- Tests for runner plumbing (no live model calls)
