# Pre-1.0 review & improvement backlog

Review date: 2026-08-10 (updated after 0.9.0 gap closure).  
Goal: track what remains before calling a public **1.0**.

## Verdict

**0.9.0 adopter preview** is ready to dogfood: documented VCS install, uninstall, hardened doctor, quarantined test fakes, schema drift fixed, public docs.

**Not 1.0 yet:** Packagist publish + tagged release soak still pending (Phase 14). Phases 11–13 CakePHP coverage + security/PHP base are done.

## Closed in 0.9.0

- [x] Existing-app install guide (+ README entrypoint)
- [x] Real GitHub remote: https://github.com/chrisShick/cakephp-agent
- [x] CHANGELOG + version **0.9.0**
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

## Remaining for 1.0

1. **Publish to Packagist** (or confirm VCS-only policy) and document `composer require chrisshick/cakephp-agent:^0.9` (then `1.0.0-beta.1` / `^1.0` when ready)
2. **Git tags** after push: `v0.9.0` if missing → **`v1.0.0-beta.1`** → later **`v1.0.0`**
3. **Soak** — install into a real CakePHP app and fix any friction
4. Optional: stronger SECURITY contact email; Windows symlink deep notes

## Explicitly later

- Live LLM eval adapters
- MCP / remote registry
- 200+ evaluation scenarios
- Filling empty `knowledge/concepts|patterns|…` catalogs beyond placeholders
