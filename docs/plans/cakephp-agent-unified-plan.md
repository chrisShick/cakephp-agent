# CakePHP Agent — Unified Implementation Plan

> **Purpose:** Single authoritative blueprint merging the original [CakePHP Agent Rules project plan](./cakephp-agent-project-plan.md) with the [AI Knowledge Platform expansion plan](./cakephp-ai-knowledge-platform-expansion-plan.md), plus refinements for a shippable v1 and a durable platform beyond it.
>
> **Status:** Unified implementation specification — use this as the source of truth for implementation sessions.
>
> **Primary inspiration:** `pekral/cursor-rules` patterns (distribution, skills, multi-editor), rewritten CakePHP-native — never a mechanical Laravel port.
>
> **Placeholder package:** `vendor/cakephp-agent` (final vendor/name TBD)

---

## 0. How to read this document

| Layer | Role |
|---|---|
| **Foundation** | Composer-distributable installer, editor adapters, extension system, safe project overrides (original blueprint) |
| **Product** | Canonical knowledge, decision models, rules/skills, behavioral evaluations (expansion plan) |
| **Delivery** | Cursor / Claude Code / Codex / CLI / future MCP — adapters only |

**Working rule:** Ship the foundation first. Introduce knowledge-platform primitives early enough that content is written once in a canonical form, but do not build compilers, registries, or MCP until duplication or external demand forces them.

---

## 1. Executive summary

Build an open, testable **AI engineering knowledge platform for CakePHP 5.x** that:

1. Encodes how experienced CakePHP engineers reason (not only how to emit valid syntax).
2. Distributes that knowledge as rules, skills, and agents into Cursor, Claude Code, and Codex.
3. Auto-enables plugin capability packs from Composer metadata (never contaminating projects with absent APIs).
4. Proves quality with behavioral evaluations, not rule count.
5. Stays extensible: FriendsOfCake CRUD is the reference extension; Authentication, Authorization, Search follow.

**Quality bar (v1):** An agent reasons about CakePHP using the same ownership boundaries, conventions, plugin semantics, and discovery habits as a strong CakePHP engineer — and only applies extension knowledge when that capability is actually present.

---

## 2. Product identity

**Positioning (preferred):**

> CakePHP Agent gives AI coding tools framework-native knowledge, architectural decision models, workflows, plugin intelligence, and behavioral evaluations so they reason like experienced CakePHP engineers instead of merely generating CakePHP-shaped PHP.

**Not:** “Cursor rules for CakePHP.”

**Four questions the platform answers:**

1. What does CakePHP do?
2. Which CakePHP abstraction should be used here?
3. How should this task be implemented correctly?
4. How can we verify the AI made the right architectural choice?

**Primitives:**

```text
Authoritative sources
        ↓
Canonical knowledge (concepts, decisions, patterns, anti-patterns, lifecycle)
        ↓
Rules + Skills + Agents
        ↓
Behavioral evaluations
        ↓
Platform adapters (Cursor / Claude / Codex / CLI / future)
```

---

## 3. Goals and non-goals

### 3.1 Primary goals

| ID | Goal |
|---|---|
| G1 | CakePHP-native intelligence (no Laravel mental model leakage) |
| G2 | Workflow-oriented skills for common CakePHP tasks |
| G3 | Extensible capability packs (rules/skills/agents/detection/compat) |
| G4 | Composer-aware discovery (`composer.lock` preferred) |
| G5 | Project-safe install (never overwrite project-owned files by default) |
| G6 | Multi-editor support via adapters; canonical source stays editor-agnostic |
| G7 | Verifiable quality: schema validation, installer tests, content lint, behavioral evals |
| G8 | Decision intelligence: ownership choices are first-class knowledge |
| G9 | Measurable reasoning quality via evaluation corpus and baselines |

### 3.2 Non-goals (v1)

- Framework fork or application code generation during install
- Mandating DDD / API-only / specific DB / Pest
- Bundling every known CakePHP plugin
- Auto-inferring subjective architecture from weak heuristics
- Modifying application source or Composer deps
- Replacing official CakePHP docs
- Remote extension registry, MCP server, or sophisticated knowledge compiler
- Treating rule/file count as a success metric

---

## 4. Design principles (merged + refined)

