# Project overlays (`.ai/`)

Put **project-specific** architecture and convention notes under **`.ai/`** at the application root.

## Contract

- The installer **never overwrites** paths under `.ai/`.
- **`uninstall` never removes** `.ai/` (only lock-tracked managed editor files).
- Overlays are highest **project** precedence: agents and skills should treat them as team truth that can override package recommendations.

Use overlays for domain boundaries, intentional exceptions, and conventions that differ from package defaults — not for copying CakePHP docs.

## Suggested layout

```text
.ai/
  architecture.md      # recommended starting point
  conventions.md       # optional team norms
  decisions/           # optional ADR-style notes
```

Start with **`.ai/architecture.md`**. Add other files only when they help agents stay aligned.

## Discovery

Core skill **`inspect-before-coding`** (and agents that reference it) tells the model to read `.ai/` before inventing conventions or recommending plugin APIs. Prefer extending existing project patterns named in overlays.

Example snippet for `.ai/architecture.md`:

```markdown
# Architecture

## Domain

- Billing and membership live in `src/Billing/`; Controllers stay thin and call Tables/Application services in that namespace.
- Do not introduce a generic “Repository” layer over Tables unless an ADR in `.ai/decisions/` says otherwise.

## Auth

- Identity: `cakephp/authentication`. Authorization policies live under `src/Policy/`.
- UI hiding is never sufficient — enforce in Authorization policies.

## Conventions

- Custom finders for reusable query shapes; Behaviors for cross-table persistence concerns.
- Listener path mirroring for FriendsOfCake CRUD is intentional in this app (see CRUD docs + team preference).
```

A fuller sample lives at [`examples/ai-overlay/architecture.md`](../examples/ai-overlay/architecture.md) — copy into your app as `.ai/architecture.md` and edit.

## Related

- [Install into an existing app](install-existing-app.md)
- [Editors](editors.md)
