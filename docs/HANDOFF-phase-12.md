# Agent Handoff — Start Phase 12

> **For a new agent:** read this file first, then [coverage-rules-skills.md](coverage-rules-skills.md) and [pre-1.0-review.md](pre-1.0-review.md). Do not re-bootstrap Phases 0–11.

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

---

## 2. Phase 12 — your assignment

### Goal

Move from **0.9.0 adopter preview** toward a credible **1.0** cut: Packagist (human) + remaining trust items, and optionally Wave B coverage if time.

### Track A — release / Packagist (primary)

- Confirm Packagist publish of `chrisshick/cakephp-agent` (human account; agent can prepare docs/checklist only)
- Walk [pre-1.0-review.md](pre-1.0-review.md) remaining items
- Version bump strategy to **1.0.0** when accept bar is met
- CHANGELOG / README status line for 1.0

### Track B — Wave B coverage (optional stretch)

From [coverage-rules-skills.md](coverage-rules-skills.md):

1. Pagination  
2. Forms + FormProtection skill (security rule already has CSRF bar)  
3. Views/cells/helpers **or** explicit “views out of scope” note  
4. Error handling / exception renderer  
5. Configuration + DI  
6. Performance rule  

Keep rules thin; skills procedural; pos+neg evals for new ownership boundaries.

### Accept

- [ ] Packagist (or documented blocked-on-human) path clear
- [ ] pre-1.0 review items triaged (done / deferred with reason)
- [ ] If Wave B started: validate + phpunit + phpstan + eval green; coverage doc updated
- [ ] README / architecture reflect 1.0 or explicit “still 0.9.x + Wave B”
- [ ] Handoff for Phase 13 only if needed (Wave C / migrations pack)

### Out of scope

- Live LLM eval adapters
- MCP / remote registry
- Wave C ecosystem packs unless Wave B done and user asks
- Re-litigating Phases 0–11

---

## 3. Kickoff prompt

```text
Read docs/HANDOFF-phase-12.md, docs/coverage-rules-skills.md, and docs/pre-1.0-review.md.
Phase 11 Wave A coverage is done. Prepare Packagist/1.0 cut (Track A);
optionally implement Wave B coverage if release blockers are clear.
Leave the repo green (validate, phpunit, phpstan, eval).
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
