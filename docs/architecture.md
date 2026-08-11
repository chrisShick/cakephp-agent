# Architecture

See the unified plan for the full blueprint:

- [Unified plan](plans/cakephp-agent-unified-plan.md)
- [Original project plan](plans/cakephp-agent-project-plan.md)
- [Expansion plan](plans/cakephp-ai-knowledge-platform-expansion-plan.md)

## Agent handoff

**Starting Phase 9?** Read **[HANDOFF-phase-9.md](HANDOFF-phase-9.md)** first.

## Current phase

**Phases 0–8 complete** on `main`.

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
| **9 Decision intelligence hardening** | **Next** |

Phase 8 delivered:

- Core agents: `cakephp-code-reviewer`, `cakephp-orm-reviewer`, `cakephp-security-reviewer`, `cakephp-architecture-reviewer`
- Capability gates (no phantom CRUD/Search/Auth APIs)
- Content validation + installer coverage for agents/
