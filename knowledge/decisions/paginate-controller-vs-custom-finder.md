---
id: paginate-controller-vs-custom-finder
type: decision
scope: http
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/controllers/pagination.html
  - https://book.cakephp.org/5.x/orm/retrieving-data-and-resultsets.html
last_verified: 2026-08-10
related: [finder-vs-behavior, contain-vs-matching]
evaluations: [list-endpoint-prefers-paginate-finder, reject-unbounded-all-for-ui-list]
---

# Paginate controller vs custom finder

## Use cases

- HTTP list endpoints that must page results.
- Reusable query semantics for those lists.

## Decision questions

1. Is the user facing a list that can grow large?
2. Are the query conditions reusable beyond one action?
3. Is Search installed for filter params?

## Recommended outcome

- **Controller pagination** over a **Table finder** (or Search filters when the pack applies).
- Keep query graphs out of the controller body.

## Rejected alternatives

- Unbounded `all()` for UI/API lists that should page.
- Duplicating large `where`/`contain` stacks in every paginate call.

## Exceptions

- Tiny admin tables with proven tiny cardinality may skip pagination if documented.
- Export/report jobs may stream differently via Commands.

## Examples

Articles index → `$this->paginate($this->Articles->find('published'))`.

## Evaluations

- `list-endpoint-prefers-paginate-finder`
- `reject-unbounded-all-for-ui-list`