1. **Convention before invention** — inspect project + CakePHP conventions first.
2. **Label knowledge provenance** — distinguish FRAMEWORK REQUIREMENT / DEFAULT / PLUGIN SEMANTIC / PACKAGE RECOMMENDATION / PROJECT CONVENTION / OPTIONAL ALTERNATIVE.
3. **Compose, don’t monolith** — small focused packs; context budget matters.
4. **Objective signals auto-detect; subjective choices are explicit** — Composer packages yes; “domain services” no.
5. **Rules = persistent standards; skills = workflows; decisions = ownership models.**
6. **Precedence:** project > enabled extensions (+ integrations) > cakephp > php > engineering.
7. **Minimal contamination** — disabled/undetected packs must not enter agent context.
8. **Source authority** — Book of CakePHP, framework source, official plugin docs; maintain provenance metadata.
9. **Knowledge is the IP; editors are adapters** — avoid coupling canonical content to `.mdc` forever.
10. **Evaluate what you teach** — critical decisions need positive *and* negative behavioral coverage.
11. **Inspect before coding** — discovery is a shared behavior, not copy-pasted into every skill.
12. **Signal density over volume** — always-on rules stay small; procedure lives in skills.

---

## 5. Improvements over the two source plans

These are intentional changes or tightenings introduced by this unified plan.

### 5.1 Scope discipline

| Issue in source plans | Unified approach |
|---|---|
| Expansion plan skill catalogs are enormous for v1 | Cap **v1 skill surface**; put the rest in a prioritized backlog with “definition of complete” per skill |
| Two overlapping skill lists (core + expansion) | Single skill backlog with tiers P0–P3 |
| “200+ evals before v1.0” risks blocking ship | **v1.0 ships with ≥50 high-quality evals** covering critical boundaries; 200+ is **v1.1 / M11** |
| Full knowledge tree + full rule tree can duplicate content | Canonical `knowledge/` is source of truth; rules/skills are **thin projections** with cross-links — start with co-located frontmatter, introduce `build` only if duplication hurts |

### 5.2 Architecture tightenings

1. **Canonical knowledge directory from Phase 3 onward** — even if initially sparse — so content authors never invent a second source of truth.
2. **Integration packs as first-class extension type** (`activateWhenAllExtensionsPresent`) — designed in Phase 2 schema, implemented after CRUD+Search.
3. **Single project-owned root:** `.ai/` for architecture, project rules, and overrides; installer maps into editor paths. Avoid dual conventions (`.cursor/rules/project/` *and* `.ai/project/`).
4. **`inspect-before-coding` and `choose-cakephp-abstraction` are P0 core skills**, not post-v1 polish — they prevent the rest of the catalog from hallucinating conventions.
5. **Seed evaluation harness in Phase 3–4** (schema + a handful of fixtures + CI dry-run), full runner later — so eval-driven development is possible while writing CRUD.
6. **Context budget policy in manifests** — each rule declares `priority: critical|high|normal|reference`; installer can eventually filter; for v1, enforce manually in review.
7. **Fake extension first in Phase 2** — before authoring real CRUD content (already in original; keep as hard gate).
8. **Version-aware content tags** — `framework_versions` / package constraints on knowledge units; skip installing content that doesn’t match detected versions.
9. **Laravel anti-contamination as CI lint + eval suite**, not only docs.
10. **Opt-in Composer plugin**; default path is explicit `vendor/bin/cakephp-agent install`.

### 5.3 Delivery / ops improvements

1. **SemVer policy:** `0.x` until installer + extension manifest + core rules stabilize; `1.0` only when DoD below is met.
2. **Content freeze gates:** no mass skill dump without source verification checklist in PR template.
3. **Fixture matrix is the contract** — every detection/install claim maps to a fixture project scenario.
4. **Name decision early** — pick package name in Phase 0 to avoid rewrite churn (`cakephp-agent` recommended).
5. **Contributor “knowledge PR” template** — source link, knowledge update, rule/skill, eval, labels (framework vs recommendation).

### 5.4 What we deliberately defer

- Knowledge compiler / MCP / remote registry
- Living architecture drift auto-rewrite
- Database packs beyond stubs
- Plugin-author / package-maintainer modes (post-v1)
- LLM-as-judge scoring (optional after deterministic concept tags work)

---

## 6. High-level architecture

