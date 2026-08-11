# Agent Handoff — Start Phase 11

> **For a new agent:** read this file first, then [coverage-rules-skills.md](coverage-rules-skills.md). Do not re-bootstrap Phases 0–10.

**Repo:** https://github.com/chrisShick/cakephp-agent  
**Local path (if present):** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**Package:** `chrisshick/cakephp-agent` (PHP 8.2+) · CLI version **0.9.0**  
**Status date:** 2026-08-10

---

## 1. Already done (do not redo)

### Phases 0–10

Installer, extensions (CRUD/AuthN/AuthZ/Search + integrations), P0 skills, agents, decision/smell intelligence, offline `eval` runner.

### Phase 11 docs / packaging slice (complete)

Adopter preview **0.9.0** is on GitHub (`v0.9.0`):

- Install / editors / overlays / extension authoring / troubleshooting docs
- `uninstall`, hardened `doctor`, fakes quarantined, schema fixes
- CHANGELOG, SECURITY, CONTRIBUTING, `.gitattributes`
- Composer name: `chrisshick/cakephp-agent`

**Still manual / not your primary job:** Packagist publish, declaring 1.0.

---

## 2. Phase 11 — your assignment (CakePHP coverage)

### Goal

Close the everyday CakePHP **rules + skills** holes from the coverage audit so agents don’t invent wrong abstractions for common Book surfaces.

**Source of truth:** [coverage-rules-skills.md](coverage-rules-skills.md)

### Wave A — required this phase (P0)

| Topic | Deliver |
|---|---|
| Routing | Core rule `rules/cakephp/routing.mdc` + skill(s) e.g. `add-route` / `review-routing` |
| Behaviors | Rule `behaviors.mdc` + skill `create-behavior` |
| Components | Rule `components.mdc` + skill `create-component` |
| Commands / console | Rule `commands.mdc` + skill `create-command` |
| Transactions | Rule `transactions.mdc` + skill `add-transaction` (link decision `transaction-vs-independent-save`) |
| Security depth | Expand `rules/cakephp/security.mdc` (FormProtection/CSRF, uploads, serialization) |
| Testing depth | Expand `rules/cakephp/testing.mdc` + skill `write-table-test` (and/or integration test skill) |

For each new/expanded unit:

- Match existing rule/skill contracts (frontmatter, sections, `inspect-before-coding` on skills)
- Prefer CakePHP Book 5.x provenance in `sources` / `knowledge/sources/` when framework-sensitive
- Add **positive + negative** evaluations for critical ownership boundaries
- Update `choose-cakephp-abstraction` / related decisions if ownership defaults change
- Keep rules **thin**; put procedures in skills

### Wave B — include if Wave A finishes cleanly (P1)

Pagination; forms + FormProtection; views/cells/helpers **or** explicit “views out of scope” note; error handling; configuration/DI; performance rule.

### Wave C — out of Phase 11 (later / extensions)

Migrations pack, Bake intelligence, mailer/queues, I18n, HTTP client — do **not** start unless Wave A+B are done and the user asks.

### Accept

- [x] Wave A rules + skills present and pass `php bin/cakephp-agent validate`
- [x] New skills reference `inspect-before-coding`
- [x] Critical new boundaries have pos+neg evals; `php bin/cakephp-agent eval` self-check green
- [x] `vendor/bin/phpunit` + `vendor/bin/phpstan analyse --memory-limit=512M` green
- [x] [coverage-rules-skills.md](coverage-rules-skills.md) updated (Wave A marked done)
- [x] README / architecture note Phase 11 coverage status
- [x] Handoff for **Phase 12** (Packagist / 1.0 cut, or Wave B leftovers) written

> **Phase 11 Wave A complete.** Continue at [HANDOFF-phase-12.md](HANDOFF-phase-12.md) (Wave B + Wave C). Packagist/1.0 is [HANDOFF-phase-13.md](HANDOFF-phase-13.md).

### Out of scope

- Live LLM eval adapters
- MCP / remote registry
- 200+ eval corpus expansion for its own sake
- Packagist account setup (human)
- Re-litigating Phases 0–10

---

## 3. Kickoff prompt

```text
Read docs/HANDOFF-phase-11.md and docs/coverage-rules-skills.md.
Phases 0–10 and the 0.9.0 docs/packaging slice are done.
Implement Phase 11 Wave A only: routing, behaviors, components, commands,
transactions, deeper security + testing rules/skills, with evals and validate green.
Update the coverage doc when finished and leave a Phase 12 handoff.
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
- Evals: `evaluations/**/*.json` — `id`, `category`, `prompt`, `expected`, `must_not`, `related_knowledge`
- ContentValidator enforces skill section list + `inspect-before-coding` reference
