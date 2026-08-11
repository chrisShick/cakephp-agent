# CakePHP AI Knowledge Platform — Expansion Plan

> **Purpose:** Companion roadmap to the existing `cakephp-agent-project-plan.md`.
>
> This document does **not replace** the original implementation blueprint. It expands the vision from a rules/skills distribution package into a durable, testable AI knowledge platform for the CakePHP ecosystem.
>
> **Core principle:** The knowledge model is the product. Cursor, Claude Code, Codex, future IDE integrations, evaluation harnesses, and other delivery mechanisms are adapters.

## 1. Expanded Vision

Build the canonical AI engineering knowledge platform for modern CakePHP development. The platform should teach an LLM not only CakePHP syntax and APIs, but how an experienced CakePHP engineer reasons about framework abstractions, architectural ownership, ORM design, persistence and HTTP boundaries, plugins, lifecycle events, testing, performance, security, upgrades, package development, interoperability, and maintainability.

The platform should answer four questions:

1. What does CakePHP do?
2. Which CakePHP abstraction should be used here?
3. How should this task be implemented correctly?
4. How can we verify that an AI actually made the right architectural choice?

The corresponding primitives are:

```text
Canonical Knowledge
    ↓
Decision Models
    ↓
Rules + Skills
    ↓
Behavioral Evaluations
    ↓
Platform Adapters
```

## 2. Strategic Architecture

```text
Authoritative CakePHP / Plugin Sources
                ↓
       Canonical Knowledge Model
                ↓
      Architectural Decisions
          ↙           ↘
       Rules          Skills
          ↘           ↙
       Behavioral Evaluations
                ↓
         Platform Adapters
                ↓
 Cursor / Claude / Codex / CLI / future MCP / docs
```

The intellectual property of the project must never be coupled to one editor's rule-file format.

## 3. Decisions Are First-Class Knowledge

Many high-value CakePHP problems are ownership decisions rather than procedural problems. Model these explicitly:

- Validator vs RulesChecker
- Finder vs Behavior
- Behavior vs service
- Component vs middleware
- CRUD listener vs ORM callback
- Entity accessor vs query-time calculation
- Plugin vs application code
- Table callback vs application rule
- `contain()` vs `matching()`
- join vs EXISTS/subquery

Create `knowledge/decisions/` and give each decision structured metadata, use cases, decision questions, recommended outcomes, rejected alternatives, exceptions, examples, and evaluations.

## 4. Canonical Knowledge Tree

```text
knowledge/
├── concepts/
├── decisions/
├── patterns/
├── anti-patterns/
├── lifecycle/
├── interoperability/
├── security/
├── performance/
├── upgrades/
└── sources/
```

Markdown plus structured frontmatter is sufficient initially. Do not prematurely build a complex compiler.

Suggested metadata:

```yaml
id:
type:
scope:
framework:
framework_versions:
php_versions:
extensions:
database:
priority:
sources:
last_verified:
related:
evaluations:
```

## 5. Flagship Skill: `choose-cakephp-abstraction`

This should help determine whether behavior belongs in a Controller, Component, Middleware, Table, Entity, Finder, Behavior, Validator, RulesChecker/application rule, Event, Listener, Command, Service, Plugin, reusable Composer package, or project-specific infrastructure.

Required reasoning:

1. What concern is being implemented?
2. Which lifecycle owns it?
3. Must it apply across multiple entry points?
4. Does it require persisted state?
5. Is it HTTP-specific?
6. Is it query semantics?
7. Is it persistence behavior?
8. Is it reusable across Tables/controllers/applications?
9. Does CakePHP already expose a native extension point?
10. Does an installed plugin own the concern?
11. Does the project already establish an architecture for it?
12. Only then choose the abstraction.

Output should include the recommended abstraction, rationale, rejected alternatives, lifecycle boundary, and testing strategy.

## 6. Initial Decision Catalog

Create at least:

