# Example `.ai/` architecture overlay

Copy this file to your CakePHP application as **`.ai/architecture.md`** and edit to match your domain. CakePHP Agent never overwrites `.ai/`.

---

# Architecture

## Product shape

- Multi-tenant church management app (example). Tenancy is resolved in middleware; Tables must not assume a global “current church” without an explicit finder/condition.
- Bounded contexts: **People**, **Giving**, **Events**. Prefer namespaces under `src/` that match those contexts over a flat Controllers-only tree for new code.

## HTTP and domain

- Controllers orchestrate request/response; persistence and invariants stay on Tables, Entities, and Application rules.
- Do not add a repository wrapper over every Table. Prefer CakePHP Tables, custom finders, and Behaviors.

## Auth

- Authentication and Authorization plugins are both installed.
- Authorization is enforced in policies / request authorization — never UI-only.

## Plugins we intentionally use

- FriendsOfCake CRUD for admin resource controllers; listener classes mirror controller paths under `src/Listener/` (team convention).
- FriendsOfCake Search for filter forms bound to indexed fields only — do not invent Search filters for columns that are not searchable in this app.

## Explicit non-goals

- No Laravel-style FormRequest / Eloquent / Gate translations.
- No new top-level `Services/` folder unless an ADR under `.ai/decisions/` lands first.
