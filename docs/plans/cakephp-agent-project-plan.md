# CakePHP Agent Rules — Full Project Implementation Blueprint

> **Purpose:** This document is a self-contained implementation specification for building a production-quality, extensible AI coding-rules and skills package for CakePHP 5.x. It is intended to be handed to ChatGPT, Claude, Cursor, Codex, or another capable LLM and used as the authoritative project plan.
>
> **Status:** Initial architecture / greenfield implementation specification
>
> **Primary inspiration:** `pekral/cursor-rules`, but this project must be CakePHP-native rather than a mechanical Laravel-to-CakePHP port.
>
> **Primary target:** CakePHP 5.x and modern PHP.
>
> **Core principle:** Teach AI agents how excellent CakePHP applications are actually designed, not merely how to generate syntactically valid CakePHP code.

---

# 1. Executive Summary

Build a Composer-distributed rules, skills, and agent package that gives AI coding assistants deep, idiomatic knowledge of CakePHP 5.x and allows that knowledge to be extended through optional capability packs for CakePHP plugins, third-party plugins, infrastructure choices, and project-specific architecture.

The system should support at minimum:

- Cursor
- Claude Code
- Codex

The package should contain:

1. Generic PHP engineering rules.
2. CakePHP 5 framework rules.
3. Task-oriented CakePHP skills.
4. Optional extension packs.
5. Automatic Composer dependency detection.
6. Explicit extension enable/disable support.
7. Safe project-specific overrides.
8. Validation tooling for rules, skills, manifests, and generated installations.
9. Tests for installer behavior and extension resolution.
10. Documentation explaining both how to consume the package and how to author extensions.

The first major extension must be **FriendsOfCake CRUD**, because it exercises the extension architecture thoroughly: controller behavior, actions, events, listeners, lifecycle semantics, plugin configuration, and architectural conventions.

This project should not blindly clone Laravel concepts. It should encode CakePHP's own architecture, conventions, ORM semantics, request lifecycle, event system, validation/application-rule distinction, plugin model, testing conventions, and ecosystem.

---

# 2. Product Vision

The project should make an AI coding agent behave like an experienced CakePHP engineer who:

- understands CakePHP conventions before inventing abstractions;
- prefers native CakePHP mechanisms when they solve the problem well;
- understands when custom domain/application services are justified;
- knows the distinction between Tables and Entities;
- understands associations and ORM query composition;
- knows validation vs. application rules;
- understands middleware, components, events, behaviors, commands, plugins, and dependency injection;
- can identify N+1 queries and inefficient ORM patterns;
- writes testable code;
- respects framework boundaries;
- detects optional CakePHP plugins and uses them correctly;
- does not recommend plugin APIs that are absent from the current project;
- does not hallucinate Laravel APIs or architecture into CakePHP applications;
- adapts its behavior to project-specific architecture without destroying framework conventions.

The package should function as a reusable **AI engineering knowledge layer** for CakePHP projects.

---

# 3. Goals

## 3.1 Primary Goals

### G1 — CakePHP-native intelligence

Produce a comprehensive CakePHP 5 ruleset that reflects CakePHP concepts accurately rather than translating Laravel terminology.

### G2 — Workflow-oriented skills

Provide reusable skills that guide an agent through common development tasks such as:

- creating a finder;
- adding an association;
- building an endpoint;
- creating an event listener;
- reviewing a Table class;
- diagnosing an ORM query;
- implementing validation;
- implementing application rules;
- writing tests;
- reviewing security;
- upgrading CakePHP.

### G3 — Extensible capability architecture

Allow optional extensions to contribute:

- rules;
- skills;
- agents;
- documentation;
- detection logic;
- compatibility constraints;
- dependencies on other extensions.

### G4 — Composer-aware discovery

Inspect the target project's Composer dependencies and automatically enable relevant extension packs.

Example:

```json
{
  "require": {
    "cakephp/cakephp": "^5.4",
    "cakephp/authentication": "^3.0",
    "cakephp/authorization": "^3.0",
    "friendsofcake/crud": "^7.0"
  }
}
```

The installer should understand that Authentication, Authorization, and FriendsOfCake CRUD capabilities are available.

### G5 — Project-safe installation

Never overwrite designated project-owned rules by default.

### G6 — Multi-agent support

Install rules and skills into the appropriate locations for Cursor, Claude Code, and Codex while keeping the content source canonical.

### G7 — Verifiable quality

Rules and skills must be lintable and testable. Extension manifests must be schema-validated. Installer behavior must be covered by automated tests.

---

# 4. Non-Goals

The initial project must **not**:

- become a CakePHP framework fork;
- generate application code during installation;
- require users to adopt a specific domain architecture such as DDD;
- require FriendsOfCake CRUD;
- assume every application is API-only;
- assume a specific database;
- prescribe project-specific folder conventions as if they were official CakePHP requirements;
- bundle every known CakePHP plugin in v1;
- automatically infer subjective architecture from weak signals;
- modify application source files;
- silently modify a project's Composer dependencies;
- replace official CakePHP documentation.

The system provides agent knowledge and workflows; it does not own the application.

---

# 5. Foundational Design Principles

## 5.1 Convention before invention

CakePHP is convention-oriented. Rules should teach agents to inspect and follow CakePHP conventions and existing project patterns before creating new abstractions.

## 5.2 Framework semantics before project preferences

Content must distinguish among:

1. PHP language / engineering principles.
2. CakePHP framework semantics.
3. Plugin semantics.
4. Package recommendations.
5. Project-specific conventions.

Do not present category 4 or 5 as if it were category 2.

## 5.3 Prefer composition over one giant rule file

Knowledge should be composed from independent packs.

## 5.4 Detect objective capabilities automatically

Composer packages and versions are objective signals and may be auto-detected.

Subjective architectural choices should generally require explicit configuration.

## 5.5 Skills are workflows; rules are persistent guidance

A rule explains what "good" looks like.

A skill explains how to execute a particular development task.

## 5.6 Project instructions have highest precedence

Precedence:

```text
Project-specific rules
        ↓
Enabled extension rules
        ↓
CakePHP core rules
        ↓
Generic PHP / engineering rules
```

If conflict resolution must be expressed numerically:

```text
project > extension > cakephp > php > generic
```

## 5.7 Minimal contamination

An extension that is not detected or enabled must not influence the generated agent context.

## 5.8 Source authority

Framework-sensitive rules must be grounded primarily in:

- CakePHP official documentation;
- CakePHP source code where necessary;
- official CakePHP plugin documentation;
- upstream plugin documentation/source repositories.

Avoid deriving framework behavior from blogs when primary sources are available.

---

# 6. Proposed Project Names

The implementation may choose a final name later. Candidate names:

