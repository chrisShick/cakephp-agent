# Rules & skills coverage map (CakePHP 5)

Audit date: 2026-08-10 · Package **0.9.0**  
**Phase ownership:** Phase 11 — see [HANDOFF-phase-11.md](HANDOFF-phase-11.md)

Goal: honest answer to “is CakePHP entirely covered?” — **No.** Core expert boundaries are strong; several Book chapters and common workflows are thin or missing. “Entirely covered” should mean *agents don’t invent wrong ownership for everyday CakePHP work*, not a mirror of the whole Book.

## Inventory

| Layer | Count | Notes |
|---|---|---|
| Core rules (`rules/cakephp/`) | **16** | ~28–45 lines each; signal-dense, not exhaustive |
| Core skills (`skills/cakephp/`) | **15** | All P0 flagship skills present + smell/review pair |
| Extension rules | **23** | CRUD 9, AuthN 4, AuthZ 4, Search 4, integrations 2 |
| Extension skills | **25** | CRUD 9, AuthN 4, AuthZ 5, Search 4, integrations 3 |
| Engineering/PHP rules | **2** | Thin base layers |
| Engineering/PHP skills | **0** | Directories empty (by design so far) |

### Core rules present

`philosophy`, `conventions`, `architecture`, `controllers`, `middleware`, `orm`, `tables`, `entities`, `associations`, `finders`, `validation`, `application-rules`, `events`, `plugins`, `testing`, `security`

### Core skills present (P0 complete)

`inspect-before-coding`, `choose-cakephp-abstraction`, `analyze-cakephp-project`, `create-finder`, `add-association`, `add-validation`, `add-application-rule`, `create-controller-action`, `create-api-endpoint`, `create-event-listener`, `diagnose-orm-query`, `cakephp-code-review`, `select-lifecycle-hook`, `detect-architectural-smells`, `review-abstraction-choice`

---

## Coverage vs unified-plan rule priorities

| Plan priority | Status | Gap |
|---|---|---|
| 1. conventions / architecture / philosophy | **Strong** | — |
| 2. controllers, middleware, routing, requests/responses | **Partial** | Controllers + middleware exist; **no dedicated routing, request, response, forms, or flash rules** |
| 3. ORM / tables / entities / associations / finders | **Strong** | Advanced query topics thin (subqueries, EXISTS, counter-cache, pagination ownership) |
| 4. validation / application-rules / transactions | **Partial** | Validation + rules strong; **no transactions rule/skill** (decision exists) |
| 5. events, behaviors, components, commands, plugins | **Partial** | Events + plugins exist; **no behaviors, components, or commands rules/skills** |
| 6. testing, fixtures, security, performance | **Thin** | Testing + security are short checklists; **no fixtures depth, no performance rule** |
| 7. DI / configuration / errors / cache / logging | **Missing** | No core rules/skills |

---

## CakePHP Book-shaped gap map

Legend: ✅ covered · 🟡 thin / implied · ❌ missing as dedicated rule or skill

### Always needed for “everyday CakePHP”