```text
                     ┌──────────────────────────┐
                     │ knowledge/ (canonical)   │
                     │ concepts · decisions ·   │
                     │ patterns · anti-patterns │
                     │ lifecycle · sources      │
                     └────────────┬─────────────┘
                                  │ projected to
              ┌───────────────────┼───────────────────┐
              ▼                   ▼                   ▼
        rules/              skills/              agents/
              └───────────────────┼───────────────────┘
                                  ▼
                     ┌──────────────────────────┐
                     │ evaluations/             │
                     └────────────┬─────────────┘
                                  ▼
                     ┌──────────────────────────┐
                     │ Installer + Discovery    │
                     │ Extension resolver       │
                     │ Editor adapters          │
                     └────────────┬─────────────┘
                                  ▼
              Cursor / Claude / Codex / .ai project overlays
```

Core layers always installed (when CakePHP detected): `engineering` → `php` → `cakephp`.

Optional: Composer-detected or explicitly enabled extensions + integration packs.

Highest precedence: `.ai/` project knowledge.

---

## 7. Repository structure (unified)

```text
cakephp-agent/
├── .github/workflows/          # ci, static-analysis, content-validation, eval (later), release
├── agents/                     # specialized reviewers
├── bin/cakephp-agent
├── docs/                       # architecture, install, editors, authoring, sources, ADRs
├── knowledge/                  # CANONICAL (intro from Phase 3)
│   ├── concepts/
│   ├── decisions/
│   ├── patterns/
│   ├── anti-patterns/
│   ├── lifecycle/
│   ├── interoperability/
│   ├── security/
│   ├── performance/
│   ├── upgrades/
│   └── sources/
├── evaluations/                # behavioral corpus (seed early; grow continuously)
├── extensions/
│   ├── friendsofcake-crud/     # reference extension
│   ├── cakephp-authentication/
│   ├── cakephp-authorization/
│   ├── cakephp-migrations/
│   ├── cakephp-bake/
│   ├── friendsofcake-search/
│   └── ...
├── integrations/               # activated when all deps present
│   └── friendsofcake-crud+search/
├── rules/{engineering,php,cakephp}/
├── skills/{engineering,php,cakephp}/
├── schemas/
│   ├── extension-manifest.schema.json
│   ├── project-config.schema.json
│   ├── knowledge-frontmatter.schema.json
│   └── evaluation.schema.json
├── src/
│   ├── Command/
│   ├── Configuration/
│   ├── Discovery/
│   ├── Editor/
│   ├── Extension/
│   ├── Filesystem/
│   ├── Installer/
│   ├── Manifest/
│   ├── Knowledge/              # load/validate canonical units (thin at first)
│   ├── Evaluation/             # schema + runner (seed → full)
│   └── Validation/
├── tests/{unit,integration,content,fixtures}/
├── composer.json
├── phpunit.xml
├── phpstan.neon
├── README.md
├── CONTRIBUTING.md
├── CHANGELOG.md
├── SECURITY.md
└── LICENSE
```

**Project overlay convention (consumer apps):**

```text
.ai/
├── architecture.md
├── rules/
└── skills/
```

Installer never overwrites `.ai/**`. Editor adapters may symlink/copy *package-managed* files into `.cursor/`, `.claude/`, etc., while instructing agents to honor `.ai/` as highest precedence.

---

## 8. CLI surface

```bash
vendor/bin/cakephp-agent help
vendor/bin/cakephp-agent install --editor=cursor|claude|codex|all
vendor/bin/cakephp-agent detect
vendor/bin/cakephp-agent extensions
vendor/bin/cakephp-agent validate
vendor/bin/cakephp-agent doctor
vendor/bin/cakephp-agent explain          # why each pack is active (ship by M2)
vendor/bin/cakephp-agent eval             # post-seed; required by M11, stub earlier OK
```

Install flags: `--force` `--symlink` `--prune` `--extension=` `--without=` `--dry-run` `--verbose`

State file: `.cakephp-agent.lock.json` (managed files + hashes + enabled packs + package version).

Config precedence:

```text
CLI flags > .cakephp-agent.json > composer.json extra.cakephp-agent > detection > defaults
```

---

## 9. Extension system (summary)

**Manifest capabilities:** id, detection (Composer constraints), CakePHP/PHP compat, dependsOn, conflictsWith, contributed rules/skills/agents, `defaultEnabledWhenDetected`, optional `activateWhenAllExtensionsPresent` for integrations, path sandboxing.

