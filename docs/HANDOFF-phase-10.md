# Agent Handoff — Start Phase 10

> **For a new agent:** read this file first, then the unified plan Phase 10 section. Do not re-bootstrap Phases 0–9.

**Repo:** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**Package:** `chrisshick/cakephp-agent` (PHP 8.2+)  
**Status date:** 2026-08-10

---

## 1. Completed through Phase 9

Installer, extensions, P0 skills, capability-aware reviewer agents, expanded decision/smell catalogs, decision-intelligence skills, and strengthened pos+neg eval coverage for critical decisions (M7).

---

## 2. Phase 10 — your assignment

### Goal

**Evaluation platform (M11 track):**

- `cakephp-agent eval` runner
- Baselines by knowledge version + model
- Grow toward 200+ scenarios (anti-Laravel suite depth)
- Regression reporting

**Note (unified plan):** Can overlap docs/1.0; must not block **v1.0** if ≥50 evals + schema validation already ship (already true after Phase 9).

### Accept

- [x] Eval runner CLI with deterministic fixture loading
- [x] Baseline/report format documented
- [x] Tests for runner plumbing (not live model calls required for MVP)
- [x] `phpunit` + `phpstan` + `validate` green
- [x] Handoff for Phase 11 (docs & public 1.0)

> **Phase 10 complete.** Continue at [HANDOFF-phase-11.md](HANDOFF-phase-11.md).

### Out of scope

- MCP / remote registry
- Replacing curated fixtures with only live LLM scores
- Mass unrelated refactors

---

## 3. Kickoff prompt

```text
Read docs/HANDOFF-phase-10.md. Phases 0–9 are complete. Implement Phase 10 only:
cakephp-agent eval runner, baselines, and regression reporting for the evaluations corpus.
```

---

## 4. Useful commands

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse --memory-limit=512M
php bin/cakephp-agent validate
php bin/cakephp-agent eval
php bin/cakephp-agent help
```
