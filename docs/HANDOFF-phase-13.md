# Agent Handoff — Start Phase 13

> **For a new agent:** read this file first, then [pre-1.0-review.md](pre-1.0-review.md). Do not re-bootstrap Phases 0–12.

**Repo:** https://github.com/chrisShick/cakephp-agent  
**Local path (if present):** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**Package:** `chrisshick/cakephp-agent` (PHP 8.2+) · CLI version **0.9.0**  
**Status date:** 2026-08-10

---

## 1. Already done (do not redo)

### Phases 0–11

Installer, extensions (CRUD/AuthN/AuthZ/Search + integrations), P0–Wave A skills/rules, agents, decision/smell intelligence, offline `eval` runner, 0.9.0 docs/packaging.

### Phase 12 Wave B + Wave C

**Wave B (core):** pagination, forms, views scope note, errors, configuration/DI, performance — rules + skills + decisions + evals.

**Wave C:**

| Pack | Notes |
|---|---|
| `cakephp-migrations` | Detect `cakephp/migrations` `^4 \|\| ^5`; rules/skills/decision; fixtures + incompatible path |
| `cakephp-bake` | Light Bake awareness (`^3`); generate-then-review |
| Mailer / Queues | Honesty evals only (no full packs yet) |

Coverage map updated: [coverage-rules-skills.md](coverage-rules-skills.md).

---

## 2. Phase 13 — your assignment (Packagist / 1.0)

### Goal

Move from **0.9.0 adopter preview** to a credible **1.0** public cut.

### Deliver

1. **Packagist** — publish `chrisshick/cakephp-agent` (human account; agent prepares checklist/docs/`composer.json` polish)
2. Triage [pre-1.0-review.md](pre-1.0-review.md) remaining items (done / deferred with reason)
3. Version bump strategy to **1.0.0** when accept bar is met
4. CHANGELOG / README status line for 1.0
5. Optional soak: install into a real CakePHP app and fix friction
6. Optional stretch only if asked: mailer/queue packs, deeper views

### Accept

- [ ] Packagist (or documented blocked-on-human) path clear
- [ ] pre-1.0 items triaged
- [ ] Version/tag plan for 1.0 documented; README reflects status
- [ ] `phpunit` + `phpstan` + `validate` + `eval` green
- [ ] Handoff for post-1.0 only if needed

### Out of scope

- Live LLM eval adapters
- MCP / remote registry
- Re-litigating Phases 0–12 coverage unless a 1.0 blocker

---

## 3. Kickoff prompt

```text
Read docs/HANDOFF-phase-13.md and docs/pre-1.0-review.md.
Phase 12 Wave B/C coverage is done. Prepare Packagist publish path and 1.0 cut.
Keep validate/phpunit/phpstan/eval green. Do not expand Wave C packs unless asked.
```

---

## 4. Useful commands

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse --memory-limit=512M
php bin/cakephp-agent validate
php bin/cakephp-agent eval
```