**Types:** composer-package | architecture (explicit) | infrastructure | integration.

**Detection order:** `composer.lock` → root `composer.json` → explicit config. Never execute app code or arbitrary Composer scripts for detection.

**Reference extension:** FriendsOfCake CRUD — proves lifecycle vs ORM boundaries, listeners, events, config decision order.

**CRUD listener path convention** (`src/Listener/...` mirroring controllers) is a **package recommendation**, overridable via `.ai/`.

---

## 10. Knowledge contracts

### 10.1 Decision units (`knowledge/decisions/`)

Required sections: use cases, decision questions, recommended outcome, rejected alternatives, exceptions, examples, linked evaluations, `truth_level` label.

**v1 decision catalog (minimum):**

- validation-vs-application-rule
- finder-vs-behavior
- component-vs-middleware
- entity-accessor-vs-query-calculation
- table-callback-vs-application-rule
- crud-listener-vs-orm-callback *(CRUD extension)*
- contain-vs-matching
- bulk-update-vs-entity-save
- plugin-vs-application-code

Remaining expansion-plan decisions are backlog after M7.

### 10.2 Flagship skills (P0)

| Skill | Why |
|---|---|
| `inspect-before-coding` | Shared discovery; other skills reference it |
| `choose-cakephp-abstraction` | Ownership router for the whole platform |
| `analyze-cakephp-project` | Orientation skill |
| `create-finder` | ORM workflow exemplar |
| `add-validation` / `add-application-rule` | Encodes the #1 CakePHP confusion |
| `create-controller-action` | HTTP boundary exemplar |
| `diagnose-orm-query` | Diagnostic exemplar |
| `cakephp-code-review` | Review exemplar |
| CRUD skills (in extension) | Extension proof |

### 10.3 Rule file contract

Frontmatter + Purpose / Framework semantics / Required behavior / Preferred patterns / Decision rules / Anti-patterns / Examples / Review checklist. Prefer enforceable criteria over essays. Tag `truth_level` and `priority`.

### 10.4 Skill file contract

Objective / Use when / Do not use when / Inputs to discover / Workflow (starts with inspect) / Framework decisions / Anti-patterns / Validation / Completion criteria.

### 10.5 Evaluation contract

```yaml
id: unique-email-uses-application-rule
category: validation
prompt: >
  I need to ensure a user's email address is unique when saving.
expected:
  concepts: [application-rule]
  preferred: [RulesChecker]
must_not: [rely solely on validationDefault]
```

Types: selection, rejection, lifecycle, project-awareness, plugin-awareness, security, performance, anti-hallucination.

**v1.0 eval bar:** ≥50 curated scenarios covering critical architecture/ORM/validation/security/anti-Laravel/CRUD. Expand to 200+ in M11.

---

## 11. CakePHP core content priorities

### Rules (implement in this order)

1. conventions, architecture, philosophy (negative rules included)
2. controllers, middleware, routing, requests/responses
3. ORM, tables, entities, associations, finders
4. validation, application-rules, transactions
5. events, behaviors, components, commands, plugins
6. testing, fixtures, security, performance
7. DI/configuration/errors/cache/logging as needed

### Anti-patterns (v1 minimum)

fat-controller, fat-table, active-record-entity, premature-service-layer, repository-over-table, persistence-concern-in-controller, http-concern-in-model, hidden-n-plus-one, over-eager-contain, mass-assignment-overexposure, plugin-api-reimplementation, framework-replacement-abstraction

### Philosophy (always-on, concise)

- Convention before configuration
- Prefer CakePHP extension points over framework replacements
- Don’t wrap every Table in a repository by default
- Entities are not Active Record
- Don’t add services solely to shorten controllers
- Use plugins only when intentionally adopted
- Inspect existing conventions before inventing new ones
- Rejected alternatives matter (“why not”)

---

## 12. Extension roadmap

| Tier | Pack | Notes |
|---|---|---|
| Reference | `friendsofcake-crud` | M4 gate |
| Tier 1 | authentication, authorization, migrations, bake, search | AuthN/AuthZ must not conflate |
| Tier 1b | `integrations/crud+search`, authn+authz | After bases stable |
| Tier 2 | crud-view, crud-json-api, debug-kit, queues, test utils | Only if agent behavior meaningfully improves |
| Later | database-postgresql/mysql, package-maintainer mode | Keep DB out of CakePHP core truth |