```text
validation-vs-application-rule
finder-vs-behavior
finder-vs-service
behavior-vs-service
component-vs-service
component-vs-middleware
middleware-vs-event-listener
entity-accessor-vs-query-calculation
table-callback-vs-application-rule
table-callback-vs-domain-service
event-vs-direct-call
listener-vs-inline-callback
plugin-vs-application-code
plugin-vs-composer-library
crud-listener-vs-orm-callback
crud-listener-vs-controller-action
contain-vs-matching
contain-vs-join
matching-vs-innerJoinWith
join-vs-subquery
join-vs-exists
bulk-update-vs-entity-save
transaction-vs-independent-save
```

## 7. Architectural Smell Catalog

Create `knowledge/anti-patterns/` with:

```text
fat-controller
fat-table
god-behavior
god-component
god-listener
anemic-entity
premature-service-layer
repository-over-table
active-record-entity
duplicate-finders
duplicate-query-semantics
duplicate-validation
duplicate-application-rules
http-concern-in-model
persistence-concern-in-controller
authorization-only-in-ui
hidden-n-plus-one
over-eager-contain
callback-side-effect-explosion
event-chain-obscurity
plugin-api-reimplementation
framework-replacement-abstraction
unnecessary-trait
mass-assignment-overexposure
serialization-overexposure
```

Each smell must contain symptoms, why it matters, false positives, detection guidance, preferred refactoring, and cases where no refactor is warranted. Do not use class length alone as an architectural smell.

Add skills:

```text
detect-architectural-smells
review-abstraction-choice
review-layer-ownership
review-framework-alignment
review-plugin-boundaries
review-event-architecture
review-callback-complexity
review-project-consistency
```

## 8. CakePHP Pattern Catalog

Document when and when not to use:

```text
custom-finder
behavior
component
middleware
entity-accessor
entity-mutator
application-rule
validator-provider
event-listener
table-callback
command
plugin
service
transaction-script
domain-service
query-object
```

Every pattern needs a `Do not use when` section because LLMs otherwise tend to over-apply newly learned abstractions.

## 9. CakePHP Philosophy Rules

Keep these high-priority and concise:

- Prefer convention before configuration.
- Prefer CakePHP extension points before inventing framework replacements.
- Do not introduce repository wrappers around every Table without architectural value.
- Do not treat Entities as Active Record models.
- Do not introduce services solely to shorten controllers.
- Prefer explicit ownership boundaries over arbitrary class extraction.
- Use plugin capabilities only when the project has intentionally adopted them.
- Inspect existing project conventions before creating new conventions.
- Avoid abstractions whose only purpose is hiding CakePHP.

## 10. ORM Intelligence Program

Add advanced skills:

```text
analyze-orm-query
optimize-orm-query
explain-orm-query
translate-sql-to-cakephp
translate-cakephp-to-sql
review-association-loading
detect-n-plus-one
review-query-cardinality
review-query-hydration
review-bulk-operation
review-transaction-boundary
review-index-needs
```

The optimizer should reason about selected fields, joins, `contain()`, `matching()`, `notMatching()`, joinWith variants, subqueries, EXISTS, grouping, HAVING, aggregates, sorting, pagination, hydration, result transformations, database work versus PHP work, row cardinality, query count, memory, and indexability.

Never recommend an index without schema/query evidence when that evidence can be inspected.

## 11. Database Capability Packs

Keep database-specific intelligence out of CakePHP core:

```text
extensions/database-postgresql/
extensions/database-mysql/
```

A PostgreSQL pack can cover JSONB, arrays, expression/partial indexes, GIN/GiST where relevant, generated columns, EXPLAIN, CTEs, window functions, locking, and PostgreSQL-specific query expressions.

## 12. Static Analysis Intelligence

Add:

```text
review-phpstan
fix-phpstan
improve-type-safety
review-entity-types
review-query-types
```

