# Agent Handoff — Start Phase 7

> **For a new agent:** read this file first, then the unified plan Phase 7 section. Do not re-bootstrap Phases 0–6.

**Repo:** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**Package:** `cakephp-agent/cakephp-agent` (PHP 8.2+)  
**Status date:** 2026-08-10

---

## 1. Completed through Phase 6

| Phase | Summary |
|---|---|
| 0–5 | Installer, extensions, core knowledge/skills, FriendsOfCake CRUD |
| 6 | Independent AuthN (`^4`) + AuthZ (`^3`) packs + auth integration pack |

Auth proof: AuthN-only has no AuthZ rules; AuthZ-only does not require Authentication APIs; both activate `cakephp-authentication-authorization`.

---

## 2. Phase 7 — your assignment

### Goal

Ship **FriendsOfCake Search** extension and the first **CRUD + Search integration pack** (`integrations/…`), proving non-duplication.

### Accept

- Search-only fixture → Search pack, no CRUD knowledge
- CRUD-only → CRUD pack, no Search knowledge
- Both present → both packs + integration (no duplicated conflicting guidance)
- Detection via Composer with supported majors; incompatible majors diagnosed
- `phpunit` + `phpstan` + `validate` green
- Update architecture + handoff for Phase 8 (agents)

### Out of scope

- Auth rewrites
- Live eval runner
- Full agent suite (Phase 8)

### Constraints

1. Integration activates only when both base extensions are enabled.
2. Verify Search/CRUD APIs against official docs for targeted majors.
3. No contamination when packages absent.
4. `.ai/` still wins.

---

## 3. Kickoff prompt

```text
Read docs/HANDOFF-phase-7.md. Phases 0–6 are complete. Implement Phase 7 only:
FriendsOfCake Search extension + CRUD↔Search integration pack with fixtures
for Search-only / CRUD-only / both. Prove non-duplication and clean detection.
```

---

## 4. Useful commands

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse --memory-limit=512M
php bin/cakephp-agent validate
php bin/cakephp-agent detect --project=tests/fixtures/projects/cakephp-auth-both
```