---

## 13. Implementation phases (unified)

Each phase is a **vertical slice**: code → tests → content validation → docs touch → commit-ready.

### Phase 0 — Repository bootstrap

**Deliver:** Composer package skeleton, CLI help, PHPUnit, PHPStan, CI stubs, LICENSE, directory scaffold, package name decision, empty `knowledge/` + `evaluations/` + schemas placeholders.

**Accept:** `composer install`, tests run, CI green, CLI help works.

---

### Phase 1 — Installer foundation

**Deliver:** project root discovery; editor adapters (Cursor/Claude/Codex); managed file install; `--force` / `--symlink` / `--dry-run`; installation lock state; preserve `.ai/` and project-owned paths.

**Accept:** installs static stub rules into fixtures; never overwrites project-owned; dry-run accurate.

---

### Phase 2 — Extension engine

**Deliver:** manifest schema (incl. future integration hooks); registry; Composer detection; version matching; enable/disable; dependency/conflict/cycle handling; `detect` / `extensions` / `explain` / `doctor`; **fake test extension first**.

**Accept:** fixture detection matrix (A–F scenarios from original plan); incompatible versions reported, not installed; explicit disable wins.

---

### Phase 3 — Canonical knowledge + CakePHP core rules

**Deliver:**

- Knowledge frontmatter schema + source map docs
- Core decision units (v1 minimum set)
- CakePHP core rules (prioritized list) with provenance
- Content lint (frontmatter, Laravel-term guard where appropriate)
- **Seed evaluations:** schema + ≥10 scenarios + CI validate-only job

**Accept:** rules source-reviewed for CakePHP 5; no accidental Laravel architecture; knowledge units validate.

---

### Phase 4 — Core skills (thin, high-leverage)

**Deliver P0 skills:** `inspect-before-coding`, `choose-cakephp-abstraction`, `analyze-cakephp-project`, `create-finder`, `add-association`, `add-validation`, `add-application-rule`, `create-controller-action`, `create-api-endpoint`, `create-event-listener`, `diagnose-orm-query`, `cakephp-code-review`, plus `select-lifecycle-hook` (lightweight).

Skills must reference `inspect-before-coding` instead of duplicating discovery walls.

**Accept:** each skill has full contract; no optional-plugin assumptions; ≥20 evals total.

---

### Phase 5 — FriendsOfCake CRUD reference extension

**Deliver:** full CRUD pack (rules, skills, decisions for CRUD vs ORM, listener convention as recommendation, fixtures, extension-scoped evals).

**Accept:** Scenario B/D/F pass; CRUD absent ⇒ CRUD knowledge absent; CRUD event vs ORM event decision covered by evals.

---

### Phase 6 — AuthN / AuthZ

**Deliver:** independent packs; clear identity vs authorization; joint guidance when both present (integration pack OK).

**Accept:** no cross-assumption when only one installed; IDOR/mass-assignment guidance present.

---

### Phase 7 — Search + first integration pack

**Deliver:** FriendsOfCake Search; `integrations/friendsofcake-crud+search`; prove non-duplication.

**Accept:** Search-only / CRUD-only / both fixtures behave correctly.

---

### Phase 8 — Agents + deeper reviews

**Deliver:** `cakephp-code-reviewer`, `cakephp-orm-reviewer`, `cakephp-security-reviewer`, `cakephp-architecture-reviewer` — capability-aware (no CRUD assumptions unless pack enabled).

---

### Phase 9 — Decision intelligence hardening (M7)

**Deliver:** expand decision catalog; smell catalog (v1 set); `detect-architectural-smells` / `review-abstraction-choice`; eval coverage for each critical decision (positive + negative).

---

### Phase 10 — Evaluation platform (M11 track)

**Deliver:** `cakephp-agent eval` runner; baselines by knowledge version + model; 200+ scenarios; anti-Laravel suite; regression reporting.

**Note:** Can overlap late Phase 8–9; must not block **v1.0** if ≥50 evals + schema validation already ship.

---

### Phase 11 — Docs, adopter preview, CakePHP coverage