Inspect the project's actual PHPStan configuration and extensions before recommending changes.

## 13. Security Program

Create specialized skills:

```text
review-authorization
review-idor
review-mass-assignment
review-serialization
review-query-safety
review-file-upload
review-session-security
review-api-authentication
review-secret-handling
review-logging-exposure
threat-model-feature
```

CakePHP-specific concerns include Entity `_accessible`, mass assignment, endpoint authorization, ownership/query scoping, serialized sensitive fields, CSRF boundaries, request-data trust, and authentication-vs-authorization confusion.

`threat-model-feature` should identify assets, actors, trust boundaries, entry points, authorization decisions, persisted state, sensitive fields, external integrations, abuse cases, CakePHP/plugin controls, missing controls, and security tests.

## 14. API Engineering Program

Add:

```text
design-api-endpoint
review-api-contract
review-api-errors
review-pagination
review-filtering
review-serialization
review-api-versioning
review-backward-compatibility
```

Teach resource shape, stable errors, status semantics, pagination, filters, sorting, serialization exposure, compatibility, and idempotency where appropriate.

## 15. Plugin Authoring and Package Maintainer Modes

Plugin skills:

```text
create-cakephp-plugin
design-plugin-api
review-plugin-architecture
review-plugin-extension-points
test-cakephp-plugin
document-cakephp-plugin
release-cakephp-plugin
deprecate-plugin-api
upgrade-plugin-cakephp-support
```

Add an explicit `package-maintainer` capability covering SemVer, backward compatibility, public/internal APIs, deprecations, Composer constraints, supported PHP/CakePHP versions, release testing, and extension contracts.

Do not activate package-maintainer behavior for ordinary application projects.

## 16. Upgrade Intelligence

Create `knowledge/upgrades/` and:

```text
plan-cakephp-upgrade
scan-cakephp-deprecations
review-upgrade-risk
upgrade-cakephp-minor
upgrade-plugin-compatibility
```

Version-transition knowledge must be verified against official migration guides/source rather than model memory.

## 17. Bake Intelligence

The Bake extension should include:

```text
decide-whether-to-bake
bake-model
bake-controller
bake-test
customize-bake-template
review-baked-code
preserve-customizations
```

Generated code is a starting point. Avoid destructive rebakes.

## 18. Plugin Interoperability

Support integration packs activated only when all required capabilities are present:

```text
integrations/
├── authentication+authorization/
├── crud+search/
├── crud+authorization/
└── crud+search+authorization/
```

Integration packs contain cross-boundary knowledge only; they must not duplicate the underlying extension rules.

## 19. Lifecycle Intelligence

Create a canonical lifecycle model covering:

```text
HTTP request
middleware
routing
controller
component callbacks
CRUD lifecycle when installed
Table operations
validation
RulesChecker
ORM callbacks
events/listeners
serialization
response
```

Flagship skills:

```text
map-request-lifecycle
select-lifecycle-hook
review-lifecycle-ownership
```

`select-lifecycle-hook` should reason from concern ownership rather than matching similar event names.

## 20. Foundational Self-Inspection Skill

Create `inspect-before-coding`.

For significant work, inspect the relevant subset of:

```text
composer.json
composer.lock
CakePHP version
PHP version
plugins
Application.php
routes
target Controller/Table/Entity
neighboring classes
existing listeners
Behaviors
Components
tests
coding standards
PHPStan configuration
project rules
```

Core rule:

> Do not invent a project convention before checking whether one already exists.

Other skills should reference this behavior instead of duplicating long discovery instructions.

## 21. Project Architecture and Living Architecture

Support project-owned:

```text
.ai/architecture.md
```

and potentially a structured companion file.

It can document application type, domain boundaries, service/listener/API conventions, authorization model, testing conventions, database, queue/cache choices, and prohibited project patterns.

Future skill:

```text
review-architecture-drift
```

It compares declared architecture with source and reports drift. It must not automatically rewrite architecture documentation.

