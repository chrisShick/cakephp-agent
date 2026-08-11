# Pre-1.0 review & improvement backlog

Review date: 2026-08-11 (Phase 14b ORM-first polish).  
Goal: track what remains before calling a public **stable 1.0**.

## Verdict

**Packagist is live:** [chrisshick/cakephp-agent](https://packagist.org/packages/chrisshick/cakephp-agent).  
**`1.0.0-beta.1`** is the public beta. Phase **14b** adds ORM-first + thin security/knowledge polish for an amazing stable 1.0. Remaining gate: **soak** → **`1.0.0`**.

## Closed before / in beta + 14b

- [x] Existing-app install guide (+ README entrypoint)
- [x] Real GitHub remote: https://github.com/chrisShick/cakephp-agent
- [x] CHANGELOG + version **0.9.0** (then **1.0.0-beta.1**)
- [x] Evaluation schema `auth`/`search` + ContentValidator enum/related_knowledge checks
- [x] Test fakes moved to `tests/fixtures/extensions/` (not loaded for consumers)
- [x] `uninstall` command
- [x] Hardened `doctor`
- [x] Public docs: editors, extension authoring, `.ai` overlays, troubleshooting
- [x] SECURITY advisory URL + CONTRIBUTING expansion
- [x] `.gitattributes` export-ignore hygiene
- [x] Empty `src/Knowledge/` removed; knowledge placeholder READMEs → indexes
- [x] Codex agents gap documented
- [x] Eval ≠ live model quality called out in README/help
- [x] **Publish to Packagist** — https://packagist.org/packages/chrisshick/cakephp-agent
- [x] Document Packagist install (`composer require --dev chrisshick/cakephp-agent:^1.0@beta`)
- [x] Git tag **`v0.9.0`** (on Packagist)
- [x] GitHub rulesets imported (main + `v*`)
- [x] **ORM-first mandate** — `orm-vs-connection-sql` + philosophy/orm rules + evals
- [x] **Thin SQL security** — `unsafe-sql-concatenation` + rejection eval
- [x] **Source freshness** on critical ORM/security rules (`last_verified` 2026-08-11)
- [x] Selective knowledge catalog indexes (concepts/patterns/security/performance)

## Remaining for stable 1.0

1. **Soak** — install into a real CakePHP app and fix any friction
2. **Cut `1.0.0`** (tag `v1.0.0`) after soak; update README status to stable
3. Optional: stronger SECURITY contact email; Windows symlink deep notes

## Explicitly later (post-1.0)

- Live LLM eval adapters
- MCP / remote registry
- 200+ evaluation scenarios
- Full knowledge encyclopedia beyond indexed decisions/anti-patterns
- DB packs, plugin-author mode, knowledge compiler
- Full ORM / AppSec / Bake / upgrade “expert programs”