**Deliver (docs/packaging — done in 0.9.0):** installation, editors, extension authoring, contributing, architecture, SECURITY, CHANGELOG, packaging trust fixes.

**Deliver (coverage Wave A — done):** routing, behaviors, components, commands, transactions, deeper security/testing — see [coverage-rules-skills.md](../coverage-rules-skills.md).

**Phase 12:** Packagist / 1.0 + optional Wave B — [HANDOFF-phase-12.md](../HANDOFF-phase-12.md).

---

### Phase 12+ — Post-1.0 platform (P2/P3)

ORM expert depth, security/API programs, Bake intelligence, upgrade intelligence, DB packs, plugin-author mode, source freshness audit, MCP adapter, remote registry — only with clear demand and eval coverage.

---

## 14. Milestones

| ID | Name | Exit criteria |
|---|---|---|
| M1 | Installer MVP | Static rules install safely |
| M2 | Extension engine | Composer detection + explain + fixtures |
| M3 | CakePHP expert core | Core rules + P0 skills + knowledge seed + ≥20 evals |
| M4 | CRUD proof | CRUD extension production-quality |
| M5 | Ecosystem foundation | AuthN, AuthZ, Search + one integration |
| M6 | **v1.0** | DoD below |
| M7 | Decision intelligence | Full v1 decision/smell coverage |
| M8 | ORM expert | Advanced ORM skills + evals |
| M9 | Architecture expert | Smells + drift review prototype |
| M10 | Security & API expert | Specialized security/API skills |
| M11 | Evaluation platform | Runner + 200+ + baselines |
| M12 | Ecosystem platform | Plugin author / upgrades / more adapters |

---

## 15. Definition of Done — v1.0

All must be true:

- [ ] Composer package installable; CLI documented
- [ ] Cursor + Claude Code supported; Codex to degree interfaces allow
- [ ] CakePHP 5 core rules cover prioritized catalog (not infinite backlog)
- [ ] ≥12 P0 skills including `inspect-before-coding` and `choose-cakephp-abstraction`
- [ ] Extension manifest schema stable
- [ ] Composer auto-detection works; `explain` works
- [ ] FriendsOfCake CRUD extension production-quality
- [ ] Authentication + Authorization + Search extensions exist
- [ ] At least one integration pack exists
- [ ] `.ai/` project overrides safe; lockfile prune-safe
- [ ] Canonical `knowledge/` for v1 decisions + provenance for framework-sensitive rules
- [ ] ≥50 behavioral evaluations validated in CI; anti-Laravel subset present
- [ ] CI: tests, PHPStan, content validation, composer validate
- [ ] Docs enable third parties to author extensions
- [ ] Inspiration inventory (`docs/inspiration-matrix.md`) completed for anything adapted from `pekral/cursor-rules`

**Explicitly not required for v1.0:** eval runner with live model APIs, 200+ evals, MCP, DB packs, plugin-author mode, knowledge compiler.

---

## 16. Acceptance scenarios (install/detection)

Keep original scenarios A–F:

| ID | Setup | Expect |
|---|---|---|
| A | CakePHP only | engineering + php + cakephp |
| B | + CRUD | + friendsofcake-crud |
| C | + CRUD + Search | + both + integration if implemented |
| D | CRUD present, disabled in config | no CRUD knowledge |
| E | Project `.ai` overrides listener convention | agent follows project |
| F | Unsupported CRUD major | detect, diagnose, do not install incompatible rules |

Add:

| ID | Setup | Expect |
|---|---|---|
| G | AuthN only | no AuthZ policy APIs recommended |
| H | Knowledge unit requires CakePHP ≥5.2, project on 5.0 | unit not installed / clearly gated |

---

## 17. Testing strategy

1. **Unit:** detection, semver, resolution, config precedence, path sandboxing
2. **Integration:** fixture projects install into temp dirs; golden file sets
3. **Content:** schemas, unique IDs, manifest path existence, Laravel-term guard, truth_level present on framework-sensitive units
4. **Evaluations:** schema validity always; optional model runs in separate workflow/secrets
5. **Security:** no path traversal from manifests; no secret printing; opt-in Composer plugin

---

## 18. Security (supply chain)

- No remote code download during install
- No app source modification
- Path traversal protection for all extension paths
- Read-only Composer metadata inspection
- Opt-in auto-install only
- Treat package as developer supply-chain sensitive software

