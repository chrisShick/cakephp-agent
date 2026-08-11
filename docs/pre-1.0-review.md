# Pre-1.0 review & improvement backlog

Review date: 2026-08-11 (Phase 14 Packagist + beta).  
Goal: track what remains before calling a public **stable 1.0**.

## Verdict

**Packagist is live:** [chrisshick/cakephp-agent](https://packagist.org/packages/chrisshick/cakephp-agent).  
**`1.0.0-beta.1`** is the public beta cut. Stable **1.0.0** waits on soak in a real CakePHP app.

Phases 11–13 CakePHP coverage + security/PHP base are done. GitHub rulesets are imported.

## Closed before / in beta

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
- [x] Empty `src/Knowledge/` removed; knowledge placeholder READMEs
- [x] Codex agents gap documented
- [x] Eval ≠ live model quality called out in README/help
- [x] **Publish to Packagist** — https://packagist.org/packages/chrisshick/cakephp-agent
- [x] Document Packagist install (`composer require --dev chrisshick/cakephp-agent:^1.0@beta`)
- [x] Git tag **`v0.9.0`** (on Packagist)
- [x] GitHub rulesets imported (main + `v*`)

## Remaining for stable 1.0

1. **Git tag `v1.0.0-beta.1`** after this bump merges (Admin; Packagist auto-updates)
2. **Soak** — install into a real CakePHP app and fix any friction
3. **Cut `1.0.0`** (tag `v1.0.0`) after soak; update README status to stable
4. Optional: stronger SECURITY contact email; Windows symlink deep notes

## Explicitly later

- Live LLM eval adapters
- MCP / remote registry
- 200+ evaluation scenarios
- Filling empty `knowledge/concepts|patterns|…` catalogs beyond placeholders
