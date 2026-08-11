# Agent Handoff — Start Phase 4

> **For a new agent:** read this file first, then the unified plan sections linked below. Do not re-bootstrap Phases 0–3.

**Repo:** `/Users/chrishickingbottom/Development/cakephp-agent`  
**Branch:** `main`  
**HEAD (at handoff):** `c90043c` — *Add canonical CakePHP knowledge, core rules, and evaluation seeds (Phase 3).*  
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
| [docs/plans/cakephp-agent-unified-plan.md](plans/cakephp-agent-unified-plan.md) | **Source of truth** for architecture, phases, DoD |
| [docs/plans/cakephp-agent-project-plan.md](plans/cakephp-agent-project-plan.md) | Original installer/extension blueprint (detail) |
| [docs/plans/cakephp-ai-knowledge-platform-expansion-plan.md](plans/cakephp-ai-knowledge-platform-expansion-plan.md) | Knowledge/eval platform expansion (detail) |
| [docs/architecture.md](architecture.md) | Short current-phase pointer |
| This file | Pickup status for Phase 4 |

---

## 3. Git history (completed work)

| Commit | Phase | Summary |
|---|---|---|
| `2b2701e` | 0–1 | Composer package, CLI, editor adapters, safe installer + lockfile, stub rules, tests/CI |
| `e813459` | 2 | Extension manifests, Composer/semver detection, enable/disable/dependsOn/conflicts, fake extensions, `detect`/`extensions`/`explain` |
| `c90043c` | 3 | Canonical decisions + anti-patterns, CakePHP core rules w/ provenance, 17 evals, `ContentValidator`, CI content job |

Working tree should be clean on `main` after these commits.

---

## 4. What exists today

### 4.1 Runtime / installer (`src/`)

- CLI: `bin/cakephp-agent` → `help`, `install`, `detect`, `extensions`, `explain`, `validate`, `doctor`, `version`
- Project discovery + Composer metadata (lock preferred)
- Config precedence: CLI → `.cakephp-agent.json` → `composer.json` extra → defaults
- Editor adapters: Cursor, Claude, Codex (managed paths under `.cursor|claude|codex/.../cakephp-agent/`)
- Safe install: never overwrite `.ai/`; prune only lock-tracked files (`.cakephp-agent.lock.json`)
- Extension engine: load manifests from `extensions/` + `integrations/`, resolve, install enabled pack files under `extensions/<id>/` in editor targets
- Content validation: knowledge decisions, anti-patterns, evaluations (≥10), CakePHP rule `truth_level`/`priority`, Laravel-term guard

### 4.2 Fake extensions (Phase 2 proof — keep)

- `extensions/test-fake-plugin/` — detects `cakephp-agent/fake-plugin` `^1.0`
- `extensions/test-fake-addon/` — detects `cakephp-agent/fake-addon`, `dependsOn: [test-fake-plugin]`
- Fixtures: `tests/fixtures/projects/{cakephp-only,cakephp-fake,cakephp-fake-incompatible,cakephp-fake-addon}/`

### 4.3 Canonical knowledge (Phase 3)

**Decisions** (`knowledge/decisions/`):

- `validation-vs-application-rule`
- `finder-vs-behavior`
- `component-vs-middleware`
- `entity-accessor-vs-query-calculation`
- `table-callback-vs-application-rule`
- `contain-vs-matching`
- `bulk-update-vs-entity-save`
- `plugin-vs-application-code`

*(CRUD listener vs ORM callback deferred to Phase 5 CRUD extension.)*

**Anti-patterns** (`knowledge/anti-patterns/`): 12 units (fat-controller, active-record-entity, over-eager-contain, …).

**Sources:** `knowledge/sources/cakephp.md`

### 4.4 CakePHP core rules installed by default

Under `rules/cakephp/`:

`philosophy` (alwaysApply), `conventions`, `architecture`, `controllers`, `orm`, `tables`, `entities`, `associations`, `finders`, `validation`, `application-rules`, `middleware`, `events`, `plugins`, `testing`, `security`

Plus generic: `rules/engineering/clean-code.mdc`, `rules/php/php.mdc`

### 4.5 Evaluations (17 JSON fixtures)

Categories: validation, architecture, orm, anti-laravel, plugins, project-awareness.  
Schema: `schemas/evaluation.schema.json`.  
No live model runner yet (deferred; schema validate only).

### 4.6 Skills today

**Empty placeholders only** — `skills/{engineering,php,cakephp}/.gitkeep`. Phase 4 owns the skill catalog.

### 4.7 Quality bar at handoff

- `composer install`
- `vendor/bin/phpunit` — ~32 tests
- `vendor/bin/phpstan analyse`
- `php bin/cakephp-agent validate`
- CI: tests (PHP 8.2–8.4), PHPStan, content-validation

---

## 5. Design constraints (do not regress)

1. CakePHP-native — never cargo-cult Laravel architecture.
2. Label `truth_level`: FRAMEWORK_* vs PACKAGE_RECOMMENDATION vs PROJECT_CONVENTION.
3. Project `.ai/` has highest precedence; installer never overwrites it.
4. Disabled/undetected extensions must not contaminate agent context.
5. Verify framework claims against Book of CakePHP 5 / upstream source; record provenance.
6. Skills are workflows; rules are persistent standards; decisions are ownership models.
7. Other skills must **reference** `inspect-before-coding` — do not paste long discovery walls into every skill.
8. No invented plugin APIs; no optional-plugin assumptions in **core** skills.
9. Prefer vertical slices: content → content validation → tests → docs → commit-ready.
10. Do not build knowledge compilers, MCP, or remote registries in Phase 4.

