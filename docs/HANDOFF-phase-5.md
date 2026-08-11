# Agent Handoff — Start Phase 5

> **For a new agent:** read this file first, then the unified plan Phase 5 section. Do not re-bootstrap Phases 0–4.

**Repo:** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**Package:** `cakephp-agent/cakephp-agent` (PHP 8.2+)  
**Status date:** 2026-08-10

---

## 1. One-sentence product

Open, testable **AI engineering knowledge platform for CakePHP 5** — canonical knowledge + rules/skills + Composer-aware extensions + behavioral evaluations — delivered via Cursor / Claude Code / Codex adapters.

**Not** “Cursor rules for CakePHP.” Editor files are outputs; knowledge + evals are the product.

---

## 2. Authoritative docs

| Doc | Role |
|---|---|
| [docs/plans/cakephp-agent-unified-plan.md](plans/cakephp-agent-unified-plan.md) | **Source of truth** |
| [docs/plans/cakephp-agent-project-plan.md](plans/cakephp-agent-project-plan.md) | Installer/extension detail |
| [docs/plans/cakephp-ai-knowledge-platform-expansion-plan.md](plans/cakephp-ai-knowledge-platform-expansion-plan.md) | Knowledge/eval detail |
| [docs/architecture.md](architecture.md) | Short current-phase pointer |
| [docs/HANDOFF-phase-4.md](HANDOFF-phase-4.md) | Prior phase (skills) — historical |
| This file | Pickup status for Phase 5 |

---

## 3. Completed phases (summary)

| Phase | Summary |
|---|---|
| 0–1 | Composer package, CLI, editor adapters, safe installer + lockfile |
| 2 | Extension manifests, Composer/semver detection, fake extensions |
| 3 | Canonical decisions + anti-patterns, CakePHP core rules, eval seeds |
| 4 | 13 P0 CakePHP skills, ≥20 evals, skill content validation |

Working tree should be clean on `main` after Phase 4 lands.

---

## 4. Phase 5 — your assignment

### Goal

Ship the **FriendsOfCake CRUD reference extension** — architectural proof that real plugin packs install only when detected, teach plugin-correct semantics, and stay absent when CRUD is missing.

### Deliver (from unified plan)

- Extension pack under `extensions/` (id TBD, typically `friendsofcake-crud`)
- Rules + skills scoped to CRUD (listener conventions as **recommendations**, labeled)
- Decision unit(s): e.g. `crud-listener-vs-orm-callback` (deferred from Phase 3 core)
- Fixtures: CakePHP+CRUD compatible, incompatible major, CRUD disabled
- Extension-scoped evaluations
- Acceptance scenarios B / D / F from the unified plan

### Explicitly out of scope

- AuthN/AuthZ (Phase 6)
- Search + integration pack (Phase 7)
- Live model eval runner
- Core skill rewrite (only extend if CRUD requires a thin cross-link)

### Design constraints (do not regress)

1. CRUD knowledge **must not** contaminate CakePHP-only installs.
2. Label `truth_level`: PLUGIN_SEMANTIC vs PACKAGE_RECOMMENDATION vs PROJECT_CONVENTION.
3. No invented CRUD APIs — verify against FriendsOfCake CRUD docs/source.
4. Disabled/undetected extensions stay out of agent context.
5. Project `.ai/` still wins.

---

## 5. Acceptance checklist (Phase 5 done when)

- [ ] CRUD extension manifest + detection for supported versions
- [ ] Rules/skills/decisions/evals for CRUD pack
- [ ] Scenario B: CRUD present → pack installed
- [ ] Scenario D: CRUD present but disabled → no CRUD knowledge
- [ ] Scenario F: unsupported CRUD major → diagnose, do not install incompatible rules
- [ ] CakePHP-only fixture still has **no** CRUD skills/rules
- [ ] `phpunit` + `phpstan` + `cakephp-agent validate` green
- [ ] `docs/architecture.md` → Phase 5 complete → Phase 6
- [ ] New `HANDOFF-phase-6.md` (or update this file)

---

## 6. Kickoff prompt

```text
You are continuing cakephp-agent. Read docs/HANDOFF-phase-5.md first.

Phases 0–4 are complete. Implement Phase 5 only: FriendsOfCake CRUD reference extension
(detection, rules/skills/decisions/evals, fixtures for B/D/F). Do not contaminate core
CakePHP installs with CRUD APIs. Verify against official CRUD docs.
```

---

## 7. Useful commands

```bash
cd /Users/chrishickingbottom/Development/cakephp-agent
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse --memory-limit=512M
php bin/cakephp-agent validate
php bin/cakephp-agent install --editor=cursor --dry-run
php bin/cakephp-agent detect --project=tests/fixtures/projects/cakephp-fake
```
