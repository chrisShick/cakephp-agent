# Agent Handoff — Phase 14b (1.0 quality polish)

> **For agents:** ORM-first + thin security/knowledge polish before stable 1.0. Packagist/beta path remains [HANDOFF-phase-14.md](HANDOFF-phase-14.md).

**Repo:** https://github.com/chrisShick/cakephp-agent  
**Package:** [`chrisshick/cakephp-agent`](https://packagist.org/packages/chrisshick/cakephp-agent) · CLI **1.0.0-beta.1** · **Apache-2.0**  
**Status date:** 2026-08-11

---

## Goal

Make stable **1.0.0** feel amazing without boiling the ocean (no MCP, live LLM evals, DB packs, or 200+ evals).

## Deliver (this slice)

1. [x] ORM-first mandate — `orm-vs-connection-sql` decision + philosophy/orm/tables rules
2. [x] Anti-patterns — `bypassing-orm-for-convenience`, `unsafe-sql-concatenation`
3. [x] Targeted ORM depth — advanced-orm expressions/`updateAll` lifecycle notes
4. [x] Thin security deepen — SQL concat eval + security rule/skill links
5. [x] Source freshness — bump `last_verified` on critical ORM/security rules (2026-08-11)
6. [x] Selective knowledge indexes — concepts/patterns/security/performance READMEs point at real units
7. [x] Evals — prefer ORM / reject Connection concat / QueryExpression / unbound SQL
8. [ ] Soak in a real CakePHP app → friction fixes
9. [ ] Cut stable `1.0.0` after soak

## Still post-1.0

Live LLM evals, MCP/remote registry, 200+ evals, DB packs, plugin-author mode, knowledge compiler, full ORM/AppSec/Bake/upgrade “expert programs.”

## Kickoff (remaining)

```text
Soak install of 1.0.0-beta.1 in a real CakePHP 5 app.
Fix friction, then cut v1.0.0.
```