- `cakephp-agent-rules`
- `cakephp-agent`
- `cakephp-ai-rules`
- `cakephp-dev-agent`
- `cakephp-engineering-rules`

For this specification, use the placeholder:

```text
cakephp-agent
```

Example package name:

```json
"name": "vendor/cakephp-agent"
```

Do not hard-code a final public vendor namespace until the repository owner selects one.

---

# 7. High-Level Architecture

```text
                     ┌──────────────────────┐
                     │  Engineering / PHP   │
                     │        Core          │
                     └──────────┬───────────┘
                                │
                                ▼
                     ┌──────────────────────┐
                     │     CakePHP 5 Core   │
                     │ rules + skills       │
                     └──────────┬───────────┘
                                │
              ┌─────────────────┼─────────────────┐
              │                 │                 │
              ▼                 ▼                 ▼
     ┌────────────────┐ ┌────────────────┐ ┌────────────────┐
     │ Authentication │ │ Authorization  │ │ FoC CRUD       │
     │ extension      │ │ extension      │ │ extension      │
     └────────────────┘ └────────────────┘ └────────────────┘
              │                 │                 │
              └─────────────────┼─────────────────┘
                                ▼
                     ┌──────────────────────┐
                     │ Project-specific     │
                     │ rules / overrides    │
                     └──────────────────────┘
```

---

# 8. Repository Structure

Target structure:

```text
cakephp-agent/
├── .github/
│   ├── workflows/
│   │   ├── ci.yml
│   │   ├── static-analysis.yml
│   │   ├── content-validation.yml
│   │   └── release.yml
│   └── ISSUE_TEMPLATE/
│
├── agents/
│   ├── cakephp-code-reviewer.md
│   ├── cakephp-security-reviewer.md
│   ├── cakephp-orm-reviewer.md
│   └── cakephp-architecture-reviewer.md
│
├── bin/
│   └── cakephp-agent
│
├── docs/
│   ├── architecture.md
│   ├── installation.md
│   ├── editors.md
│   ├── rules-authoring.md
│   ├── skills-authoring.md
│   ├── extensions.md
│   ├── extension-manifest.md
│   ├── project-overrides.md
│   ├── compatibility.md
│   └── contributing.md
│
├── extensions/
│   ├── cakephp-authentication/
│   ├── cakephp-authorization/
│   ├── cakephp-bake/
│   ├── cakephp-migrations/
│   ├── friendsofcake-crud/
│   ├── friendsofcake-search/
│   └── ...
│
├── rules/
│   ├── engineering/
│   ├── php/
│   └── cakephp/
│
├── skills/
│   ├── engineering/
│   ├── php/
│   └── cakephp/
│
├── schemas/
│   ├── extension-manifest.schema.json
│   └── project-config.schema.json
│
├── src/
│   ├── Command/
│   ├── Configuration/
│   ├── Discovery/
│   ├── Editor/
│   ├── Extension/
│   ├── Filesystem/
│   ├── Installer/
│   ├── Manifest/
│   └── Validation/
│
├── tests/
│   ├── Command/
│   ├── Configuration/
│   ├── Discovery/
│   ├── Editor/
│   ├── Extension/
│   ├── Installer/
│   ├── Integration/
│   └── fixtures/
│
├── composer.json
├── phpunit.xml
├── phpstan.neon
├── README.md
├── CONTRIBUTING.md
├── CHANGELOG.md
├── SECURITY.md
└── LICENSE
```

---

# 9. Knowledge Layers

## 9.1 Generic Engineering

Examples:

```text
rules/engineering/
├── clean-code.mdc
├── code-review.mdc
├── security.mdc
├── testing.mdc
├── database.mdc
├── api-design.mdc
├── git.mdc
└── refactoring.mdc
```

These should be framework-neutral.

## 9.2 PHP

```text
rules/php/
├── php.mdc
├── type-system.mdc
├── exceptions.mdc
├── dependencies.mdc
├── composer.mdc
├── security.mdc
├── performance.mdc
└── testing.mdc
```

These should target the supported PHP versions and avoid framework assumptions.

## 9.3 CakePHP

```text
rules/cakephp/
├── architecture.mdc
├── conventions.mdc
├── application.mdc
├── dependency-injection.mdc
├── configuration.mdc
├── controllers.mdc
├── components.mdc
├── middleware.mdc
├── requests-responses.mdc
├── routing.mdc
├── serialization.mdc
├── orm.mdc
├── tables.mdc
├── entities.mdc
├── associations.mdc
├── finders.mdc
├── behaviors.mdc
├── validation.mdc
├── application-rules.mdc
├── transactions.mdc
├── events.mdc
├── commands.mdc
├── plugins.mdc
├── cache.mdc
├── logging.mdc
├── errors-exceptions.mdc
├── testing.mdc
├── fixtures.mdc
├── migrations.mdc
├── security.mdc
└── performance.mdc
```

Keep files focused. Avoid 5,000-line omnibus rules.

---

# 10. CakePHP Core Knowledge Requirements

The CakePHP core rules must accurately cover the following.

## 10.1 Application architecture

The agent should understand:

- `Application.php`;
- bootstrap lifecycle;
- middleware queue;
- routing;
- services / dependency injection;
- plugins;
- controllers;
- components;
- Tables;
- Entities;
- behaviors;
- events;
- commands;
- templates/views when relevant.

## 10.2 Controllers

Teach agents to:

- keep controllers oriented around HTTP/application-flow concerns;
- use request and response objects correctly;
- avoid putting substantial persistence/domain logic directly in controllers;
- use components for reusable controller-level concerns when appropriate;
- understand CakePHP callbacks;
- respect existing project patterns;
- avoid inventing Laravel FormRequests, facades, service providers, etc.

## 10.3 ORM

The ORM rules should be among the deepest in the package.

Cover:

- Table registry / TableLocator;
- `Table` responsibilities;
- `Entity` responsibilities;
- `SelectQuery`;
- query expressions;
- custom finders;
- association loading;
- `contain()`;
- `matching()`;
- `notMatching()`;
- `innerJoinWith()`;
- `leftJoinWith()`;
- field selection;
- hydration;
- result sets;
- pagination;
- aggregate queries;
- subqueries;
- transactions;
- atomic saves;
- associated saves;
- mass assignment;
- dirty state;
- virtual fields;
- accessors/mutators;
- query efficiency;
- N+1 prevention;
- bulk operations and their lifecycle tradeoffs.

## 10.4 Tables vs. Entities

Rules must explicitly teach:

### Table responsibilities

Typically:

- repository/query access;
- associations;
- validation configuration;
- application rules;
- persistence;
- finders;
- table-level callbacks;
- reusable persistence behavior.

### Entity responsibilities

Typically:

