---
name: inspect-before-coding
description: Shared CakePHP discovery workflow — inspect project conventions, Composer packages, and existing abstractions before inventing new ones.
---

# Inspect before coding

## Objective

Establish facts about the project and CakePHP surface before writing or refactoring code, so agents follow existing conventions instead of inventing parallel ones.

## Use when

- Starting non-trivial CakePHP work (new feature, refactor, bugfix with architectural impact).
- Another skill says to run discovery first.
- The project is unfamiliar or `.ai/` / Composer state may change recommendations.

## Do not use when

- The change is a one-line typo/fix with no ownership or convention questions.
- You already completed this discovery in the same task and nothing relevant changed.

## Inputs to discover

Inspect the **relevant subset** for the task (not every item every time):

1. `composer.json` / `composer.lock` — CakePHP version, PHP version, installed plugins/packages.
2. Project overlays — `.ai/` (architecture, conventions, overrides).
3. App bootstrap — `Application.php`, middleware queue, plugin loading.
4. Routes for the feature area.
5. Target Controller / Table / Entity and neighboring classes of the same kind.
6. Existing Behaviors, Components, event listeners, custom finders, validators, and application rules in that area.
7. Tests and fixtures that encode expected behavior.
8. Coding standards / PHPStan config if the change must match project tooling.

## Workflow

1. Clarify the task concern (HTTP, query, persistence invariant, side effect, etc.).
2. Read Composer metadata for CakePHP and **only recommend APIs from packages that are present**.
3. Read `.ai/` if present; treat it as highest project precedence.
4. Locate existing patterns for the same concern; prefer extending them.
5. Summarize findings: versions, relevant packages, established abstraction, gaps.
6. Only then proceed to design or edit (often via `choose-cakephp-abstraction` or a task skill).

## Framework decisions

- Discovery is shared — other skills **reference this skill** instead of pasting long discovery walls.
- Optional plugins are not assumed unless Composer (or an enabled cakephp-agent extension) shows them.
- Prefer CakePHP-native extension points over invented layers.

## Anti-patterns

- Inventing a new folder, service, or naming convention before checking the project.
- Recommending FriendsOfCake CRUD, Auth, Search, or other plugin APIs without evidence they are installed.
- Cargo-culting Laravel concepts (FormRequest, Eloquent scopes, Artisan, Gate).
- Skipping `.ai/` when it exists.

## Validation

- You can name CakePHP major version and whether key packages for the task are installed.
- You can point to at least one existing project pattern (or explicitly note none exists).
- Recommendations do not depend on undetected plugins.

## Completion criteria

- Discovery notes cover versions, relevant packages, and existing abstractions for the concern.
- No new convention invented without checking for an existing one.
- Ready to choose an abstraction or execute a task skill.
