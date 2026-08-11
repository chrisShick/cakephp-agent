# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Phase 11 Wave A CakePHP coverage: routing, behaviors, components, commands, transactions; deeper security/testing
- Phase 12 Wave B/C: pagination, forms, views scope, errors, config/DI, performance; Migrations + Bake packs
- Phase 13 Track A: sessions, cache, logging, I18n, HTTP client, request/response, mailer, advanced ORM, `work-with-views`; `cakephp-queue` + `cakephp-debug-kit` packs
- Phase 13 Track B: `review-cakephp-security`; deepened security rule; PHP safety rules/skills; engineering testing + dependency honesty
- Pos+neg evaluations across phases; [HANDOFF-phase-14.md](docs/HANDOFF-phase-14.md) for Packagist / 1.0

### Changed

- Coverage map and `choose-cakephp-abstraction` defaults through Phase 13
- Relicensed from MIT to **Apache-2.0** (`LICENSE`, `NOTICE`, `composer.json`, README)

## [0.9.0] - 2026-08-10

Adopter preview (not 1.0). Safe installer + extension packs + offline eval platform for CakePHP 5, with packaging/docs gap fixes for dogfooding.

Composer package name: **`chrisshick/cakephp-agent`** (Composer requires a lowercase vendor; GitHub remains `chrisShick/cakephp-agent`).

### Added

- Phases 0–10 vertical slice: repository bootstrap, installer foundation, extension engine, canonical knowledge + core rules, P0 skills, FriendsOfCake CRUD pack, AuthN/AuthZ packs, Search + integration packs, capability-aware agents, decision/smell intelligence, offline `cakephp-agent eval` with baselines
- Editor adapters for Cursor, Claude Code, and Codex (rules/skills; agents for Cursor/Claude only)
- `uninstall` command — removes lock-tracked managed editor files; never touches `.ai/`
- Hardened `doctor` — project/package roots, CakePHP detection, lock vs disk, Codex agents note, content validate summary, suggested next step
- Public adopter docs: install guide, editors, extension authoring, AI overlays, troubleshooting; example `.ai/` overlay
- Extension manifest JSON Schema (`schemas/extension-manifest.schema.json`)

### Changed

- Package version aligned to **0.9.0**
- Test fake extensions quarantined under `tests/fixtures/extensions/` (not loaded for consumer installs)
- SECURITY reporting points at GitHub private advisories
- CONTRIBUTING expanded with setup, check commands, truth levels, and PR expectations

### Notes

- Not on Packagist yet — install via VCS (`https://github.com/chrisShick/cakephp-agent.git`) or path repository as `chrisshick/cakephp-agent`
- Offline `eval` is a corpus/self-check harness, not live model grading
- See [docs/pre-1.0-review.md](docs/pre-1.0-review.md) for the path to 1.0

[0.9.0]: https://github.com/chrisShick/cakephp-agent/releases/tag/v0.9.0
