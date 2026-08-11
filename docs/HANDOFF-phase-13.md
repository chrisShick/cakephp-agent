# Agent Handoff — Start Phase 13

> **For a new agent:** read this file first, then [coverage-rules-skills.md](coverage-rules-skills.md). Do not re-bootstrap Phases 0–12.

**Repo:** https://github.com/chrisShick/cakephp-agent  
**Local path (if present):** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**Package:** `chrisshick/cakephp-agent` (PHP 8.2+) · CLI version **0.9.0** · **Apache-2.0**  
**Status date:** 2026-08-10

---

## 1. Already done (do not redo)

Phases 0–12: installer, capability packs (CRUD/Auth/Search/Migrations/Bake), Waves A–C ownership coverage, decision/smell intelligence, offline eval, 0.9.0 docs, Apache-2.0 license.

**Packagist / declaring 1.0 is Phase 14** — after this phase.

---

## 2. Phase 13 — full CakePHP coverage + security/PHP base

### Goal

1. Close remaining CakePHP Book-shaped holes so “everyday CakePHP” has a rule/skill path (or an honest capability pack).
2. Then ship the five security/PHP priorities agreed for 1.0 trust.

### Track A — remaining CakePHP coverage (do first)

| Topic | Deliver |
|---|---|
| Sessions / cookies | Core rule + skill |
| Cache | Core rule + skill |
| Logging | Core rule + skill (or paired with cache if thin) |
| I18n | Core rule + skill |
| HTTP client | Core rule + skill |
| Request / response / flash | Core rule (+ skill if needed); deepen controllers lightly |
| Views / cells / helpers | Light skills beyond scope note (`create-cell` / `create-helper` or one `work-with-views`) |
| Mailer | Core rule + skill (`Cake\Mailer` — framework surface) |
| Queues | Capability pack for `cakephp/queue` when installed + absent honesty |
| DebugKit | Light capability pack when installed |
| Advanced ORM | Thin rule/skill for subquery/EXISTS/counter-cache ownership |

### Track B — five priorities (after Track A)

1. **P0** Skill `review-cakephp-security` + pos/neg evals  
2. **P0** Deepen `rules/cakephp/security.mdc` (secrets, open redirects, debug-in-prod)  
3. **P1** Expand `rules/php/` (typing, errors, input, `password_hash`, dangerous APIs, Composer hygiene)  
4. **P1** Skills `review-php-safety` + `apply-strict-types` under `skills/php/`  
5. **P2** Engineering: testing discipline + dependency honesty (`rules/engineering/` + thin skill if useful)

### Accept

- [x] Track A topics present (rules/skills/packs as above); coverage map updated to ✅/🟡 honestly
- [x] Track B five priorities done
- [x] New CakePHP skills reference `inspect-before-coding`; PHP skills follow same section contract
- [x] Critical boundaries have pos+neg evals; `php bin/cakephp-agent validate` + `eval` green
- [x] `vendor/bin/phpunit` + `vendor/bin/phpstan analyse --memory-limit=512M` green
- [x] README / architecture note Phase 13 → Phase 14
- [x] [HANDOFF-phase-14.md](HANDOFF-phase-14.md) Packagist / 1.0 written

> **Phase 13 complete.** Continue at [HANDOFF-phase-14.md](HANDOFF-phase-14.md).

### Out of scope

- Packagist account setup (Phase 14 / human)
- Live LLM eval adapters / MCP
- Turning rules into an AppSec textbook
- Re-litigating Waves A–C

---

## 3. Kickoff prompt

```text
Read docs/HANDOFF-phase-13.md and docs/coverage-rules-skills.md.
Implement Phase 13 Track A (remaining CakePHP coverage), then Track B
(security review skill, deeper security rule, PHP rules/skills, engineering P2).
Leave validate/phpunit/phpstan/eval green and a Phase 14 Packagist/1.0 handoff.
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