- record state;
- field accessibility;
- accessors/mutators;
- virtual properties;
- presentation-friendly derived values where appropriate;
- entity-specific behavior when justified.

Never teach an Active Record mental model where Entities issue their own queries by default.

## 10.5 Validation vs. application rules

This distinction must be strongly encoded.

Validation:

```text
Is incoming data structurally/formally valid?
```

Examples:

- required field;
- email format;
- scalar shape;
- allowed value;
- length constraint.

Application rules:

```text
Is this entity valid relative to application/persisted state?
```

Examples:

- uniqueness;
- valid foreign relationship;
- state-dependent business constraint.

The agent should select the correct layer rather than treating all validation as one mechanism.

## 10.6 Associations

Teach:

- `belongsTo`;
- `hasOne`;
- `hasMany`;
- `belongsToMany`;
- foreign keys;
- binding keys;
- junction tables;
- save strategies;
- cascading;
- dependent records;
- strategy implications;
- query-loading implications.

## 10.7 Events

Teach the CakePHP event system independently from plugin events.

The agent should distinguish among:

- application events;
- controller callbacks;
- ORM callbacks;
- event listeners;
- plugin-emitted events.

## 10.8 Middleware

Teach PSR-style request/response flow and CakePHP middleware composition.

Avoid treating middleware as a replacement for every cross-cutting concern.

## 10.9 Dependency injection

Teach currently supported CakePHP DI/service patterns accurately.

Rules must be version-aware where CakePHP behavior changed between minor releases.

## 10.10 Plugins

Teach:

- Composer installation;
- plugin loading;
- plugin namespaces;
- plugin routes/bootstrap/services;
- plugin isolation;
- application/plugin interaction.

## 10.11 Commands

Teach modern `bin/cake` command patterns.

Avoid legacy Shell APIs in new CakePHP 5 code.

## 10.12 Testing

Support PHPUnit-oriented CakePHP testing.

Cover:

- unit tests;
- Table tests;
- integration tests;
- controller/request tests;
- fixtures;
- factories only when the project has the relevant library;
- assertions;
- deterministic test data;
- test database behavior.

Do not impose Pest unless it is explicitly selected by the project.

---

# 11. Skills Architecture

Every skill should live in a dedicated folder.

Example:

```text
skills/cakephp/create-finder/
└── SKILL.md
```

A skill must contain:

1. purpose;
2. trigger/use cases;
3. prerequisites;
4. discovery steps;
5. implementation workflow;
6. framework decision rules;
7. anti-patterns;
8. validation steps;
9. testing expectations;
10. completion criteria.

---

# 12. Initial CakePHP Skill Catalog

Create at least the following first-party skills.

## Architecture / discovery

- `analyze-cakephp-project`
- `map-request-lifecycle`
- `review-cakephp-architecture`
- `identify-framework-boundary`
- `review-plugin-usage`

## ORM / model layer

- `create-table-entity`
- `create-finder`
- `add-association`
- `modify-association`
- `create-behavior`
- `add-validation`
- `add-application-rule`
- `diagnose-save-failure`
- `diagnose-orm-query`
- `optimize-orm-query`
- `diagnose-n-plus-one`
- `implement-transaction`
- `review-table`
- `review-entity`
- `review-model-layer`

## HTTP / application

- `create-controller-action`
- `create-api-endpoint`
- `create-component`
- `create-middleware`
- `modify-routing`
- `create-event-listener`
- `create-command`
- `implement-json-response`
- `review-controller`
- `review-request-lifecycle`

## Testing

- `create-table-test`
- `create-entity-test`
- `create-controller-test`
- `create-integration-test`
- `create-command-test`
- `debug-failing-cakephp-test`

## Quality / operations

- `cakephp-code-review`
- `cakephp-security-review`
- `cakephp-performance-review`
- `cakephp-upgrade-review`
- `cakephp-dependency-review`
- `cakephp-production-readiness`

---

# 13. Skill Behavior Example: `create-finder`

The skill should instruct an agent to:

1. Locate the target Table class.
2. Inspect existing custom finders.
3. Inspect associations used by the desired query.
4. Determine required CakePHP version.
5. Determine the expected query type/signature.
6. Prefer query composition over duplicated filtering logic.
7. Use `aliasField()` where ambiguity is possible.
8. Avoid unnecessary `contain()`.
9. Avoid selecting fewer columns if downstream code requires hydrated associations unless intentional.
10. Preserve composability.
11. Add focused tests.
12. Run applicable static analysis/test commands.
13. Explain performance implications when meaningful.

Completion criteria:

- Finder follows CakePHP conventions.
- Finder can compose with other query conditions.
- Tests verify expected records and edge cases.
- No N+1 behavior is introduced.
- No Laravel/Eloquent APIs appear.

---

# 14. Extension System

Extensions are capability packs.

Each extension can contain:

```text
extensions/<extension-id>/
├── manifest.json
├── README.md
├── rules/
├── skills/
└── agents/
```

All directories except `manifest.json` may be optional.

---

# 15. Extension Manifest Specification

Example:

```json
{
  "$schema": "../../schemas/extension-manifest.schema.json",
  "id": "friendsofcake-crud",
  "name": "FriendsOfCake CRUD",
  "description": "Rules and workflows for applications using FriendsOfCake CRUD.",
  "version": "1.0",
  "type": "composer-package",
  "detect": {
    "composer": [
      {
        "package": "friendsofcake/crud",
        "constraint": "^7.0"
      }
    ]
  },
  "requires": {
    "cakephp": "^5.0"
  },
  "dependsOn": [],
  "conflictsWith": [],
  "rules": [
    "rules/*.mdc"
  ],
  "skills": [
    "skills/*"
  ],
  "agents": [],
  "defaultEnabledWhenDetected": true
}
```

The schema should support:

- stable extension ID;
- display name;
- description;
- extension format version;
- extension type;
- detection rules;
- CakePHP compatibility;
- PHP compatibility if needed;
- extension dependencies;
- extension conflicts;
- contributed rules;
- contributed skills;
- contributed agents;
- auto-enable behavior.

---

# 16. Extension Types

Support at least:

## 16.1 Composer package extension

Detected through Composer.

Examples:

- `cakephp/authentication`;
- `cakephp/authorization`;
- `friendsofcake/crud`.

## 16.2 Architecture extension

Explicitly selected.

Examples:

- `architecture-api-only`;
- `architecture-domain-services`;
- `architecture-multi-tenant`.

These should **not** normally be auto-inferred from weak source-code heuristics.

## 16.3 Infrastructure extension

Can be explicit or objectively detected where safe.

Examples:

- PostgreSQL;
- Redis;
- Docker.

Keep infrastructure packs separate from CakePHP framework truth.

---

# 17. Extension Detection

Create a discovery subsystem.