| Topic | Rules | Skills | Priority to add |
|---|---|---|---|
| Ownership / philosophy | ✅ | ✅ | — |
| Controllers | ✅ | ✅ | Deepen request/response/flash slightly |
| Tables / Entities / Associations / Finders | ✅ | ✅ | — |
| Validation vs rules | ✅ | ✅ | — |
| Middleware | ✅ | 🟡 (via choose-abstraction) | Optional `create-middleware` |
| Events / listeners | ✅ | ✅ | — |
| Plugins (awareness) | ✅ | 🟡 | Optional `review-plugin-usage` |
| **Routing** | ❌ | ❌ | **P0** |
| **Behaviors** | ❌ | ❌ | **P0** |
| **Components** | ❌ | ❌ | **P0** |
| **Commands / console** | ❌ | ❌ | **P0** |
| **Transactions** | ❌ (decision only) | ❌ | **P0** |
| **Pagination** | ❌ | ❌ | **P1** |
| **Forms / FormHelper / CSRF** | ❌ | ❌ | **P1** |
| **Views / cells / helpers** | ❌ | ❌ | **P1** (lower if API-first teams) |
| **Error handling** | ❌ | ❌ | **P1** |
| **Configuration / DI** | ❌ | ❌ | **P1** |
| **Sessions / cookies** | ❌ | ❌ | **P2** |
| **Mailer** | ❌ | ❌ | **P2** |
| **Cache / logging** | ❌ | ❌ | **P2** |
| **I18n** | ❌ | ❌ | **P2** |
| **Migrations** (plugin) | ❌ | ❌ | Extension pack (Tier 1 planned) |
| **Bake** | ❌ | ❌ | Post-1.0 / Bake intelligence |
| **Queues** | ❌ | ❌ | Extension later |
| **Performance** | ❌ | 🟡 (diagnose-orm) | **P1** rule + skill |
| **Testing / fixtures depth** | 🟡 | ❌ | **P1** `write-table-test` / `write-integration-test` |
| **HTTP client** | ❌ | ❌ | **P2** |

### Capability packs (when installed)

| Pack | Coverage |
|---|---|
| FriendsOfCake CRUD | **Strong** |
| Authentication | **Good** |
| Authorization | **Good** |
| Search | **Good** |
| AuthN↔AuthZ / CRUD↔Search integrations | **Thin but real** |
| Migrations / Bake / DebugKit / Queues | **Not started** |

---

## Depth issue (even where files exist)

Most core rules are **checklists (≈30 lines)**. That is intentional (signal density), but these are especially thin for “entire coverage” claims:

- `security.mdc` — no CSRF/FormProtection, file upload, or session hardening detail
- `testing.mdc` — no fixture strategy, IntegrationTestCase patterns, or schema refresh guidance
- `events.mdc` — little on `EventManager` registration / global vs local
- `middleware.mdc` — no body parsing / routing middleware order pitfalls

Skills are stronger procedurally than rules are encyclopedically — which matches the product design.

---

## Recommended fill order (before claiming “CakePHP covered”)

> **Assigned to Phase 11** ([HANDOFF-phase-11.md](HANDOFF-phase-11.md)). Wave A is required; Wave B if time; Wave C later.

### Wave A — close everyday holes (rules + matching skills) — Phase 11 required

1. **`routing`** rule + `add-route` / `review-routing` skill  
2. **`behaviors`** rule + `create-behavior` skill  
3. **`components`** rule + `create-component` skill  
4. **`commands`** rule + `create-command` skill  
5. **`transactions`** rule + `add-transaction` skill (link `transaction-vs-independent-save`)  
6. Expand **`security`** (FormProtection/CSRF, uploads, serialization)  
7. Expand **`testing`** + `write-table-test` skill  

### Wave B — web/app completeness — Phase 11 optional stretch

8. Pagination (controller/ORM)  
9. Forms + FormProtection  
10. Views/cells/helpers (or explicit “views out of scope for v1 agents”)  
11. Error handling / exception renderer  
12. Configuration + DI (`Application::services`)  
13. Performance rule (N+1 already elsewhere; add caching/query budget)

### Wave C — ecosystem (extensions, not core always-on) — post–Phase 11

14. `cakephp/migrations` pack  
15. Bake intelligence  
16. Mailer / Queue packs as demand appears  

---

## What “entirely covered” should mean for 1.0

**Pass bar (recommended):**

- Every common ownership question has a decision or skill path (`choose-cakephp-abstraction` + Wave A rules).
- Agents do not invent Laravel substitutes for missing CakePHP surfaces (routing, behaviors, components, commands, transactions).
- Auth/CRUD/Search remain capability-gated.
- Security/testing are actionable, not slogans.

**Not required for 1.0:**

- Full Book parity (I18n, Mailer, Cache internals, Bake, Queues, DB packs).
- Expansion-plan P1–P3 skill dumps (`review-phpstan`, Bake suite, upgrade intelligence, etc.).
