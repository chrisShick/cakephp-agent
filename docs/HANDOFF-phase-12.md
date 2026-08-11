# Agent Handoff — Start Phase 12

> **For a new agent:** read this file first, then [coverage-rules-skills.md](coverage-rules-skills.md). Do not re-bootstrap Phases 0–11.

**Repo:** https://github.com/chrisShick/cakephp-agent  
**Local path (if present):** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**Package:** `chrisshick/cakephp-agent` (PHP 8.2+) · CLI version **0.9.0**  
**Status date:** 2026-08-10

---

## 1. Already done (do not redo)

### Phases 0–10

Installer, extensions (CRUD/AuthN/AuthZ/Search + integrations), P0 skills, agents, decision/smell intelligence, offline `eval` runner.

### Phase 11 docs / packaging slice

Adopter preview **0.9.0** on GitHub (`v0.9.0`): install/docs/trust packaging, `chrisshick/cakephp-agent` rename.

### Phase 11 Wave A CakePHP coverage

Everyday ownership holes closed:

| Topic | Delivered |
|---|---|
| Routing | `rules/cakephp/routing.mdc` + `add-route` / `review-routing` |
| Behaviors | `behaviors.mdc` + `create-behavior` |
| Components | `components.mdc` + `create-component` |
| Commands | `commands.mdc` + `create-command` |
| Transactions | `transactions.mdc` + `add-transaction` |
| Security depth | Expanded `security.mdc` + CSRF/upload anti-patterns + evals |
| Testing depth | Expanded `testing.mdc` + `write-table-test` |

Also: decisions `route-config-vs-controller-url-logic`, `command-vs-controller-action`; `choose-cakephp-abstraction` updated; coverage map marked Wave A done.

**Not your primary job:** Packagist publish / declaring 1.0 — that is **Phase 13** ([HANDOFF-phase-13.md](HANDOFF-phase-13.md) stub after this phase, or create it when finishing).

---

## 2. Phase 12 — your assignment (Wave B + Wave C)

### Goal

Continue CakePHP coverage from the audit: finish **Wave B** (web/app completeness), then **Wave C** (ecosystem extension packs) so agents cover the remaining everyday Book surfaces without inventing foreign frameworks.

**Source of truth:** [coverage-rules-skills.md](coverage-rules-skills.md)

### Wave B — required this phase (P1 web/app)

| Topic | Deliver |
|---|---|
| Pagination | Rule + skill(s) for controller/ORM pagination ownership |
| Forms + FormProtection | Dedicated rule/skill (security rule already has CSRF bar — deepen with FormHelper workflow) |
| Views / cells / helpers | Rules/skills **or** an explicit “views out of scope for v1 agents” decision documented in coverage + architecture |
| Error handling | Exception renderer / error trapping ownership rule + skill as needed |
| Configuration / DI | `Application::services` / config ownership rule + skill as needed |
| Performance | Core performance rule (query budget / caching guidance; N+1 already elsewhere) |

For each new/expanded unit:

- Match existing rule/skill contracts (frontmatter, sections, `inspect-before-coding` on skills)
- Prefer CakePHP Book 5.x provenance in `sources` / `knowledge/sources/` when framework-sensitive
- Add **positive + negative** evaluations for critical ownership boundaries
- Update `choose-cakephp-abstraction` / related decisions if ownership defaults change
- Keep rules **thin**; put procedures in skills

### Wave C — include after Wave B is green (ecosystem packs)

| Topic | Deliver |
|---|---|
| Migrations | `cakephp/migrations` capability pack (detect Composer; rules/skills; no inventing when absent) |
| Bake | Bake intelligence skill/rule pack or scoped guidance (do not assume Bake in every app) |
| Mailer / Queues | Packs **as demand appears** — at least stub manifests + honest “absent → do not invent” evals if you start them |

Follow existing extension patterns (`extensions/friendsofcake-crud/`, AuthN/AuthZ/Search): Composer detection, disable path, incompatible version path, fixtures, evals.

### Accept

- [x] Wave B rules/skills (or explicit out-of-scope notes) present; `php bin/cakephp-agent validate` green
- [x] Wave C: Migrations pack + Bake light pack; mailer/queue honesty evals (full packs deferred)
- [x] New skills reference `inspect-before-coding`
- [x] Critical new boundaries have pos+neg evals; `php bin/cakephp-agent eval` self-check green
- [x] `vendor/bin/phpunit` + `vendor/bin/phpstan analyse --memory-limit=512M` green
- [x] [coverage-rules-skills.md](coverage-rules-skills.md) updated (Wave B/C status)
- [x] README / architecture note Phase 12 status
- [x] Handoff for **Phase 13** (Packagist / 1.0 cut) written

> **Phase 12 complete.** Continue at [HANDOFF-phase-13.md](HANDOFF-phase-13.md).

### Out of scope

- Live LLM eval adapters
- MCP / remote registry
- 200+ eval corpus expansion for its own sake
- Packagist account setup (human) — Phase 13
- Re-litigating Phases 0–11 / Wave A

---

## 3. Kickoff prompt

```text
Read docs/HANDOFF-phase-12.md and docs/coverage-rules-skills.md.
Phase 11 Wave A is done. Implement Phase 12 Wave B (pagination, forms,
views-or-out-of-scope, errors, config/DI, performance), then Wave C
(migrations pack first; Bake/mailer/queues as time allows).
Leave validate/phpunit/phpstan/eval green and a Phase 13 Packagist/1.0 handoff.
```

---

## 4. Useful commands

```bash
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse --memory-limit=512M
php bin/cakephp-agent validate
php bin/cakephp-agent eval
php bin/cakephp-agent install --editor=cursor --dry-run --project=tests/fixtures/projects/cakephp-only
```

## 5. Contracts to mirror

- Rules: see existing `rules/cakephp/*.mdc` (Purpose via description + Framework semantics / Required behavior / Anti-patterns / Review checklist)
- Skills: Objective / Use when / Do not use when / Inputs / Workflow / Framework decisions / Anti-patterns / Validation / Completion criteria
- Extensions: `extensions/*/extension.json` + Composer detection fixtures (see CRUD/Search packs)
- Evals: `evaluations/**/*.json` — `id`, `category`, `prompt`, `expected`, `must_not`, `related_knowledge`
- ContentValidator enforces skill section list + `inspect-before-coding` reference
