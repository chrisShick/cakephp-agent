---
name: choose-cakephp-abstraction
description: Ownership router — decide which CakePHP abstraction should own a concern before implementing it.
---

# Choose CakePHP abstraction

## Objective

Select the correct CakePHP ownership boundary for a concern (Controller, Middleware, Component, Table, Entity, Finder, Behavior, Validator, RulesChecker, Event/Listener, Command, etc.) with explicit rationale and rejected alternatives.

## Use when

- Unsure where new behavior should live.
- A request sounds like “add a service / helper / util” without a clear CakePHP home.
- Reviewing whether an existing placement is wrong.

## Do not use when

- The abstraction is already decided and you only need the implementation workflow (use the matching task skill).
- The only question is syntax, not ownership.

## Inputs to discover

1. Run **`inspect-before-coding`** first (Composer, `.ai/`, neighboring classes, existing patterns).
2. Name the concern in one sentence (what must be true / what must happen).
3. Note lifecycle: request pipeline, controller action, entity marshaling, save/delete, query, CLI, async event.
4. Note whether the concern is HTTP-specific, query semantics, persistence invariant, or side effect.
5. Check whether an installed plugin already owns the concern.

## Workflow

1. Complete discovery via `inspect-before-coding`.
2. Answer the ownership questions:
   1. What concern is being implemented?
   2. Which lifecycle owns it?
   3. Must it apply across multiple entry points?
   4. Does it require persisted state?
   5. Is it HTTP-specific?
   6. Is it query semantics?
   7. Is it persistence behavior / invariant?
   8. Is it reusable across Tables/controllers/apps?
   9. Does CakePHP already expose a native extension point?
   10. Does an installed plugin own it?
   11. Does the project already establish an architecture for it?
3. Map answers to a recommended abstraction using linked decision units.
4. State rejected alternatives and why.
5. Name a testing strategy appropriate to the layer (controller/integration/table/unit).
6. Hand off to the matching task skill when implementation starts.

## Framework decisions

Link and apply as relevant:

- `knowledge/decisions/validation-vs-application-rule`
- `knowledge/decisions/finder-vs-behavior`
- `knowledge/decisions/finder-vs-service`
- `knowledge/decisions/behavior-vs-service`
- `knowledge/decisions/component-vs-middleware`
- `knowledge/decisions/middleware-vs-event-listener`
- `knowledge/decisions/entity-accessor-vs-query-calculation`
- `knowledge/decisions/table-callback-vs-application-rule`
- `knowledge/decisions/contain-vs-matching`
- `knowledge/decisions/contain-vs-join`
- `knowledge/decisions/bulk-update-vs-entity-save`
- `knowledge/decisions/transaction-vs-independent-save`
- `knowledge/decisions/event-vs-direct-call`
- `knowledge/decisions/plugin-vs-application-code`
- `knowledge/decisions/route-config-vs-controller-url-logic`
- `knowledge/decisions/command-vs-controller-action`
- `knowledge/decisions/paginate-controller-vs-custom-finder`
- `knowledge/decisions/exception-renderer-vs-controller-catch`
- `knowledge/decisions/config-vs-hardcoded-service`
- `knowledge/decisions/views-out-of-scope-for-core-skills`

Rough defaults (override with project conventions and decisions above):

| Concern | Prefer |
|---|---|
| URL → action mapping | Route config (`config/routes.php`) |
| Request/response pipeline | Middleware |
| Reusable controller collaboration | Component |
| HTTP orchestration only | Controller action |
| CLI / cron / batch entry | Command (`bin/cake`) |
| Large HTTP lists | Paginate a Table finder |
| HTML forms | FormHelper + FormProtection/CSRF |
| Presentation templates | Follow project templates (no deep core view catalog in v1) |
| Unexpected errors | Exception renderer / error middleware |
| Shared infra / secrets | Config + `Application::services()` |
| Query reuse on one Table | Custom finder |
| Cross-table persistence feature | Behavior |
| Multi-write consistency unit | Connection transaction |
| Field shape/format | Validator |
| Stateful save/delete invariant | Application rule (`buildRules`) |
| Persistence side effect | Table callback / event listener |
| Derived field on loaded entity | Entity accessor (careful) |
| Aggregate query calculation | Query / finder |

## Anti-patterns

- Fat controllers holding persistence and query logic.
- Active Record Entities that query the database by default.
- Premature service/repository layers that wrap a single Table with no orchestration value.
- Putting uniqueness only in validation.
- Assuming optional plugin APIs in core guidance.

## Validation

- Output includes: recommended abstraction, rationale, rejected alternatives, lifecycle boundary, testing strategy.
- Choice aligns with a decision unit or an explicit project convention from `.ai/`.
- No undetected plugin APIs recommended.

## Completion criteria

- Ownership decision documented and ready for implementation.
- Clear next skill (e.g. `add-application-rule`, `create-finder`, `create-controller-action`, `add-route`, `paginate-results`, `create-form`, `create-behavior`, `create-component`, `create-command`, `add-transaction`, `write-table-test`, `configure-error-handling`, `configure-application-services`, `review-query-performance`, `review-abstraction-choice`).
