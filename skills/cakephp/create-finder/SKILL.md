---
name: create-finder
description: Create or refine an idiomatic CakePHP custom finder for reusable query semantics on a Table.
---

# Create finder

## Objective

Add or improve a custom finder so reusable query semantics live on the Table (`find('name')`) instead of duplicated controller/`where` clauses.

## Use when

- The same query conditions/contain/select appear in multiple places.
- Introducing named query semantics (`published`, `forUser`, `active`).
- Refactoring ad-hoc query building into a Table finder.

## Do not use when

- The logic is a cross-cutting persistence feature for many Tables (consider a Behavior — see decision).
- The work is primarily validation or application rules.
- You need association definitions (use `add-association`).

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Locate the target Table and existing `find*` methods / `finders` map.
3. Check neighboring controllers/commands for duplicated conditions.
4. Decide finder vs behavior with `choose-cakephp-abstraction` if unclear.

## Workflow

1. Inspect existing finders and call sites.
2. Confirm ownership: custom finder on this Table (not a god Behavior).
3. Implement `findX(SelectQuery $query, array $options): SelectQuery` (CakePHP 5 naming/types as used by the project).
4. Keep finder focused on query composition; avoid HTTP or response concerns.
5. Update call sites to `find('x', ...)` / `findX` per project style.
6. Add or adjust Table/integration tests for the finder semantics.

## Framework decisions

- `knowledge/decisions/finder-vs-behavior`
- `knowledge/decisions/contain-vs-matching` when filtering by associated data
- `knowledge/decisions/entity-accessor-vs-query-calculation` when “computed” values belong in SQL vs Entity

## Anti-patterns

- Duplicating the same `where` in every controller.
- Behaviors that accumulate unrelated one-Table query helpers.
- Over-eager `contain` inside a finder “just in case.”
- Putting authorization-only-in-UI assumptions inside finders without project auth patterns.

## Validation

- Finder is reachable via CakePHP’s finder conventions used in the project.
- Call sites no longer duplicate the core conditions.
- Tests cover the semantic (included/excluded rows).

## Completion criteria

- Finder implemented, documented by name in code, tested, and used by at least the motivating call site.
