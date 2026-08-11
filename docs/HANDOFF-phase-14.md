# Agent Handoff — Start Phase 14

> **For a new agent:** read this file first, then [pre-1.0-review.md](pre-1.0-review.md). Do not re-bootstrap Phases 0–13.

**Repo:** https://github.com/chrisShick/cakephp-agent  
**Package:** `chrisshick/cakephp-agent` · CLI **0.9.0** · **Apache-2.0**  
**Status date:** 2026-08-11

---

## 1. Already done

Phases 0–13: installer, capability packs, full CakePHP coverage, security/PHP/engineering base, offline eval, adopter docs, Apache-2.0.

**Also done before Packagist cut:** CRUD harden (decisions, smells, security, AuthZ integration, eval depth), `muffin/trash` pack, core polish.

**GitHub harden (in repo):** importable rulesets under [`.github/rulesets/`](../.github/rulesets/), `CODEOWNERS`, CI includes eval. Human must still **import** the rulesets in GitHub Settings.

Coverage: [coverage-rules-skills.md](coverage-rules-skills.md).

---

## 2. Phase 14 — Packagist / public release path

### Goal

Publish on Packagist and cut a **beta**, then stable **1.0.0** after soak.

### Version plan

1. **`1.0.0-beta.1`** (tag `v1.0.0-beta.1`) — first Packagist-visible / public beta  
2. Optional further betas (`1.0.0-beta.2`, …) if needed  
3. **`1.0.0`** (tag `v1.0.0`) — stable after soak

### Deliver

1. Import GitHub rulesets ([`.github/rulesets/README.md`](../.github/rulesets/README.md)) if not already active
2. Packagist publish `chrisshick/cakephp-agent` (human account; agent prepares checklist/docs)
3. Triage [pre-1.0-review.md](pre-1.0-review.md)
4. Version bump to **`1.0.0-beta.1`**; CHANGELOG / README status
5. Git tags (`v0.9.0` if missing, then `v1.0.0-beta.1`; later `v1.0.0`)
6. Optional soak in a real CakePHP app → then stable 1.0.0

### Accept

- [ ] Rulesets imported / active on `main` and `v*`
- [ ] Packagist path clear (or blocked-on-human documented)
- [ ] pre-1.0 items triaged
- [ ] `1.0.0-beta.1` version/tag cut (or ready)
- [ ] validate / phpunit / phpstan / eval green

### Kickoff

```text
Read docs/HANDOFF-phase-14.md and docs/pre-1.0-review.md.
Phase 13 coverage + security/PHP base is done. Harden GitHub is prepared in-repo.
Prepare Packagist and cut 1.0.0-beta.1 (stable 1.0.0 after soak).
```
