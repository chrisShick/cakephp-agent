---
id: search-filter-vs-custom-finder
type: decision
scope: plugins
framework: cakephp
package: friendsofcake/search
framework_versions: [">=5.0 <6.0"]
package_versions: [">=7.0 <8.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://github.com/FriendsOfCake/search
last_verified: 2026-08-10
related: [finder-vs-behavior]
evaluations: [search-filter-not-controller-where, search-absent-no-search-apis]
---

# Search filter vs custom finder

## Use cases

- Optional request-driven list filtering (query string / PRG).
- Reusable named query semantics across HTTP and non-HTTP entry points.

## Decision questions

1. Is the condition driven by optional UI filter params?
2. Must the same semantics run from CLI/jobs without Search?
3. Is `friendsofcake/search` installed?

## Recommended outcome

- **Search filter** for request-bound list filters when Search is installed.
- **Custom finder** for reusable query semantics (and when Search is absent).
- Combine when filters delegate to finders for shared pieces.

## Rejected alternatives

- Large controller `where` blocks for list UIs when Search is available.
- Replacing all finders with Search filters.

## Exceptions

- Project may standardize differently in `.ai/` — honor it.

## Examples

- `?q=cake` on index → Search like/value filter.
- `find('published')` used by mailers and controllers → custom finder.

## Evaluations

- `search-filter-not-controller-where`
- `search-absent-no-search-apis`
