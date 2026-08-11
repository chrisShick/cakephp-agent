# Agent Handoff — Start Phase 14

> **For a new agent:** read this file first, then [pre-1.0-review.md](pre-1.0-review.md). Do not re-bootstrap Phases 0–13.

**Repo:** https://github.com/chrisShick/cakephp-agent  
**Package:** `chrisshick/cakephp-agent` · CLI **0.9.0** · **Apache-2.0**  
**Status date:** 2026-08-10

---

## 1. Already done

Phases 0–13: installer, capability packs, full CakePHP coverage, security/PHP/engineering base, offline eval, adopter docs, Apache-2.0.

**Also done before Packagist cut:** CRUD harden (decisions, smells, security, AuthZ integration, eval depth), `muffin/trash` pack, core polish.

Coverage: [coverage-rules-skills.md](coverage-rules-skills.md).

---

## 2. Phase 14 — Packagist / public 1.0

### Goal

Publish and cut **1.0.0**.

### Deliver

1. Packagist publish `chrisshick/cakephp-agent` (human account; agent prepares checklist/docs)
2. Triage [pre-1.0-review.md](pre-1.0-review.md)
3. Version bump to **1.0.0**; CHANGELOG / README status
4. Git tags (`v0.9.0` if missing, then `v1.0.0`)
5. Optional soak in a real CakePHP app

### Accept

- [ ] Packagist path clear (or blocked-on-human documented)
- [ ] pre-1.0 items triaged
- [ ] 1.0 version/tag plan executed or ready
- [ ] validate / phpunit / phpstan / eval green

### Kickoff

```text
Read docs/HANDOFF-phase-14.md and docs/pre-1.0-review.md.
Phase 13 coverage + security/PHP base is done. Prepare Packagist and 1.0.0 cut.
```
