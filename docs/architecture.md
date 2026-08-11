# Architecture

See the unified plan for the full blueprint:

- [Unified plan](plans/cakephp-agent-unified-plan.md)
- [Original project plan](plans/cakephp-agent-project-plan.md)
- [Expansion plan](plans/cakephp-ai-knowledge-platform-expansion-plan.md)

## Agent handoff

**Starting Phase 6?** Read **[HANDOFF-phase-6.md](HANDOFF-phase-6.md)** first.

## Current phase

**Phases 0–5 complete** on `main`.

| Phase | Status |
|---|---|
| 0–1 Installer foundation | Done |
| 2 Extension engine | Done |
| 3 Canonical knowledge + core rules + eval seed | Done |
| 4 P0 skills | Done |
| 5 FriendsOfCake CRUD extension | Done |
| **6 AuthN / AuthZ** | **Next** |

Phase 5 delivered:

- `extensions/friendsofcake-crud/` — detect `friendsofcake/crud` `^7.0`
- Rules, skills, decision `crud-listener-vs-orm-callback`, CRUD evals
- Fixtures: `cakephp-crud`, `cakephp-crud-incompatible` (scenarios B/D/F + CakePHP-only clean)