---

## 6. Phase 4 — your assignment

### Goal

Ship **thin, high-leverage P0 skills** under `skills/cakephp/` (and install them via existing installer). Grow evaluations to **≥20** total.

### Required skills (from unified plan)

| Skill | Notes |
|---|---|
| `inspect-before-coding` | **Flagship shared discovery** — others depend on this |
| `choose-cakephp-abstraction` | **Ownership router** — link to `knowledge/decisions/` |
| `analyze-cakephp-project` | Orientation |
| `create-finder` | ORM exemplar |
| `add-association` | ORM |
| `add-validation` | Pair with decision unit |
| `add-application-rule` | Pair with decision unit |
| `create-controller-action` | HTTP boundary |
| `create-api-endpoint` | HTTP/API |
| `create-event-listener` | Events |
| `diagnose-orm-query` | Diagnostic exemplar |
| `cakephp-code-review` | Review exemplar |
| `select-lifecycle-hook` | Lightweight lifecycle selector |

### Skill contract (each `skills/cakephp/<name>/SKILL.md`)

Must include:

1. Frontmatter: `name`, `description`
2. Objective
3. Use when / Do not use when
4. Inputs to discover (point at `inspect-before-coding` first)
5. Workflow (numbered; inspect before edit)
6. Framework decisions (link decision IDs where relevant)
7. Anti-patterns
8. Validation
9. Completion criteria

### Installer note

Core skills live under `skills/cakephp/<skill-id>/`. The current installer already copies files from `skills/{engineering,php,cakephp}/` into editor skill targets. Nested skill folders (with `SKILL.md`) should install as-is — **verify with a dry-run/integration test** after adding the first skill.

### Evaluations for Phase 4

Add ≥3 new evals tied to skills (examples):

- Choosing abstraction (controller vs table vs rule)
- Lifecycle hook selection
- Review skill expects discovery before edits

Keep pos+neg coverage for critical boundaries. Target **≥20** evaluation JSON files total (currently 17).

### Optional content validation extension

If useful, extend `ContentValidator` to require skill contract headings / unique skill names — only if it stays simple.

### Explicitly out of scope for Phase 4

- FriendsOfCake CRUD extension (Phase 5)
- AuthN/AuthZ packs (Phase 6)
- Live `eval` model runner (later)
- Full smell-detection skill suite (M7/P1)

---

## 7. Acceptance checklist (Phase 4 done when)

- [ ] All listed P0 skills exist with full contract
- [ ] Skills reference `inspect-before-coding` instead of duplicating discovery
- [ ] Core skills assume no optional plugins
- [ ] `cakephp-agent install --dry-run` shows skill files for a fixture project
- [ ] Integration/unit coverage for skill install path if anything broke
- [ ] ≥20 evaluations; `cakephp-agent validate` passes
- [ ] PHPUnit + PHPStan green
- [ ] `docs/architecture.md` updated to “Phase 4 complete → Phase 5 CRUD”
- [ ] This handoff file updated or a new `HANDOFF-phase-5.md` created for the next agent

---

## 8. Kickoff prompt (paste to new agent)

```text
You are continuing cakephp-agent at /Users/chrishickingbottom/Development/cakephp-agent.

Read docs/HANDOFF-phase-4.md first, then docs/plans/cakephp-agent-unified-plan.md (Phase 4 + skill/decision contracts).

Phases 0–3 are complete on main (HEAD should be c90043c or later Phase 4 work). Do not recreate the installer or extension engine.

Implement Phase 4 only: P0 CakePHP skills (inspect-before-coding and choose-cakephp-abstraction first), wire them through the existing installer, add evaluations to reach ≥20, keep validate/phpunit/phpstan green.

Rules:
- CakePHP-native; no Laravel cargo-cult
- Skills reference inspect-before-coding; put ownership logic in knowledge/decisions links
- No optional-plugin assumptions in core skills
- Verify CakePHP APIs against official docs when uncertain
- Vertical slice: skills + evals + tests + docs; then commit only if asked

Start by inspecting the repo and skills/ directories, then implement inspect-before-coding + choose-cakephp-abstraction as the first slice.
```

---

## 9. Useful commands

```bash
cd /Users/chrishickingbottom/Development/cakephp-agent
composer install
vendor/bin/phpunit
vendor/bin/phpstan analyse --memory-limit=512M
php bin/cakephp-agent validate
php bin/cakephp-agent detect --project=tests/fixtures/projects/cakephp-fake
php bin/cakephp-agent install --editor=cursor --dry-run
```

---

## 10. Known gaps / watchouts

- **Git author config:** machine may lack global `user.name` / `user.email`; prior commits used env `GIT_AUTHOR_*` / `GIT_COMMITTER_*` without writing git config.
- **Sandbox:** writing under `.cursor/` may be blocked in Cursor sandbox; installer tests use `FakeEditorAdapter` → `.editor/...`.
- **Skill layout:** confirm Cursor/Claude skill folder expectations (`SKILL.md` in named folders) match adapter output paths.
- **CRUD decision** `crud-listener-vs-orm-callback` intentionally not in core knowledge yet — Phase 5.
- **composer version warning:** root package version may default when not published; ignore unless packaging for Packagist.

---

## 11. After Phase 4

Next major milestone is **Phase 5 — FriendsOfCake CRUD reference extension** (architectural proof for real plugin packs). Update handoff accordingly.
