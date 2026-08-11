# Rules & skills coverage map (CakePHP 5)

Audit date: 2026-08-10 · Package **0.9.0**  
**Phase ownership:** Phase 11 Wave A **done** — see [HANDOFF-phase-11.md](HANDOFF-phase-11.md); leftovers in [HANDOFF-phase-12.md](HANDOFF-phase-12.md)

Goal: honest answer to “is CakePHP entirely covered?” — **No (Wave B+ still open).** Core everyday ownership boundaries for Wave A are filled so agents don’t invent wrong abstractions for routing, behaviors, components, commands, transactions, or thin security/testing.

## Inventory

| Layer | Count | Notes |
|---|---|---|
| Core rules (`rules/cakephp/`) | **21** | Wave A added routing/behaviors/components/commands/transactions; security + testing deepened |
| Core skills (`skills/cakephp/`) | **22** | Wave A added route/behavior/component/command/transaction/table-test skills |
| Extension rules | **23** | CRUD 9, AuthN 4, AuthZ 4, Search 4, integrations 2 |
| Extension skills | **25** | CRUD 9, AuthN 4, AuthZ 5, Search 4, integrations 3 |
| Engineering/PHP rules | **2** | Thin base layers |
| Engineering/PHP skills | **0** | Directories empty (by design so far) |

### Core rules present

`philosophy`, `conventions`, `architecture`, `controllers`, `middleware`, `routing`, `orm`, `tables`, `entities`, `associations`, `finders`, `behaviors`, `components`, `commands`, `transactions`, `validation`, `application-rules`, `events`, `plugins`, `testing`, `security`

### Core skills present

`inspect-before-coding`, `choose-cakephp-abstraction`, `analyze-cakephp-project`, `create-finder`, `add-association`, `add-validation`, `add-application-rule`, `create-controller-action`, `create-api-endpoint`, `create-event-listener`, `diagnose-orm-query`, `cakephp-code-review`, `select-lifecycle-hook`, `detect-architectural-smells`, `review-abstraction-choice`, `add-route`, `review-routing`, `create-behavior`, `create-component`, `create-command`, `add-transaction`, `write-table-test`

---

## Coverage vs unified-plan rule priorities

| Plan priority | Status | Gap |
|---|---|---|
| 1. conventions / architecture / philosophy | **Strong** | — |
| 2. controllers, middleware, routing, requests/responses | **Good** | Routing filled; request/response/flash/forms still thin |
| 3. ORM / tables / entities / associations / finders | **Strong** | Advanced query topics thin (subqueries, EXISTS, counter-cache, pagination ownership) |
| 4. validation / application-rules / transactions | **Strong** | Transactions rule + skill added |
| 5. events, behaviors, components, commands, plugins | **Strong** | Behaviors/components/commands filled |
| 6. testing, fixtures, security, performance | **Improved** | Security/testing deepened + `write-table-test`; still no performance rule |
| 7. DI / configuration / errors / cache / logging | **Missing** | Wave B |

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
| **Routing** | ✅ | ✅ | Wave A done |
| **Behaviors** | ✅ | ✅ | Wave A done |
| **Components** | ✅ | ✅ | Wave A done |
| **Commands / console** | ✅ | ✅ | Wave A done |
| **Transactions** | ✅ | ✅ | Wave A done |
| **Pagination** | ❌ | ❌ | **P1** (Wave B) |
| **Forms / FormHelper / CSRF** | 🟡 (in security) | ❌ | **P1** dedicated forms skill still open |
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
| **Testing / fixtures depth** | ✅ improved | ✅ `write-table-test` | Optional integration-test skill |
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

Most core rules are **checklists (≈30 lines)**. That is intentional (signal density).

Skills remain stronger procedurally than rules are encyclopedically — which matches the product design.

---

## Recommended fill order

### Wave A — close everyday holes — **DONE (Phase 11)**

1. ~~**`routing`** rule + `add-route` / `review-routing` skill~~  
2. ~~**`behaviors`** rule + `create-behavior` skill~~  
3. ~~**`components`** rule + `create-component` skill~~  
4. ~~**`commands`** rule + `create-command` skill~~  
5. ~~**`transactions`** rule + `add-transaction` skill~~  
6. ~~Expand **`security`** (FormProtection/CSRF, uploads, serialization)~~  
7. ~~Expand **`testing`** + `write-table-test` skill~~  

### Wave B — web/app completeness — Phase 12 optional

8. Pagination (controller/ORM)  
9. Forms + FormProtection (dedicated skill; security rule already covers CSRF bar)  
10. Views/cells/helpers (or explicit “views out of scope for v1 agents”)  
11. Error handling / exception renderer  
12. Configuration + DI (`Application::services`)  
13. Performance rule (N+1 already elsewhere; add caching/query budget)

### Wave C — ecosystem (extensions, not core always-on) — post–Wave B

14. `cakephp/migrations` pack  
15. Bake intelligence  
16. Mailer / Queue packs as demand appears  

---

## What “entirely covered” should mean for 1.0

**Pass bar (recommended):**

- Every common ownership question has a decision or skill path (`choose-cakephp-abstraction` + Wave A rules). **Wave A met.**
- Agents do not invent Laravel substitutes for missing CakePHP surfaces (routing, behaviors, components, commands, transactions). **Wave A met.**
- Auth/CRUD/Search remain capability-gated.
- Security/testing are actionable, not slogans. **Improved in Wave A.**

**Not required for 1.0:**

- Full Book parity (I18n, Mailer, Cache internals, Bake, Queues, DB packs).
- Expansion-plan P1–P3 skill dumps (`review-phpstan`, Bake suite, upgrade intelligence, etc.).