Suggested classes:

```text
src/Discovery/
├── ProjectRootLocator.php
├── ComposerFileLoader.php
├── ComposerLockLoader.php
├── ComposerPackageIndex.php
├── CakePhpVersionDetector.php
├── PhpVersionDetector.php
└── ExtensionDetector.php
```

Detection should prefer `composer.lock` for resolved versions when available.

Fallback:

1. `composer.lock`;
2. root `composer.json` constraints;
3. explicit configuration.

Do not execute arbitrary Composer scripts merely to detect packages.

---

# 18. Extension Resolution

Create:

```text
src/Extension/
├── Extension.php
├── ExtensionRegistry.php
├── ExtensionResolver.php
├── ExtensionDependencyGraph.php
├── ExtensionConflict.php
└── ExtensionCollection.php
```

Resolver responsibilities:

1. Load manifests.
2. Validate manifests.
3. Detect matching packages.
4. Add explicitly enabled extensions.
5. Remove explicitly disabled extensions.
6. Resolve transitive extension dependencies.
7. enforce compatibility;
8. detect dependency cycles;
9. detect conflicts;
10. produce deterministic ordered result.

Suggested priority/order:

```text
engineering
php
cakephp
extensions by dependency order
project
```

---

# 19. Project Configuration

Allow optional configuration in root `composer.json`:

```json
{
  "extra": {
    "cakephp-agent": {
      "auto-install": false,
      "editor": "cursor",
      "extensions": {
        "enable": [
          "architecture-api-only"
        ],
        "disable": [
          "cakephp-bake"
        ]
      }
    }
  }
}
```

Also consider supporting:

```text
.cakephp-agent.json
```

If both exist, define precedence explicitly.

Recommended precedence:

```text
CLI flags
    >
.cakephp-agent.json
    >
composer.json extra
    >
automatic detection
    >
defaults
```

---

# 20. CLI

Target commands:

```bash
vendor/bin/cakephp-agent help
vendor/bin/cakephp-agent install --editor=cursor
vendor/bin/cakephp-agent install --editor=claude
vendor/bin/cakephp-agent install --editor=codex
vendor/bin/cakephp-agent install --editor=all

vendor/bin/cakephp-agent detect
vendor/bin/cakephp-agent extensions
vendor/bin/cakephp-agent validate
vendor/bin/cakephp-agent doctor
```

Install options:

```bash
--force
--symlink
--prune
--extension=<id>
--without=<id>
--dry-run
--verbose
```

Possible later command:

```bash
vendor/bin/cakephp-agent explain
```

which explains why each extension is active.

---

# 21. Example CLI Output

```text
CakePHP Agent

Project:
  /path/to/application

Detected:
  PHP 8.x
  CakePHP 5.x

Composer capabilities:
  ✓ cakephp/authentication
  ✓ cakephp/authorization
  ✓ friendsofcake/crud

Enabled knowledge packs:
  ✓ engineering
  ✓ php
  ✓ cakephp
  ✓ cakephp-authentication
  ✓ cakephp-authorization
  ✓ friendsofcake-crud

Target:
  Cursor

Installing:
  .cursor/rules/
  .cursor/skills/

Project-owned files preserved.
Installation complete.
```

---

# 22. Editor Adapters

Do not hard-wire editor paths into installer logic.

Create adapters:

```text
src/Editor/
├── EditorAdapterInterface.php
├── CursorAdapter.php
├── ClaudeAdapter.php
├── CodexAdapter.php
└── EditorRegistry.php
```

Interface should expose concepts such as:

```php
interface EditorAdapterInterface
{
    public function id(): string;

    public function ruleTargets(Project $project): array;

    public function skillTargets(Project $project): array;

    public function agentTargets(Project $project): array;

    public function supportsRules(): bool;

    public function supportsSkills(): bool;

    public function supportsAgents(): bool;
}
```

The actual API may differ, but keep editor-specific behavior encapsulated.

---

# 23. Installation Safety

Default behavior:

- copy missing managed files;
- never overwrite project-owned override files;
- do not delete unknown files;
- `--force` overwrites package-managed files;
- `--prune` removes package-managed files no longer present;
- provide `--dry-run`;
- produce deterministic output.

Maintain an installation state file, for example:

```text
.cakephp-agent.lock.json
```

This file may record:

- package version;
- enabled extensions;
- installed managed files;
- hashes;
- editor targets;
- timestamp.

This enables safe pruning without deleting user-authored files.

Do not use filename guessing as the sole prune mechanism.

---

# 24. Project-Owned Rules

Reserve a safe location that the installer never overwrites.

Example:

```text
.cursor/rules/project/
```

Or a canonical source:

```text
.ai/project/
├── rules/
├── skills/
└── architecture.md
```

The first version should pick one clear convention and document it.

Project rules should be loaded last / have highest precedence where the target editor permits it.

---

# 25. FriendsOfCake CRUD Reference Extension

The first extension must be:

```text
extensions/friendsofcake-crud/
```

Proposed structure:

```text
extensions/friendsofcake-crud/
├── manifest.json
├── README.md
│
├── rules/
│   ├── crud.mdc
│   ├── controller-design.mdc
│   ├── actions.mdc
│   ├── events.mdc
│   ├── listeners.mdc
│   ├── configuration.mdc
│   ├── orm-boundaries.mdc
│   ├── api.mdc
│   └── testing.mdc
│
└── skills/
    ├── analyze-crud-controller/
    ├── create-crud-controller/
    ├── create-crud-listener/
    ├── modify-crud-action/
    ├── select-crud-event/
    ├── debug-crud-request/
    ├── migrate-controller-to-crud/
    ├── review-crud-controller/
    └── test-crud-controller/
```

---

# 26. CRUD Architectural Rules

The CRUD pack must teach:

- CRUD owns common controller action boilerplate.
- Avoid manually recreating CRUD action flow unnecessarily.
- Prefer CRUD configuration for simple behavior changes.
- Prefer CRUD lifecycle events for lifecycle-specific customization.
- Prefer reusable listeners for reusable behavior.
- Keep persistence/domain concerns in the model/domain layer where appropriate.
- Do not move ORM invariants into controller lifecycle handlers merely because a CRUD event exists.
- Understand where CRUD lifecycle and CakePHP ORM lifecycle differ.

Decision order:

```text
Can standard CRUD configuration express it?
        ↓ no
Is it tied to CRUD action lifecycle?
        ↓ yes
Use a CRUD listener/event.
        ↓
Does the logic represent persistence/domain invariants?
        ↓ yes
Move that portion into Table / application rule / behavior / domain service.
```

---

# 27. CRUD Event vs. ORM Event Rule

This distinction is mandatory.

Example decision guidance:

