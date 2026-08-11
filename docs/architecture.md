# Architecture

CakePHP Agent is a **Composer library** that installs CakePHP-native AI guidance into editor trees. It does not boot inside the CakePHP HTTP runtime.

## Layers

```text
knowledge/          canonical decisions & anti-patterns (package-local)
rules/ skills/ agents/
extensions/ + integrations/   capability packs (Composer-detected)
        ↓
installer + editor adapters (Cursor / Claude / Codex)
        ↓
project .cursor|.claude|.codex/cakephp-agent/  +  .ai/ overlays
```

## Design rules

1. **Discover before prescribing** — skills/agents start with project inspection.
2. **Capability honesty** — do not assume CRUD/Search/Auth APIs unless packs are enabled.
3. **Safe install** — lock file, dry-run, preserve local edits, never touch `.ai/` or app `src/`.
4. **Decisions own ownership logic; skills own workflows; rules stay thin.**
5. **Evaluate critical boundaries** — curated fixtures with positive and negative cases.

## Editors

| Editor | Rules | Skills | Agents |
|---|---|---|---|
| Cursor | yes | yes | yes |
| Claude Code | yes | yes | yes |
| Codex | yes | yes | no |

Details: [editors.md](editors.md).

## Versioning

- Package CLI version: `Application::VERSION` (currently **0.9.0** adopter preview).
- 1.0 requires Packagist (or equivalent) + remaining trust polish in [pre-1.0-review.md](pre-1.0-review.md).

## Related docs

- [Install existing app](install-existing-app.md)
- [Extension authoring](extension-authoring.md)
- [Evaluation baselines](evaluation-baselines.md)
- [Unified plan](plans/cakephp-agent-unified-plan.md) (historical blueprint)
