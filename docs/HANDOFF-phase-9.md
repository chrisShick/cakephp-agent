# Agent Handoff — Start Phase 9

> **For a new agent:** read this file first, then the unified plan Phase 9 section. Do not re-bootstrap Phases 0–8.

**Repo:** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**Package:** `chrisshick/cakephp-agent` (PHP 8.2+)  
**Status date:** 2026-08-10

---

## 1. Completed through Phase 8

Installer, extensions (CRUD/Auth/Search + integrations), P0 skills, evaluations corpus, and capability-aware reviewer agents.

---

## 2. Phase 9 — your assignment

### Goal

**Decision intelligence hardening (M7):**

- Expand decision catalog where gaps remain
- Expand smell catalog toward the v1 set
- Skills: `detect-architectural-smells`, `review-abstraction-choice` (and related if planned)
- Evaluation coverage for each critical decision (positive + negative)

### Accept

- [x] New decisions/smells follow existing contracts + content validation
- [x] Skills reference `inspect-before-coding` / `choose-cakephp-abstraction`
- [x] Critical decisions have pos+neg evals
- [x] `phpunit` + `phpstan` + `validate` green
- [x] Handoff for Phase 10 (eval runner)

> **Phase 9 complete.** Continue at [HANDOFF-phase-10.md](HANDOFF-phase-10.md).

### Out of scope

- Live model eval runner (Phase 10)
- MCP / remote registry
- Mass unrelated refactors

---

## 3. Kickoff prompt

```text
Read docs/HANDOFF-phase-9.md. Phases 0–8 are complete. Implement Phase 9 only:
expand decisions/smells, add detect-architectural-smells and review-abstraction-choice,
and strengthen eval coverage for critical decisions.
```

---

## 4. Useful commands

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse --memory-limit=512M
php bin/cakephp-agent validate
php bin/cakephp-agent install --editor=cursor --dry-run --project=tests/fixtures/projects/cakephp-only
```
