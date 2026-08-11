# Extension authoring

Capability packs live under **`extensions/`** (Composer-detected plugins) or **`integrations/`** (activate when peer extensions are all enabled). Each pack is a directory with a **`manifest.json`** plus optional `rules/`, `skills/`, `agents/`, and pack-local `knowledge/`.

Contract schema: [`schemas/extension-manifest.schema.json`](../schemas/extension-manifest.schema.json).

Reference example: [`extensions/friendsofcake-crud/`](../extensions/friendsofcake-crud/) (`friendsofcake/crud` ^7.0).

## Layout

```text
extensions/my-pack/
  manifest.json
  rules/
    *.mdc
  skills/
    my-skill/
      SKILL.md
  agents/          # optional
  knowledge/       # optional pack-local decisions/notes
```

Integrations use the same layout under `integrations/` with `"type": "integration"`.

Test-only fakes belong in **`tests/fixtures/extensions/`**. Consumer installs load `extensions/` and `integrations/` only — fixtures are not registered for adopters.

## `manifest.json` fields

| Field | Required | Notes |
|---|---|---|
| `id` | yes | Stable pack id (e.g. `friendsofcake-crud`) |
| `name` | yes | Human-readable name |
| `version` | yes | Pack version string |
| `type` | yes | `composer-package`, `architecture`, `infrastructure`, or `integration` |
| `description` | no | Short summary |
| `detect.composer` | no | List of `{ "package", "constraint?" }` — enable when Composer satisfies |
| `requires` | no | Map of package → constraint (e.g. `"cakephp": "^5.0"`) |
| `dependsOn` | no | Other extension **ids** that must be enabled first (transitive) |
| `conflictsWith` | no | Extension ids that cannot be co-enabled |
| `rules` / `skills` / `agents` | no | Glob lists relative to the pack root (e.g. `"rules/*.mdc"`, `"skills/*"`) |
| `defaultEnabledWhenDetected` | no | Default `true` when detection matches |
| `activateWhenAllExtensionsPresent` | no | Integration packs: enable when all listed extension ids are enabled |

Point `$schema` at the package schema for editor validation:

```json
{
  "$schema": "../../schemas/extension-manifest.schema.json",
  "id": "friendsofcake-crud",
  "name": "FriendsOfCake CRUD",
  "description": "Rules and workflows for applications using FriendsOfCake CRUD (CakePHP 5 / CRUD 7.x).",
  "version": "1.0.0",
  "type": "composer-package",
  "detect": {
    "composer": [
      {
        "package": "friendsofcake/crud",
        "constraint": "^7.0"
      }
    ]
  },
  "requires": {
    "cakephp": "^5.0"
  },
  "dependsOn": [],
  "conflictsWith": [],
  "rules": ["rules/*.mdc"],
  "skills": ["skills/*"],
  "agents": [],
  "defaultEnabledWhenDetected": true
}
```

### Detection vs dependencies

- **`detect.composer`** — “is this plugin installed at a compatible version?”
- **`dependsOn`** — “another cakephp-agent pack must be enabled first” (see test fixture `test-fake-addon` → `test-fake-plugin`)
- **`activateWhenAllExtensionsPresent`** — integration packs (e.g. `integrations/cakephp-authentication-authorization`) without their own Composer detect

Incompatible Composer majors are reported by `explain` / `detect` and **not** installed.

## Rules and skills

- Rules: `.mdc` files under `rules/` (include frontmatter used by core validation where applicable).
- Skills: one directory per skill with `SKILL.md`. Prefer referencing the core **`inspect-before-coding`** skill instead of duplicating discovery walls.
- Do not assume optional plugins unless this pack’s detection (or an integration peer) proves they are present.
- Distinguish truth levels: framework requirement vs package recommendation vs project convention (see [CONTRIBUTING.md](../CONTRIBUTING.md)).

## Validate

From the **package** root:

```bash
php bin/cakephp-agent validate
# or
composer validate-content
```

This checks manifests, knowledge frontmatter, skills/agents contracts, and evaluations.

Also useful while iterating:

```bash
php bin/cakephp-agent extensions --project=/path/to/fixture-app
php bin/cakephp-agent explain --project=/path/to/fixture-app
php bin/cakephp-agent install --editor=cursor --dry-run --project=/path/to/fixture-app
```

## Related

- [Editors](editors.md) — where packs land after install
- [Architecture](architecture.md)
