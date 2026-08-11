# Architecture

See the unified plan for the full blueprint:

- [Unified plan](plans/cakephp-agent-unified-plan.md)
- [Original project plan](plans/cakephp-agent-project-plan.md)
- [Expansion plan](plans/cakephp-ai-knowledge-platform-expansion-plan.md)

## Agent handoff

**Starting Phase 7?** Read **[HANDOFF-phase-7.md](HANDOFF-phase-7.md)** first.

## Current phase

**Phases 0–6 complete** on `main`.

| Phase | Status |
|---|---|
| 0–1 Installer foundation | Done |
| 2 Extension engine | Done |
| 3 Canonical knowledge + core rules + eval seed | Done |
| 4 P0 skills | Done |
| 5 FriendsOfCake CRUD extension | Done |
| 6 AuthN / AuthZ | Done |
| **7 Search + CRUD↔Search integration** | **Next** |

Phase 6 delivered:

- `extensions/cakephp-authentication/` — `cakephp/authentication` `^4.0`
- `extensions/cakephp-authorization/` — `cakephp/authorization` `^3.0`
- `integrations/cakephp-authentication-authorization/` — activates only when both packs enabled
- Fixtures: AuthN-only, AuthZ-only, both, AuthN incompatible; evals for cross-assumption + IDOR
