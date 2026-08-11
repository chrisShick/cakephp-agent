# Architecture

See the unified plan for the full blueprint:

- [Unified plan](plans/cakephp-agent-unified-plan.md)
- [Original project plan](plans/cakephp-agent-project-plan.md)
- [Expansion plan](plans/cakephp-ai-knowledge-platform-expansion-plan.md)

## Agent handoff

**Starting Phase 8?** Read **[HANDOFF-phase-8.md](HANDOFF-phase-8.md)** first.

## Current phase

**Phases 0–7 complete** on `main`.

| Phase | Status |
|---|---|
| 0–1 Installer foundation | Done |
| 2 Extension engine | Done |
| 3 Canonical knowledge + core rules + eval seed | Done |
| 4 P0 skills | Done |
| 5 FriendsOfCake CRUD extension | Done |
| 6 AuthN / AuthZ | Done |
| 7 Search + CRUD↔Search integration | Done |
| **8 Agents + deeper reviews** | **Next** |

Phase 7 delivered:

- `extensions/friendsofcake-search/` — `friendsofcake/search` `^7.0`
- `integrations/friendsofcake-crud-search/` — activates only when CRUD + Search packs enabled
- Fixtures: Search-only, CRUD+Search, Search incompatible; evals for non-duplication