```text
Need to adjust an HTTP/API response around a CRUD save?
    → Crud lifecycle event/listener

Need to ensure all persistence paths normalize a field?
    → Table/ORM callback or domain persistence mechanism

Need to validate incoming field format?
    → Validator

Need to enforce stateful persistence constraint?
    → RulesChecker/application rule

Need to change CRUD action query before pagination?
    → CRUD action event/listener or configured finder, depending on concern

Need reusable query semantics outside CRUD?
    → custom Table finder
```

The CRUD rules must explicitly warn that similarly named CRUD and ORM events are not interchangeable ownership boundaries.

---

# 28. CRUD Listener Convention

Adopt an opinionated but clearly labeled package convention for application-owned CRUD listeners.

Preferred default:

```text
src/
├── Controller/
│   ├── PeopleController.php
│   ├── Admin/
│   │   └── PeopleController.php
│   └── Api/
│       └── PeopleController.php
│
└── Listener/
    ├── PeopleListener.php
    ├── Admin/
    │   └── PeopleListener.php
    └── Api/
        └── PeopleListener.php
```

Path mirroring:

```text
src/Controller/Api/PeopleController.php
                 ↓
src/Listener/Api/PeopleListener.php
```

Benefits:

- discoverability;
- predictable AI navigation;
- clear controller/listener relationship;
- less inline callback clutter;
- easier testing;
- easier reuse.

Important:

This is a **recommended extension convention**, not an official CakePHP requirement.

Projects must be able to override this convention through project rules.

---

# 29. CRUD `create-crud-listener` Skill

Workflow:

1. Identify the target controller.
2. Determine its relative path under `src/Controller`.
3. Look for the mirrored listener under `src/Listener`.
4. Inspect existing listener conventions before creating anything.
5. Inspect the controller's CRUD configuration.
6. Determine the specific behavior requested.
7. Select the narrowest appropriate CRUD lifecycle event.
8. Verify that the concern belongs in CRUD lifecycle rather than Table/Entity/domain code.
9. Reuse an existing listener if appropriate.
10. Create a new listener only when justified.
11. Register the listener using the project's established pattern.
12. Implement only needed events.
13. Keep methods narrowly scoped.
14. Add tests.
15. Verify the request lifecycle.
16. Run static analysis and tests.
17. Summarize why the selected event/layer is correct.

Anti-patterns:

- giant listener classes;
- business invariants enforced only at controller level;
- duplicate listeners for identical reusable behavior;
- anonymous closures for substantial lifecycle behavior;
- using ORM callbacks for response shaping;
- using CRUD callbacks for rules that must apply outside CRUD.

---

# 30. CRUD Event Selection Skill

Create:

```text
skills/select-crud-event/
```

The skill should reason from intent, not event-name similarity.

Example matrix:

| Need | Preferred location |
|---|---|
| Change base query for CRUD index | CRUD query lifecycle / configured finder |
| Reusable query semantics across app | Table finder |
| Validate request payload shape | CakePHP validation |
| Enforce persistence-state invariant | application rule |
| Add response metadata | CRUD listener |
| Add related data for rendering | appropriate CRUD listener/configuration |
| Normalize field for every persistence path | model/domain layer |
| Redirect after a CRUD action | CRUD redirect lifecycle |
| Cross-controller reusable CRUD behavior | reusable CRUD listener |

The final implementation should verify exact event APIs against the supported CRUD version before encoding them.

---

# 31. Additional First-Party Extensions

After CRUD works, implement in approximately this order:

## Tier 1

1. `cakephp-authentication`
2. `cakephp-authorization`
3. `cakephp-migrations`
4. `cakephp-bake`
5. `friendsofcake-search`

## Tier 2

Potential:

- `friendsofcake-crud-view`
- `friendsofcake-crud-json-api`
- `cakephp-debug-kit`
- common queue plugin(s)
- common test utility plugin(s)

Every extension must justify its existence. Do not create thin packs that add no useful agent behavior.

---

# 32. Authentication Extension

Should teach:

- authentication middleware placement;
- identity propagation;
- authenticators;
- identifiers;
- unauthenticated handling;
- request identity access;
- separation of authentication from authorization;
- tests.

Skills could include:

- `configure-authentication`
- `add-authenticator`
- `debug-authentication`
- `review-authentication-flow`

Do not conflate "who is this?" with "may they do this?"

---

# 33. Authorization Extension

Should teach:

- policy-oriented authorization;
- resource ownership;
- request identity;
- query scoping where appropriate;
- endpoint authorization;
- defense against IDOR-style failures;
- test matrices.

Skills:

- `create-policy`
- `authorize-controller-action`
- `scope-query`
- `review-authorization`
- `debug-authorization`

The extension must be based on the actual installed CakePHP Authorization plugin API.

---

# 34. FriendsOfCake Search Extension

Should teach:

- Search plugin concepts;
- search manager/filter configuration;
- finder integration;
- PRG behavior where relevant;
- integration with CRUD when both extensions are enabled.

Important feature:

Extensions should be able to contribute **integration rules** when another extension is present.

Example:

```text
friendsofcake-search + friendsofcake-crud
```

may activate a small integration capability pack.

This should not require copy/pasting the same rules into each plugin.

---

# 35. Extension Interoperability

Support conditional integration packs.

Possible manifest concept:

```json
{
  "activateWhenAllExtensionsPresent": [
    "friendsofcake-crud",
    "friendsofcake-search"
  ]
}
```

Or model integrations separately:

```text
integrations/
└── friendsofcake-crud+search/
```

Do not implement until base extension resolution is stable, but architect for it.

---

# 36. Rules File Contract

Every `.mdc` rule should follow a consistent structure.

Suggested content:

```markdown
---
description: ...
globs:
  - ...
alwaysApply: false
---

# Purpose

# Framework semantics

# Required behavior

# Preferred patterns

# Decision rules

# Anti-patterns

# Examples

# Review checklist
```

Adapt frontmatter to actual target-editor format where necessary.

Prefer concise, enforceable rules over essay-style documentation.

---

# 37. Rule Quality Criteria

A good rule:

- states the architectural boundary;
- explains when it applies;
- tells the agent what to inspect;
- gives decision criteria;
- names anti-patterns;
- uses correct CakePHP APIs;
- avoids absolute statements when CakePHP permits multiple legitimate approaches;
- does not encode obsolete APIs;
- does not include unnecessary prose.

Bad:

```text
Always use services for business logic.
```

Better:

```text
Keep HTTP orchestration in controllers. Before introducing an application
service, determine whether the behavior naturally belongs in an existing
Table, Entity, Behavior, component, listener, or other established project
abstraction. Introduce a service when it represents reusable application or
domain orchestration that does not naturally belong to those framework
objects.
```

---

# 38. Skill File Contract