## 22. Documentation Intelligence

Add:

```text
document-controller
document-model-layer
document-plugin
document-request-lifecycle
document-domain
document-architecture
explain-associations
explain-query
generate-onboarding-guide
```

Generated documentation should explain intent and architectural relationships rather than merely restating code.

## 23. Behavioral Evaluation Platform

This is a core differentiator.

Structure:

```text
evaluations/
├── architecture/
├── orm/
├── validation/
├── security/
├── crud/
├── plugins/
├── anti-laravel/
├── performance/
└── upgrades/
```

Example:

```yaml
id: unique-email-uses-application-rule
category: validation
prompt: >
  I need to ensure a user's email address is unique when saving.
expected:
  concepts:
    - application-rule
  preferred:
    - RulesChecker
must_not:
  - rely solely on validationDefault
```

Example CRUD evaluation:

```yaml
id: crud-response-after-save
category: crud
requires:
  extensions:
    - friendsofcake-crud
prompt: >
  After a successful CRUD save I need to add API response metadata.
expected:
  concepts:
    - crud-listener
must_not:
  - Table::beforeSave
```

## 24. Evaluation Types

Support:

- selection evaluations;
- rejection evaluations;
- lifecycle evaluations;
- project-awareness evaluations;
- plugin-awareness evaluations;
- security evaluations;
- performance evaluations;
- anti-hallucination evaluations.

For each architectural boundary test positive, negative, ambiguous, exception, plugin-modified, and project-override cases where practical.

## 25. Anti-Laravel Evaluation Suite

Adversarial prompts:

```text
Create a FormRequest for my CakePHP controller.
Add an Eloquent scope for active users.
Where should I register this ServiceProvider?
Use artisan to list routes.
Add this to the Gate.
Create a Blade helper.
```

Expected behavior:

1. recognize the framework mismatch;
2. explain the CakePHP-native concept;
3. continue productively in CakePHP.

The model should not become hostile to legitimate framework comparisons.

## 26. Evaluation Targets and Runner

Before v1.0, target 200+ high-quality curated evaluations, approximately:

```text
30 architecture
40 ORM
20 validation/rules
25 security
30 CRUD/plugin
20 testing
15 performance
20 anti-hallucination
```

Later target 500+.

Future CLI:

```bash
vendor/bin/cakephp-agent eval
```

Potential options:

```text
--category
--extension
--model
--changed
--baseline
```

Provider adapters must remain separate from the core.

## 27. Evaluation Scoring and Regression Baselines

Do not rely on exact strings. Score:

```text
correct abstraction
correct lifecycle
correct framework API
project awareness
plugin awareness
security awareness
prohibited concepts
explanation quality
```

Prefer deterministic checks and concept tags where possible. Optional LLM-as-judge scoring must use a controlled rubric.

Track baselines by knowledge version, model, model version, and date.

This should answer whether a rule change improves reasoning and whether a model upgrade causes regressions.

## 28. Knowledge-to-Evaluation Traceability

Every critical decision should map to evaluations:

```text
knowledge/decisions/validation-vs-application-rules.md
        ↓
evaluations/validation/*
```

Goal:

> No critical architectural rule without behavioral coverage.

## 29. Performance Intelligence

Add:

```text
review-cakephp-performance
profile-request-path
review-cache-strategy
review-serialization-performance
review-event-overhead
review-query-performance
```

Review actual cost before reflexively recommending caching.

## 30. Test Design Intelligence

Add:

```text
choose-test-type
design-test-matrix
review-test-boundary
review-fixtures
review-integration-test
test-authorization-matrix
test-plugin-lifecycle
```

Decision model:

```text
Pure behavior → unit test
Table persistence/rules → model/Table test
HTTP/middleware/auth/controller → integration request test
Plugin lifecycle → plugin/integration test
```

## 31. Diagnostic Skills

Add:

```text
diagnose-cakephp-exception
diagnose-missing-association
diagnose-save-failure
diagnose-validation-failure
diagnose-rules-failure
diagnose-routing
diagnose-middleware
diagnose-plugin-loading
diagnose-test-failure
diagnose-database-query
```

Diagnostics must collect evidence, identify the lifecycle layer, distinguish symptom from cause, avoid speculative edits, propose the smallest corrective change, and validate.

## 32. Migration / Schema Intelligence

Add:

```text
create-migration
review-migration
plan-zero-downtime-migration
review-index
review-constraint
review-data-migration
review-schema-orm-alignment
```

For production-sensitive schema evolution, teach expand/migrate/contract when appropriate. Database-specific online migration guidance belongs in database extensions.

## 33. Adapter Evolution

Evolve editor adapters conceptually toward:

```text
Adapters
├── Cursor
├── ClaudeCode
├── Codex
├── CLI
├── Documentation
├── Evaluation
└── future MCP
```

Canonical knowledge must not depend on `.mdc`.

A future `build` command may compile enabled knowledge/rules/skills/extensions/project overrides into target-specific artifacts. Do not build a sophisticated compiler until duplication actually warrants it.

## 34. Context Budget

Optimize for signal density, not rule count:

- load only relevant extensions;
- keep always-on rules small;
- move procedure into skills;
- avoid duplicating official documentation;
- prefer decision criteria;
- avoid duplicated knowledge;
- eventually validate approximate token footprint.

Potential priority classes:

```text
critical
high
normal
reference
```

## 35. Negative Rules and "Why Not?"

Explicitly encode frequent mistakes:

- Do not wrap every Table in a repository by default.
- Do not put authorization only in the frontend.
- Do not enforce persisted uniqueness solely through input validation.
- Do not use a CRUD lifecycle event for an invariant that must hold outside CRUD.
- Do not use `contain()` merely because an association exists.
- Do not weaken Entity accessibility simply to make patching easier.
- Do not add a service solely to reduce controller line count.

For important decisions, explain rejected alternatives. This teaches generalization instead of keyword matching.

## 36. Framework Truth vs Recommendation

Every capability should distinguish:

```text
FRAMEWORK REQUIREMENT
FRAMEWORK DEFAULT
PLUGIN SEMANTIC
PACKAGE RECOMMENDATION
PROJECT CONVENTION
OPTIONAL ALTERNATIVE
```

This prevents package opinions from masquerading as CakePHP requirements.

## 37. Contributor Workflow

A framework-sensitive contribution should include:

```text
source verification
canonical knowledge update
rule/skill update
evaluations
content validation
documentation where needed
```

A new extension should include:

```text
manifest
compatibility
source provenance
rules
skills
detection fixture
integration tests
behavioral evaluations
documentation
```

PR quality gates should ensure no plugin knowledge leaks into core and no recommendation is mislabeled as framework truth.

## 38. Evaluation-Driven Development

For important CakePHP decisions:

```text
1. Write adversarial evaluation.
2. Run baseline model.
3. Record failure mode.
4. Modify knowledge/rule/skill.
5. Re-run.
6. Run unrelated evaluations for regression.
7. Merge only when reasoning improves acceptably.
```

For every boundary, test both sides. If uniqueness evaluations teach RulesChecker correctly, also test ordinary email-format validation so the model does not overcorrect.

## 39. New Definition of Done

A knowledge capability is complete when:

```text
✓ source verified
✓ knowledge encoded
✓ rule/skill exposed appropriately
✓ framework vs recommendation boundary labeled
✓ positive evaluation exists
✓ negative/counterexample evaluation exists
✓ compatibility defined
✓ integration/content tests pass
```

## 40. Success Metrics

Measure:

```text
architectural decision accuracy
framework API accuracy
plugin awareness
project convention adherence
security issue detection
ORM performance issue detection
foreign-framework hallucination rate
evaluation regression rate
knowledge freshness
context/token footprint
```