---

## 19. Inspiration migration

Do not wholesale copy `pekral/cursor-rules`. Inventory each item: KEEP / ADAPT / REWRITE / REMOVE / REPLACE into `docs/inspiration-matrix.md`.

---

## 20. Priority backlog (post-core)

### P0 (v1)

Foundation, core knowledge/rules, extension system, CRUD, AuthN/AuthZ/Search, flagship skills, decision seed, ≥50 evals

### P1

ORM depth, smells, lifecycle selection depth, security specialties, static analysis intelligence, integration packs

### P2

API engineering, Bake intelligence, upgrades, DB packs, living architecture, docs generation skills

### P3

Remote extensions, MCP, multi-provider eval service, documentation portal

---

## 21. Success metrics

Measure (not rule count):

- Architectural decision accuracy (eval)
- Framework API accuracy
- Plugin awareness / non-contamination
- Project convention adherence
- Security issue detection
- ORM performance issue detection
- Foreign-framework hallucination rate
- Eval regression rate
- Knowledge freshness (`last_verified`)
- Approximate context/token footprint of default install

---

## 22. ADR summary (carry forward)

| ADR | Decision |
|---|---|
| 001 | CakePHP-first, not Laravel port |
| 002 | Rules ≠ skills |
| 003 | Extensions are capability packs |
| 004 | Composer drives plugin detection |
| 005 | Subjective architecture is explicit |
| 006 | Project rules highest precedence |
| 007 | CRUD is reference extension |
| 008 | Mirrored CRUD listeners are recommendation |
| 009 | Managed-file state required for prune |
| 010 | **NEW:** Canonical `knowledge/` is source of truth; editor files are projections |
| 011 | **NEW:** Critical decisions require behavioral evals (pos + neg) before “done” |
| 012 | **NEW:** `.ai/` is the sole project-owned overlay root |
| 013 | **NEW:** v1.0 eval bar is ≥50 quality scenarios; 200+ is M11 |
| 014 | **NEW:** Context priority classes on rules; always-on set stays small |

---

## 23. LLM implementation directives

1. Treat **this unified plan** as architectural source of truth; use the two source plans as detail appendices when needed.
2. Inspect the repo; resume from earliest incomplete phase; don’t recreate completed work.
3. Research CakePHP/plugin APIs against current official sources before encoding; record provenance.
4. Implement vertical slices; test each phase.
5. Do not remove extension manifests, adapters, lock state, or project-override safety.
6. Do not cargo-cult Laravel.
7. Prefer fixture apps that look like real CakePHP Composer layouts.
8. No invented plugin APIs.
9. When adding a critical decision, add pos+neg evaluations in the same slice.
10. Keep always-on rules small; put procedures in skills; put ownership logic in `knowledge/decisions/`.

### First session

Complete **Phase 0 + Phase 1** only.

### Second session

**Phase 2** with fake extension + fixture matrix.

### Third session

**Phase 3** core knowledge/rules + evaluation schema seed.

### Fourth session

**Phase 4** P0 skills.

### Fifth session

**Phase 5** FriendsOfCake CRUD (architectural proof).

---

## 24. Recommended kickoff prompt

> You are implementing `cakephp-agent` per `cakephp-agent-unified-plan.md`.
>
> Inspect the repository and continue from the earliest incomplete phase. Do not recreate completed work.
>
> For CakePHP or plugin behavior, verify against current official docs/source before encoding. Record provenance.
>
> Implement the next vertical slice completely (code, tests, content validation, minimal docs). Preserve extension architecture, editor adapters, installation lock state, `.ai/` override safety, and canonical `knowledge/` direction.
>
> Never mechanically translate Laravel concepts. Distinguish framework requirements from package recommendations.
>
> Prefer thin always-on rules, decision units for ownership questions, and skills for workflows. Add positive and negative evaluations when touching critical architectural boundaries.
>
> Before finishing, run relevant tests, static analysis, content validation, and `composer validate`.

---

## 25. Final standard

The original blueprint asked whether an AI can generate excellent CakePHP code.

The expansion asked whether we can encode, distribute, test, and improve the architectural reasoning behind that code.

**This unified plan ships both:** a production installer/extension platform **and** a knowledge/evaluation core — sequenced so the platform is real before the catalog is huge, and so every critical teaching has evidence it works.
