# Rules & skills coverage map (CakePHP 5)

Audit date: 2026-08-11 · Package **1.0.0-beta.1** · **Apache-2.0**  
**Phase ownership:** Phase 14 Packagist/beta done path — [HANDOFF-phase-14.md](HANDOFF-phase-14.md). Phase **14b** ORM-first + 1.0 polish — [HANDOFF-phase-14b.md](HANDOFF-phase-14b.md) (soak → stable 1.0).

Goal: everyday CakePHP work has a rule/skill path (or capability-gated pack) so agents don’t invent foreign frameworks. **Book-complete enough for 1.0 trust** after Phase 13; not an encyclopedia of every plugin.

## Inventory

| Layer | Count | Notes |
|---|---|---|
| Core rules (`rules/cakephp/`) | **35** | Phase 13 added sessions/cache/logging/i18n/http-client/request-response/mailer/advanced-orm |
| Core skills (`skills/cakephp/`) | **36** | + session/cache/logging/i18n/http/mailer/views/advanced-finder/security-review |
| PHP rules (`rules/php/`) | **2** | Typing + php-safety |
| PHP skills (`skills/php/`) | **2** | `apply-strict-types`, `review-php-safety` |
| Engineering rules | **3** | clean-code, testing-discipline, dependency-honesty |
| Engineering skills | **1** | `review-change-safety` |
| Extension rules | **28** | + queue, DebugKit |
| Extension skills | **30** | + queue job, use-debug-kit |

### Core CakePHP rules

`philosophy`, `conventions`, `architecture`, `controllers`, `middleware`, `routing`, `request-response`, `orm`, `tables`, `entities`, `associations`, `finders`, `advanced-orm`, `behaviors`, `components`, `commands`, `transactions`, `validation`, `application-rules`, `events`, `plugins`, `testing`, `security`, `pagination`, `forms`, `views`, `errors`, `configuration`, `performance`, `sessions`, `cache`, `logging`, `i18n`, `http-client`, `mailer`

---

## CakePHP Book-shaped map

| Topic | Rules | Skills | Status |
|---|---|---|---|
| Ownership / ORM / validation / rules / transactions | ✅ | ✅ | **Strong** (+ ORM-first 14b) |
| Controllers / middleware / routing / request-response | ✅ | ✅ | Strong |
| Behaviors / components / commands / events | ✅ | ✅ | Strong |
| Pagination / forms / errors / config/DI / performance | ✅ | ✅ | Strong |
| Sessions / cookies / flash | ✅ | ✅ | Phase 13 |
| Cache / logging | ✅ | ✅ | Phase 13 |
| I18n | ✅ | ✅ | Phase 13 |
| HTTP client | ✅ | ✅ | Phase 13 |
| Mailer | ✅ | ✅ | Phase 13 |
| Views / cells / helpers | ✅ | ✅ `work-with-views` | Good (project-following) |
| Advanced ORM (subquery/EXISTS/counter-cache/expressions) | ✅ | ✅ | Phase 13 + 14b |
| Security | ✅ deepened | ✅ `review-cakephp-security` | Phase 13 + SQL concat 14b |
| PHP / engineering base | ✅ | ✅ | Phase 13 Track B |

### Capability packs

| Pack | Coverage |
|---|---|
| CRUD / AuthN / AuthZ / Search + integrations | **Strong** — CRUD hardened (evals, security, AuthZ integration, smells) |
| CRUD + Authorization integration | **Good** (IDOR/scoping on CRUD actions) |
| CRUD + Search integration | **Good** (non-duplication deepened) |
| Migrations / Bake | Good / Light (Bake → ownership/CRUD review notes) |
| **muffin/trash** (UseMuffin/Trash) | **Good** — soft-delete behavior pack |
| **cakephp/queue** | Good |
| **cakephp/debug_kit** | Light dev-only |

---

## Waves

- **A–C (Phases 11–12):** everyday ownership + migrations/bake  
- **Phase 13 Track A:** remaining core Book surfaces + queue/DebugKit packs  
- **Phase 13 Track B:** security review skill, deeper security rule, PHP safety, engineering P2  
- **Phase 14 prep:** CRUD harden (evals, decisions, security, smells, AuthZ integration) + `muffin/trash` pack + core polish  
- **Phase 14:** Packagist published; **`1.0.0-beta.1`** cut  
- **Phase 14b:** ORM-first mandate + thin security/knowledge polish — soak → stable `1.0.0`

---

## What “entirely covered” means for 1.0

**Met after Phase 13:**

- Common ownership questions have decision/skill paths.
- Agents should not invent Laravel substitutes for CakePHP surfaces listed above.
- Auth/CRUD/Search/Migrations/Bake/Queue/DebugKit remain capability-gated.
- Security + PHP baseline are actionable skills, not slogans.

**Still optional later:** full AppSec/ORM expert encyclopedias, live LLM evals, MCP, niche plugins, DB packs.
