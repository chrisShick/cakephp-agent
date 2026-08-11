# FriendsOfCake CRUD extension

Reference capability pack for **`friendsofcake/crud` ^7.0** (CakePHP 5).

## Detection

Enabled automatically when Composer reports `friendsofcake/crud` satisfying `^7.0`.

- Unsupported majors (e.g. 6.x) are reported as **incompatible** — rules/skills are not installed.
- Disable with `cakephp-agent install --without=friendsofcake-crud` or config `extensions.disable`.

## Contents

- **Rules** — CRUD controller design, actions, events, listeners, configuration, ORM boundaries, API, testing
- **Skills** — analyze/create/review CRUD controllers and listeners; select CRUD events; debug requests
- **Decision** — `knowledge/decisions/crud-listener-vs-orm-callback.md` (package-local; not installed into CakePHP-only projects)

## Provenance

Verify APIs against [CRUD docs](https://crud.readthedocs.io/en/latest/) and the [FriendsOfCake/crud](https://github.com/FriendsOfCake/crud) source for the installed version.

Listener path mirroring (`src/Listener/...` matching controllers) is a **package recommendation**, overridable via `.ai/`.
