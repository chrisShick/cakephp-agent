# Architecture

See the unified plan for the full blueprint:

- [Unified plan](plans/cakephp-agent-unified-plan.md)
- [Original project plan](plans/cakephp-agent-project-plan.md)
- [Expansion plan](plans/cakephp-ai-knowledge-platform-expansion-plan.md)

## Agent handoff

**Starting Phase 10?** Read **[HANDOFF-phase-10.md](HANDOFF-phase-10.md)** first.

## Current phase

**Phases 0–9 complete** on `main`.

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
| **10 Evaluation platform** | **Next** |

Phase 9 delivered:

- Expanded core decisions (service/event/transaction/join/middleware boundaries)
- Smell catalog expanded beyond the v1 minimum (god-* / duplication / exposure / lifecycle opacity)
- Skills: `detect-architectural-smells`, `review-abstraction-choice`
- Positive + negative eval coverage for critical/high decisions