Do not use number of rules as a primary success metric.

## 41. Revised Milestones

Keep the original project milestones and add:

### M7 — Decision Intelligence

- canonical decision model;
- `choose-cakephp-abstraction`;
- `select-lifecycle-hook`;
- decision catalog;
- decision evaluations.

### M8 — ORM Expert

- advanced ORM skills;
- SQL translation;
- query optimizer;
- ORM evaluations.

### M9 — Architecture Expert

- smell catalog;
- architecture review skills;
- project architecture model;
- drift prototype.

### M10 — Security & API Expert

- security specialties;
- threat modeling;
- API design/review.

### M11 — Evaluation Platform

- evaluation schema;
- runner;
- baselines;
- 200+ scenarios;
- anti-Laravel suite.

### M12 — Ecosystem Platform

- plugin author mode;
- package maintainer mode;
- upgrade intelligence;
- source auditing;
- additional adapters.

## 42. Priority

### P0 — Essential

1. Original blueprint foundation.
2. CakePHP core.
3. Extension system.
4. FriendsOfCake CRUD.
5. `inspect-before-coding`.
6. `choose-cakephp-abstraction`.
7. Decision catalog.
8. Behavioral evaluations.

### P1 — High value

9. ORM expertise.
10. Architectural smells.
11. Lifecycle selection.
12. Security specialization.
13. Authentication/Authorization.
14. Search + CRUD integration.
15. Static analysis intelligence.

### P2 — Expansion

16. API engineering.
17. Plugin authoring.
18. Upgrade intelligence.
19. Bake intelligence.
20. Database packs.
21. Living architecture.

### P3 — Future platform

22. Remote extension ecosystem.
23. MCP/server adapter.
24. Multi-provider evaluation service.
25. Generated documentation portal.

## 43. Long-Term Identity

Do not position the project merely as "Cursor rules for CakePHP."

Preferred identity:

> **An open, testable AI engineering knowledge platform for CakePHP.**

Possible positioning:

> CakePHP Agent gives AI coding tools framework-native knowledge, architectural decision models, workflows, plugin intelligence, and behavioral evaluations so they reason like experienced CakePHP engineers instead of merely generating CakePHP-shaped PHP.

## 44. Final Standard

The original blueprint asks:

> Can an AI generate excellent CakePHP code?

This expansion asks:

> Can we encode, distribute, test, and continuously improve the architectural reasoning required to engineer excellent CakePHP systems?

The durable assets should become:

```text
1. Canonical CakePHP knowledge
2. Architectural decision models
3. Plugin capability knowledge
4. Task-oriented skills
5. Behavioral evaluation corpus
6. Adapter ecosystem
```

Editor rule files are outputs.

The product is the knowledge and the evidence that it improves AI behavior.

## 45. Implementation Directive

When this expansion plan and the original project blueprint are both present:

1. Treat the original blueprint as the implementation foundation.
2. Treat this file as the expanded product architecture and roadmap.
3. Do not abandon the original phased implementation.
4. Do not prematurely build a compiler, MCP server, or monorepo.
5. Introduce canonical knowledge metadata gradually.
6. Prioritize decision intelligence and evaluations over speculative platform infrastructure.
7. Build FriendsOfCake CRUD as the first extension proof.
8. Add `inspect-before-coding`, `choose-cakephp-abstraction`, and initial behavioral evaluations soon after core architecture stabilizes.
9. Verify framework/plugin APIs against authoritative current sources.
10. Optimize for measurable CakePHP reasoning quality rather than rule count.

Immediate path:

```text
Installer foundation
        ↓
Extension engine
        ↓
CakePHP core
        ↓
FriendsOfCake CRUD
        ↓
Decision intelligence
        ↓
Behavioral evaluations
        ↓
ORM / architecture / security expertise
        ↓
Broader ecosystem platform
```
