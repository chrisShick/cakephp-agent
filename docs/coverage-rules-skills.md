# Rules & skills coverage map (CakePHP 5)

Audit date: 2026-08-10 · Package **0.9.0**  
**Phase ownership:** Phase 12 Wave B + Wave C **done** — see [HANDOFF-phase-12.md](HANDOFF-phase-12.md). Next: [Phase 13 Packagist/1.0](HANDOFF-phase-13.md).

Goal: honest answer to “is CakePHP entirely covered?” — **Much closer for everyday ownership.** Wave A–C close core holes and add Migrations/Bake packs; Mailer/Queues remain honesty-only (absent → do not invent). Full Book parity is still not the bar.

## Inventory

| Layer | Count | Notes |
|---|---|---|
| Core rules (`rules/cakephp/`) | **27** | Wave B added pagination/forms/views/errors/configuration/performance |
| Core skills (`skills/cakephp/`) | **27** | Wave B added paginate/form/error/DI/performance skills |
| Extension rules | **26** | + Migrations 2, Bake 1 (CRUD/Auth/Search unchanged) |
| Extension skills | **28** | + Migrations 2, Bake 1 |
| Engineering/PHP rules | **2** | Thin base layers |
| Engineering/PHP skills | **0** | Directories empty (by design so far) |

### Core rules present

`philosophy`, `conventions`, `architecture`, `controllers`, `middleware`, `routing`, `orm`, `tables`, `entities`, `associations`, `finders`, `behaviors`, `components`, `commands`, `transactions`, `validation`, `application-rules`, `events`, `plugins`, `testing`, `security`, `pagination`, `forms`, `views`, `errors`, `configuration`, `performance`

### Core skills present

`inspect-before-coding`, `choose-cakephp-abstraction`, `analyze-cakephp-project`, `create-finder`, `add-association`, `add-validation`, `add-application-rule`, `create-controller-action`, `create-api-endpoint`, `create-event-listener`, `diagnose-orm-query`, `cakephp-code-review`, `select-lifecycle-hook`, `detect-architectural-smells`, `review-abstraction-choice`, `add-route`, `review-routing`, `create-behavior`, `create-component`, `create-command`, `add-transaction`, `write-table-test`, `paginate-results`, `create-form`, `configure-error-handling`, `configure-application-services`, `review-query-performance`

---

## Coverage vs unified-plan rule priorities

| Plan priority | Status | Gap |
|---|---|---|
| 1. conventions / architecture / philosophy | **Strong** | — |
| 2. controllers, middleware, routing, requests/responses | **Strong** | Flash/request minutiae still thin |
| 3. ORM / tables / entities / associations / finders | **Strong** | Advanced query topics thin (subqueries, EXISTS, counter-cache) |
| 4. validation / application-rules / transactions | **Strong** | — |
| 5. events, behaviors, components, commands, plugins | **Strong** | — |
| 6. testing, fixtures, security, performance | **Strong** | Performance rule + review skill added |
| 7. DI / configuration / errors / cache / logging | **Good** | Config/DI + errors filled; cache/logging still thin |

---

## CakePHP Book-shaped gap map

Legend: ✅ covered · 🟡 thin / implied · ❌ missing as dedicated rule or skill

### Always needed for “everyday CakePHP”

| Topic | Rules | Skills | Notes |
|---|---|---|---|
| Ownership / philosophy | ✅ | ✅ | — |
| Controllers / Middleware / Routing | ✅ | ✅ | — |
| Tables / Entities / Associations / Finders | ✅ | ✅ | — |
| Validation / Rules / Transactions | ✅ | ✅ | — |
| Behaviors / Components / Commands / Events | ✅ | ✅ | — |
| **Pagination** | ✅ | ✅ | Wave B |
| **Forms / FormProtection** | ✅ | ✅ | Wave B (+ security CSRF bar) |
| **Views / cells / helpers** | ✅ scope note | 🟡 | Explicit v1 “follow project templates” — no deep catalog |
| **Error handling** | ✅ | ✅ | Wave B |
| **Configuration / DI** | ✅ | ✅ | Wave B |
| **Performance** | ✅ | ✅ | Wave B |
| **Testing** | ✅ | ✅ | — |
| **Security** | ✅ | 🟡 | Via security rule + form skill |
| Sessions / cookies | ❌ | ❌ | P2 later |
| Cache / logging depth | 🟡 | ❌ | Mentioned in performance; no dedicated pack |
| I18n / HTTP client | ❌ | ❌ | P2 later |

### Capability packs (when installed)

| Pack | Coverage |
|---|---|
| FriendsOfCake CRUD | **Strong** |
| Authentication / Authorization / Search | **Good** |
| Integrations AuthN↔AuthZ / CRUD↔Search | **Thin but real** |
| **cakephp/migrations** | **Good** (Wave C) |
| **cakephp/bake** | **Light** (Wave C — generate then review) |
| Mailer / Queues | **Honesty evals only** (absent → do not invent Laravel mail/jobs) |
| DebugKit | **Not started** |

---

## Recommended fill order

### Wave A — **DONE (Phase 11)**

Routing, behaviors, components, commands, transactions, deeper security/testing.

### Wave B — **DONE (Phase 12)**

Pagination; forms + FormProtection; views scope note; errors; configuration/DI; performance.

### Wave C — **DONE (Phase 12 slice)**

- Migrations pack + Bake light pack  
- Mailer/Queue: absent honesty evals (full packs deferred until demand)

### After Phase 12

Packagist / **1.0** — [HANDOFF-phase-13.md](HANDOFF-phase-13.md). Optional later: mailer/queue packs, DebugKit, deeper views, cache/logging rules.

---

## What “entirely covered” should mean for 1.0

**Pass bar (recommended):**

- Every common ownership question has a decision or skill path — **Wave A+B met.**
- Agents do not invent Laravel substitutes for CakePHP surfaces — **met for Wave A–C topics.**
- Auth/CRUD/Search/Migrations/Bake remain capability-gated.
- Security/testing/performance are actionable.

**Not required for 1.0:**

- Full Book parity (I18n, deep Mailer/Queue, Cache internals).
- Expansion-plan P1–P3 skill dumps.
