# Agent Handoff — Phase 14 (Packagist / 1.0)

> **Status:** Packagist live; **`1.0.0-beta.2`** (ORM-first polish). Remaining: soak, then stable **1.0.0**.

**Repo:** https://github.com/chrisShick/cakephp-agent  
**Package:** [`chrisshick/cakephp-agent`](https://packagist.org/packages/chrisshick/cakephp-agent) · CLI **1.0.0-beta.2** · **Apache-2.0**  
**Status date:** 2026-08-11

---

## 1. Already done

Phases 0–13: installer, capability packs, full CakePHP coverage, security/PHP/engineering base, offline eval, adopter docs, Apache-2.0.

**Also done before Packagist cut:** CRUD harden, `muffin/trash` pack, core polish, GitHub rulesets (imported), Packagist package created.

Coverage: [coverage-rules-skills.md](coverage-rules-skills.md).

---

## 2. Phase 14 — Packagist / public release path

### Goal

Publish on Packagist and cut a **beta**, then stable **1.0.0** after soak.

### Version plan

1. **`1.0.0-beta.1`** (tag `v1.0.0-beta.1`) — first Packagist-visible / public beta ← **version bumped in tree**
2. Optional further betas (`1.0.0-beta.2`, …) if soak finds blockers
3. **`1.0.0`** (tag `v1.0.0`) — stable after soak

### Deliver

1. [x] Import GitHub rulesets ([`.github/rulesets/README.md`](../.github/rulesets/README.md))
2. [x] Packagist publish `chrisshick/cakephp-agent`
3. [x] Triage [pre-1.0-review.md](pre-1.0-review.md)
4. [x] Version bump to **`1.0.0-beta.1`**; CHANGELOG / README status
5. [x] Git tag **`v1.0.0-beta.1`** (human Admin after merge)
6. [x] Phase **14b** ORM-first / 1.0 polish — [HANDOFF-phase-14b.md](HANDOFF-phase-14b.md)
7. [ ] Soak in a real CakePHP app → then stable **1.0.0**

### Accept

- [x] Rulesets imported / active on `main` and `v*`
- [x] Packagist path clear — https://packagist.org/packages/chrisshick/cakephp-agent
- [x] pre-1.0 items triaged
- [ ] `1.0.0-beta.1` version/tag cut (version in tree; **tag pending**)
- [ ] validate / phpunit / phpstan / eval green (re-check on release PR)

### After beta tag

```bash
composer require --dev chrisshick/cakephp-agent:^1.0@beta
# or pin: chrisshick/cakephp-agent:1.0.0-beta.1
```

Stable later: `composer require --dev chrisshick/cakephp-agent:^1.0`

### Kickoff (remaining)

```text
Tag v1.0.0-beta.1 after merge. Soak in a real CakePHP app.
Fix friction, then cut v1.0.0 and flip README to stable 1.0.
```
