# Agent Handoff — Start Phase 8

> **For a new agent:** read this file first, then the unified plan Phase 8 section. Do not re-bootstrap Phases 0–7.

**Repo:** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**Package:** `cakephp-agent/cakephp-agent` (PHP 8.2+)  
**Status date:** 2026-08-10

---

## 1. Completed through Phase 7

| Phase | Summary |
|---|---|
| 0–6 | Installer, core knowledge/skills, CRUD, AuthN/AuthZ |
| 7 | FriendsOfCake Search + `friendsofcake-crud-search` integration |

Search proof: Search-only has no CRUD; CRUD-only has no Search; both activate integration without duplicating filter ownership.

---

## 2. Phase 8 — your assignment

### Goal

Ship capability-aware **agents** for deeper reviews:

- `cakephp-code-reviewer`
- `cakephp-orm-reviewer`
- `cakephp-security-reviewer`
- `cakephp-architecture-reviewer`

Agents must **not** assume CRUD/Search/Auth packs unless those extensions are enabled for the project.

### Accept

- [x] Agents install via existing adapter paths when present
- [x] Capability-aware prompts (no phantom plugin APIs)
- [x] Tests/docs updated; validate/phpunit/phpstan green
- [x] Handoff for Phase 9 (decision intelligence) or next planned phase

> **Phase 8 complete.** Continue at [HANDOFF-phase-9.md](HANDOFF-phase-9.md).

### Out of scope

- Live eval model runner (later)
- Mass new P1 skill dump
- Remote registries / MCP

---

## 3. Kickoff prompt

```text
Read docs/HANDOFF-phase-8.md. Phases 0–7 are complete. Implement Phase 8 only:
capability-aware CakePHP reviewer agents that respect enabled extensions.
```

---

## 4. Useful commands

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse --memory-limit=512M
php bin/cakephp-agent validate
php bin/cakephp-agent detect --project=tests/fixtures/projects/cakephp-crud-search
```