Suggested:

```markdown
---
name: create-finder
description: Create or modify an idiomatic CakePHP custom finder.
---

# Objective

# Use when

# Do not use when

# Inputs to discover

# Workflow

## 1. Inspect existing project structure

## 2. Locate the Table

...

# Framework decisions

# Anti-patterns

# Validation

# Completion criteria
```

Skills should tell an LLM to inspect before editing.

---

# 39. Agents

For platforms supporting dedicated agents/subagents, initially provide:

## `cakephp-code-reviewer`

Checks:

- CakePHP conventions;
- architecture boundaries;
- ORM usage;
- plugin usage;
- testing;
- error handling;
- maintainability.

## `cakephp-orm-reviewer`

Checks:

- N+1;
- association loading;
- query composition;
- hydration;
- field selection;
- query count;
- indexes only when schema evidence supports recommendation;
- bulk operation lifecycle differences.

## `cakephp-security-reviewer`

Checks:

- authorization;
- IDOR;
- mass assignment;
- unsafe query construction;
- CSRF where applicable;
- authentication assumptions;
- serialization exposure;
- secrets/configuration;
- file uploads if relevant.

## `cakephp-architecture-reviewer`

Checks:

- framework boundaries;
- controller bloat;
- Table/Entity misuse;
- excessive custom abstractions;
- duplicated behavior;
- plugin misuse.

---

# 40. Versioning Strategy

The package should separately track:

1. package version;
2. supported CakePHP range;
3. extension supported package ranges.

Example:

```text
cakephp-agent 1.x
  supports CakePHP >=5.0 <6.0
```

Do not assume all CakePHP 5 minor releases have identical APIs.

Rules requiring a newer minor version should:

- specify version requirements;
- be conditionally installed if necessary;
- or clearly describe fallback behavior.

Extension manifests should enforce package compatibility.

---

# 41. Source Research Requirements

Before implementing a framework-sensitive rule or skill:

1. Read the relevant current CakePHP 5 official documentation.
2. Inspect CakePHP source when documentation is ambiguous.
3. For plugins, read the plugin's official docs and current supported branch.
4. Confirm package/version compatibility in Composer metadata.
5. Avoid relying on stale Stack Overflow answers or old CakePHP 3/4 examples.

Each rule/skill does not necessarily need citations in the installed content, but maintain a contributor-facing source map.

Suggested:

```text
docs/sources/
├── cakephp.md
├── authentication.md
├── authorization.md
└── friendsofcake-crud.md
```

This makes future upgrades auditable.

---

# 42. Source Mapping

Maintain metadata such as:

```yaml
rule: cakephp/validation
sources:
  - https://book.cakephp.org/5.x/orm/validation.html
verified_against:
  cakephp: 5.x
last_reviewed: YYYY-MM-DD
```

This can be implemented as frontmatter or separate metadata.

Purpose:

- reduce stale rules;
- simplify version upgrades;
- enable automated stale-content reporting later.

---

# 43. Testing Strategy

## 43.1 Unit tests

Test:

- Composer package indexing;
- semantic version matching;
- extension detection;
- explicit enable/disable;
- dependency resolution;
- conflict detection;
- cycle detection;
- editor target paths;
- manifest parsing;
- configuration precedence.

## 43.2 Integration tests

Create fixture projects with different `composer.json` / `composer.lock` combinations.

Examples:

```text
tests/fixtures/projects/
├── cakephp-only/
├── cakephp-auth/
├── cakephp-authz/
├── cakephp-crud/
├── cakephp-crud-search/
├── cakephp-no-lock/
├── incompatible-crud/
└── project-overrides/
```

Test actual installation into temporary directories.

## 43.3 Content tests

Validate:

- required frontmatter;
- unique skill IDs;
- valid manifest references;
- referenced files exist;
- no duplicate extension IDs;
- no unresolved dependencies;
- no known Laravel terms in CakePHP core except where explicitly discussed.

Potential guard list in CakePHP core:

```text
artisan
Eloquent
FormRequest
ServiceProvider
Gate
Blade
Livewire
```

Do not blindly ban the words globally; a migration/comparison document might legitimately mention them.

## 43.4 Golden installation tests

For representative fixture projects, snapshot expected installed file sets.

This is particularly useful for extension-resolution regressions.

---

# 44. Static Analysis and Code Quality

Use modern PHP tooling.

Recommended:

- PHPUnit;
- PHPStan;
- PHP_CodeSniffer with appropriate standard or CakePHP conventions;
- Composer validation;
- JSON Schema validation.

Rector may be used only if it provides meaningful maintenance value.

Do not copy every tool from the inspiration repository simply because it exists there.

---

# 45. CI

Minimum GitHub Actions jobs:

## `tests`

Matrix over supported PHP versions where practical.

## `static-analysis`

Run PHPStan and coding standards.

## `content-validation`

Validate:

- rule files;
- skill files;
- manifests;
- extension dependency graph;
- source metadata.

## `integration`

Install into fixture projects for Cursor/Claude/Codex layouts.

## `composer`

Run:

```bash
composer validate --strict
```

---

# 46. Security

The package is installed into developer repositories and potentially executes as a Composer plugin. Treat this as supply-chain-sensitive software.

Requirements:

- no arbitrary remote code execution;
- no downloading executable scripts during install;
- no modifying source code;
- no shelling out unnecessarily;
- sanitize filesystem paths;
- prevent path traversal from extension manifests;
- validate manifest paths remain inside extension root;
- never expose secrets from project config;
- do not print environment variables;
- avoid executing application code during detection;
- use read-only Composer metadata inspection where possible.

Composer plugin auto-install behavior should be opt-in.

---

# 47. Inspiration Repository Migration Strategy

Do **not** copy the existing `pekral/cursor-rules` repository wholesale and run search/replace.

Perform an inventory.

For every existing rule and skill classify:

```text
KEEP
ADAPT
REWRITE
REMOVE
REPLACE
```

## KEEP

Generic engineering workflows with no Laravel coupling.

## ADAPT

Mostly generic content containing a few Laravel commands/examples.

## REWRITE

Workflows whose architecture is framework-dependent.

## REMOVE

Laravel-ecosystem-specific capabilities with no CakePHP equivalent.

## REPLACE

Laravel concept is useful, but CakePHP has a fundamentally different mechanism.

Example:

```text
Laravel authorization review
    → replace with CakePHP Authentication/Authorization-aware review
```

Create:

```text
docs/inspiration-matrix.md
```

with columns:

| Original item | Classification | New item | Rationale |
|---|---|---|---|

This prevents accidental omission and prevents Laravel assumptions from surviving unnoticed.

---

# 48. Implementation Phases

## Phase 0 — Repository bootstrap

Deliver:

- Composer package skeleton;
- CLI entrypoint;
- PHPUnit;
- PHPStan;
- CI;
- README placeholder;
- license;
- base directory structure.

Acceptance:

- `composer install` succeeds;
- tests run;
- CI runs;
- CLI prints help.

---

## Phase 1 — Installer foundation

Implement:

- project root discovery;
- Composer metadata reader;
- editor adapters;
- rule/skill copy logic;
- `--force`;
- `--symlink`;
- `--prune`;
- `--dry-run`;
- installation state.

Acceptance:

- installs static PHP/CakePHP rules into fixture projects;
- preserves project-owned files;
- pruning touches only managed files.

---

## Phase 2 — Extension framework

Implement:

- manifest schema;
- manifest loader;
- registry;
- Composer package detector;
- version resolver;
- dependency graph;
- enable/disable;
- `detect`;
- `extensions`;
- diagnostics.

Acceptance:

- CRUD extension is detected by fixture Composer metadata;
- incompatible versions do not activate;
- cycles/conflicts fail clearly;
- explicit disable overrides detection.

---

## Phase 3 — CakePHP core rules

Implement the core rule catalog from this specification.

Prioritize:

1. conventions;
2. architecture;
3. controllers;
4. ORM;
5. Tables;
6. Entities;
7. associations;
8. finders;
9. validation;
10. application rules;
11. events;
12. middleware;
13. routing/request/response;
14. testing;
15. security;
16. performance.

Acceptance:

- rules pass content validation;
- examples use CakePHP 5 APIs;
- framework-sensitive claims are source-reviewed;
- no accidental Laravel architecture remains.

---

## Phase 4 — CakePHP core skills

Implement the initial skill catalog.

Start with:

1. `analyze-cakephp-project`
2. `create-finder`
3. `add-association`
4. `add-validation`
5. `add-application-rule`
6. `create-controller-action`
7. `create-api-endpoint`
8. `create-event-listener`
9. `diagnose-orm-query`
10. `cakephp-code-review`

Acceptance:

- every skill has discovery, workflow, anti-patterns, validation, and completion criteria;
- skill instructions do not assume optional plugins.

---

## Phase 5 — FriendsOfCake CRUD

Implement full CRUD reference extension.

Deliver:

- manifest;
- controller rules;
- action rules;
- event rules;
- listener rules;
- lifecycle-vs-ORM boundary rules;
- listener path convention;
- CRUD skills;
- tests;
- fixtures.

Acceptance scenario:

Given a fixture project containing CakePHP 5 + CRUD 7:

```bash
vendor/bin/cakephp-agent detect
```

must show CRUD.

Installation must add CRUD knowledge.

Given CakePHP without CRUD, CRUD rules must not install.

---

## Phase 6 — Authentication and Authorization

Build first official CakePHP plugin packs.

Acceptance:

- independently detected;
- independently enabled;
- no cross-assumption;
- when both exist, rules correctly explain identity vs. authorization responsibilities.

---

## Phase 7 — Search and CRUD/Search interoperability

Implement FriendsOfCake Search.

Then prototype conditional extension integration.

Acceptance:

- Search-alone project gets Search rules;
- CRUD-alone project gets CRUD rules;
- both packages present can activate integration rules without duplication.

---

## Phase 8 — Agents and deeper reviews

Implement specialized reviewers.

Acceptance:

- reviewers reference installed capabilities;
- ORM reviewer does not assume CRUD;
- CRUD-aware review behavior activates only in CRUD projects.

---

## Phase 9 — Documentation and public release

Complete:

- installation guide;
- editor guide;
- extension authoring;
- contribution guide;
- architecture docs;
- package compatibility;
- migration/inspiration notes;
- security policy;
- changelog.

---

# 49. Milestone Definition

Suggested milestones:

## M1 — Installer MVP

Static CakePHP rules can be installed.

## M2 — Extension Engine

Composer-based extension discovery works.

## M3 — CakePHP Expert Core

Core rules and first core skills are complete.

## M4 — CRUD Proof

FriendsOfCake CRUD validates extension architecture.

## M5 — Ecosystem Foundation

Authentication, Authorization, Search supported.

## M6 — 1.0

Stable manifests, installer, core content, extension authoring contract.

---

# 50. Definition of Done for v1.0

v1.0 is complete when all are true:

- Composer package installable.
- CLI is documented.
- Cursor supported.
- Claude Code supported.
- Codex supported to the degree its current rule/skill interfaces permit.
- CakePHP 5 core rules are comprehensive.
- At least 20 meaningful CakePHP skills exist.
- Extension manifest format is stable.
- Composer package auto-detection works.
- FriendsOfCake CRUD extension is production-quality.
- Authentication extension exists.
- Authorization extension exists.
- FriendsOfCake Search extension exists.
- Project overrides are safe.
- Installer state prevents destructive prune behavior.
- CI passes.
- Static analysis passes.
- Content lint passes.
- Integration tests cover representative project combinations.
- Framework-specific content has source provenance.
- Documentation enables third parties to create extensions.

---

# 51. Acceptance Scenarios

## Scenario A — Plain CakePHP

Project:

```json
{
  "require": {
    "cakephp/cakephp": "^5.4"
  }
}
```

Expected:

```text
engineering
php
cakephp
```

No CRUD/Auth/Search rules.

---

## Scenario B — CakePHP + CRUD

Expected:

```text
engineering
php
cakephp
friendsofcake-crud
```

CRUD listener skills available.

---

## Scenario C — CakePHP + CRUD + Search

Expected:

```text
engineering
php
cakephp
friendsofcake-crud
friendsofcake-search
crud-search integration (if implemented)
```

---

## Scenario D — Explicit disable

CRUD exists in Composer, config says:

```json
{
  "extensions": {
    "disable": ["friendsofcake-crud"]
  }
}
```

CRUD knowledge must not install.

---

## Scenario E — Project override

Package recommends:

```text
src/Listener/Api/PeopleListener.php
```

Project rule states:

```text
CRUD listeners are grouped by bounded context.
```

Agent should follow project rule.

---

## Scenario F — Incompatible plugin

Composer contains an unsupported CRUD major version.

Installer should:

- detect the package;
- report incompatible capability;
- not install incompatible rules;
- provide actionable diagnostics.

Never silently pretend compatibility.

---

# 52. LLM Implementation Instructions

When this document is given to an LLM to implement the project, the LLM should follow these instructions.

## 52.1 First action

Inspect the current repository.

If the repository is empty:

- bootstrap according to Phase 0.

If it already contains code:

- inventory it against this blueprint;
- preserve useful completed work;
- identify deviations;
- continue from the earliest incomplete phase.

## 52.2 Research before framework-sensitive implementation

Before writing CakePHP-specific rules or plugin extensions:

- verify behavior against current official docs/source for the supported version;
- do not rely solely on model memory;
- record important source provenance.

## 52.3 Implement incrementally

Do not generate the entire repository as a single unreviewed dump.

Implement by vertical slices:

```text
code
→ tests
→ static analysis
→ documentation
→ commit-ready state
```

## 52.4 Preserve architecture

Do not simplify away:

- extension manifests;
- extension dependency resolution;
- editor adapters;
- installation state;
- project override safety.

These are core product requirements, not optional polish.

## 52.5 Do not cargo-cult the inspiration repository

Use `pekral/cursor-rules` to learn useful distribution and workflow patterns.

Do not copy Laravel architecture.

Every framework-specific item must be reconsidered from CakePHP principles.

## 52.6 Test every phase

Do not postpone all tests until the end.

## 52.7 Prefer real fixture applications

Where practical, integration fixtures should resemble actual CakePHP Composer metadata and directory layout.

## 52.8 No invented plugin APIs

If uncertain about a plugin method, event, class, or version:

- inspect upstream documentation/source;
- do not guess.

---

# 53. Recommended First LLM Prompt

The following prompt can accompany this file:

> You are implementing the project described in `PROJECT_PLAN.md`.
>
> Treat that file as the architectural source of truth.
>
> Start by inspecting the repository and determining which implementation phase is currently incomplete. Do not blindly recreate files that already exist.
>
> For CakePHP or plugin-specific behavior, verify current APIs against official CakePHP documentation or the upstream plugin documentation/source before encoding rules.
>
> Implement the next coherent vertical slice completely, including tests and documentation. Preserve the extension architecture, editor adapters, safe project override behavior, and installation-state design.
>
> Never mechanically translate Laravel concepts to CakePHP. Prefer idiomatic CakePHP mechanisms and explicitly distinguish framework semantics from project/package conventions.
>
> Before concluding, run the relevant tests, static analysis, content validation, and Composer validation and resolve failures caused by your changes.

---

# 54. Recommended Bootstrap Task for the First LLM Session

Ask the LLM to complete only:

```text
Phase 0 + Phase 1 foundation
```

Specifically:

1. scaffold Composer package;
2. create CLI;
3. create editor adapter interface;
4. implement project root discovery;
5. implement managed file installer;
6. implement safe installation-state file;
7. support `--editor`, `--force`, `--dry-run`;
8. add PHPUnit tests;
9. add PHPStan;
10. add CI;
11. document architecture.

Do not start writing hundreds of rules until the installer architecture is stable.

---

# 55. Second LLM Session

Implement:

```text
Phase 2 — Extension framework
```

Use a fake/test extension first.

Only after extension resolution passes tests should the real CRUD extension be written.

---

# 56. Third LLM Session

Implement CakePHP core content foundations:

```text
conventions
architecture
controllers
ORM
Tables
Entities
associations
validation
application rules
```

Perform source verification before authoring.

---

# 57. Fourth LLM Session

Implement:

```text
FriendsOfCake CRUD reference extension
```

This is the architectural proof that the extension platform works.

---

# 58. Future Opportunities

Not required for v1, but the architecture should leave room for:

## 58.1 Knowledge freshness tooling

Command:

```bash
vendor/bin/cakephp-agent audit-sources
```

could identify rules whose upstream references have not been reviewed recently.

## 58.2 Remote extension registry

Potential future registry of community extension packs.

Do not require this for v1.

## 58.3 Project architecture generator

A command could inspect an application and generate a draft project architecture file for human review.

Never silently enable subjective rules based solely on inference.

## 58.4 Rule effectiveness tests

Use curated prompts and expected architectural choices to regression-test LLM behavior.

Example prompt:

```text
I need to make sure email is unique before saving a User.
```

Expected guidance:

```text
application rule / RulesChecker
```

rather than only request validation.

## 58.5 Anti-hallucination evaluation suite

Test prompts intentionally designed to tempt Laravel-style answers.

Examples:

- "Create a FormRequest for this CakePHP endpoint."
- "Add an Eloquent scope."
- "Register this ServiceProvider."
- "Run artisan to inspect routes."

Expected behavior:

- correct the framework mismatch;
- propose the CakePHP-native equivalent.

This could become one of the most valuable quality mechanisms in the project.

---

# 59. Key Architectural Decisions Record

## ADR-001 — CakePHP-first rather than Laravel-port architecture

**Decision:** Reuse useful engineering/distribution ideas but rewrite framework knowledge from CakePHP principles.

**Reason:** Mechanical translation retains foreign architectural assumptions.

---

## ADR-002 — Rules and skills are separate concepts

**Decision:** Rules encode persistent standards; skills encode task workflows.

**Reason:** Reduces rule bloat and gives agents procedural guidance only when needed.

---

## ADR-003 — Extensions are capability packs

**Decision:** Optional CakePHP/plugin knowledge lives outside core.

**Reason:** Avoids contaminating projects with unavailable plugin APIs.

---

## ADR-004 — Composer dependencies drive automatic plugin detection

**Decision:** Use Composer metadata as primary objective detection source.

**Reason:** Reliable and deterministic.

---

## ADR-005 — Subjective architecture is not aggressively auto-detected

**Decision:** Architecture packs generally require explicit enablement.

**Reason:** Weak heuristic inference can cause damaging agent assumptions.

---

## ADR-006 — Project rules have highest precedence

**Decision:** Project-owned architecture overrides packaged recommendations.

**Reason:** Real applications legitimately diverge from defaults.

---

## ADR-007 — FriendsOfCake CRUD is the reference extension

**Decision:** Build CRUD before simpler extensions.

**Reason:** Its lifecycle/listener architecture thoroughly exercises the extension system.

---

## ADR-008 — Mirrored CRUD listener paths are a recommendation, not framework truth

**Decision:** Default CRUD convention mirrors controller paths under `src/Listener`.

**Reason:** Excellent discoverability for humans and agents while remaining overridable.

---

## ADR-009 — Managed-file state is required for safe pruning

**Decision:** Track installed files and hashes.

**Reason:** Never delete user-authored files based only on folder comparison.

---

# 60. Final Product Standard

The project's quality bar is not:

> "The agent knows CakePHP syntax."

The quality bar is:

> "The agent reasons about a CakePHP application using the same architectural boundaries, framework conventions, plugin semantics, discovery habits, and lifecycle awareness expected from an experienced CakePHP engineer."

For optional extensions, the quality bar is:

> "The agent only applies extension-specific knowledge when that extension is actually available and understands where the extension's lifecycle stops and CakePHP's core lifecycle begins."

For project customization, the quality bar is:

> "The package provides strong defaults without preventing an application from establishing its own legitimate architectural conventions."

That is the project to build.
