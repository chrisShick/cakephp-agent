# Agent Handoff — Start Phase 6

> **For a new agent:** read this file first, then the unified plan Phase 6 section. Do not re-bootstrap Phases 0–5.

**Repo:** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**Package:** `chrisshick/cakephp-agent` (PHP 8.2+)  
**Status date:** 2026-08-10

---

## 1. Product reminder

Open, testable **AI engineering knowledge platform for CakePHP 5** — knowledge + rules/skills + Composer-aware extensions + evaluations. Editor files are adapters.

---

## 2. Completed through Phase 5

| Phase | Summary |
|---|---|
| 0–1 | Installer, adapters, lockfile safety |
| 2 | Extension engine + fake packs |
| 3 | Core knowledge/rules + eval seeds |
| 4 | 13 P0 CakePHP skills, ≥20 evals |
| 5 | FriendsOfCake CRUD reference extension (`friendsofcake-crud`) |

CRUD proof: detected on `^7.0`, absent on CakePHP-only, disabled via config, incompatible on 6.x.

---

## 3. Phase 6 — your assignment

### Goal

Ship **independent Authentication and Authorization extension packs** with clear identity vs authorization boundaries. Joint guidance only when both are present (integration pack OK if needed).

### Accept (unified plan)

- [x] AuthN-only install does **not** recommend AuthZ policy APIs
- [x] AuthZ-only does **not** assume Authentication plugin APIs incorrectly
- [x] IDOR / mass-assignment guidance present where relevant
- [x] Detection via Composer; no contamination when packages absent
- [x] Fixtures + tests for AuthN-only, AuthZ-only, both, disabled, incompatible as needed
- [x] `phpunit` + `phpstan` + `validate` green
- [x] Update architecture + handoff for Phase 7 (Search)

> **Phase 6 complete.** Continue at [HANDOFF-phase-7.md](HANDOFF-phase-7.md).

### Out of scope

- FriendsOfCake Search + CRUD↔Search integration (Phase 7)
- Live eval model runner
- Rewriting core skills unless a thin cross-link is required

### Constraints

1. Label truth levels carefully (PLUGIN_SEMANTIC vs recommendations).
2. Verify APIs against official CakePHP Authentication/Authorization docs for the targeted majors.
3. Disabled/undetected packs must not enter agent context.
4. `.ai/` still wins.

---

## 4. Kickoff prompt

```text
Read docs/HANDOFF-phase-6.md. Phases 0–5 are complete. Implement Phase 6 only:
Authentication and Authorization capability packs (independent; clear boundaries;
fixtures/tests; no cross-assumption when only one is installed).
```

---

## 5. Useful commands

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse --memory-limit=512M
php bin/cakephp-agent validate
php bin/cakephp-agent detect --project=tests/fixtures/projects/cakephp-crud
php bin/cakephp-agent install --editor=cursor --dry-run --project=tests/fixtures/projects/cakephp-crud
```
