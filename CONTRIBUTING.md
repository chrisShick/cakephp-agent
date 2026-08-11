# Contributing

Thanks for helping improve CakePHP Agent. This package is a **public beta (`1.0.0-beta.x`)** — stable 1.0 after soak.

## Setup

```bash
git clone https://github.com/chrisShick/cakephp-agent.git
cd cakephp-agent
composer install
```

Read [docs/plans/cakephp-agent-unified-plan.md](docs/plans/cakephp-agent-unified-plan.md) and the current handoff under `docs/HANDOFF-*.md` before large changes.

## What to work on

1. Prefer the next incomplete phase (or an agreed gap from [docs/pre-1.0-review.md](docs/pre-1.0-review.md)) as a vertical slice: code → tests → validation → docs.
2. For CakePHP/plugin behavior, verify against current official docs/source and record provenance under `knowledge/sources/` when adding framework-sensitive content.
3. Extension packs go under `extensions/` or `integrations/` — see [docs/extension-authoring.md](docs/extension-authoring.md).
4. Test fakes belong in `tests/fixtures/extensions/` only (not shipped as consumer packs).

## Check commands

Run before opening a PR:

```bash
composer test
composer phpstan
composer validate-content   # php bin/cakephp-agent validate
composer eval               # php bin/cakephp-agent eval (offline self-check)
composer check              # composer validate --strict + test + phpstan
```

`validate` and `eval` are package-maintainer tools; keep them green when you touch content, manifests, or evaluations.

## Pull requests

**`main` is PR-only** (GitHub ruleset). Do not push commits directly to `main` — open a branch, open a PR, get approval (code owners), and wait for CI (`composer check`, content validation, and eval).

- Keep scope focused — no drive-by refactors unrelated to the change.
- Include or update tests for installer/extension/validation behavior.
- Update public docs when changing CLI flags, install paths, or extension contracts.
- Do not commit secrets, vendor trees, or local editor lock noise from dogfooding unless intentional.

Release tags (`v*`) are protected; only repository Admins should create version tags (e.g. `v1.0.0-beta.1`).

## Truth levels

When writing rules, decisions, or anti-patterns, label content honestly:

| Level | Meaning |
|---|---|
| `FRAMEWORK_REQUIREMENT` | CakePHP (or PHP) requires this |
| `FRAMEWORK_DEFAULT` | Framework default / conventional framework behavior |
| `PLUGIN_SEMANTIC` | Semantics of a specific plugin API |
| `PACKAGE_RECOMMENDATION` | CakePHP Agent guidance (overridable) |
| `PROJECT_CONVENTION` | Belongs in `.ai/` for a given app — do not hard-code as universal |

Distinguish **FRAMEWORK REQUIREMENT** vs **PACKAGE RECOMMENDATION** vs **PROJECT CONVENTION** in prose as well as frontmatter.

## Do not translate Laravel

Do not mechanically map Laravel concepts (FormRequest, Eloquent, Artisan, Gate, ServiceProvider, Blade, etc.) into CakePHP. Prefer CakePHP-native extension points (Tables, Entities, Behaviors, Components, middleware, events, Application rules).

## License

Contributions are accepted under the [Apache License 2.0](LICENSE). By submitting a pull request, you agree your contribution is licensed under the same terms.

## Related

- [Security policy](SECURITY.md)
- [Install for adopters](docs/install-existing-app.md)
