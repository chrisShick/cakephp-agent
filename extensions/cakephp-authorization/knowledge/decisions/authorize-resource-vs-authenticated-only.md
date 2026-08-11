---
id: authorize-resource-vs-authenticated-only
type: decision
scope: security
framework: cakephp
package: cakephp/authorization
framework_versions: [">=5.0 <6.0"]
package_versions: [">=3.0 <4.0"]
priority: critical
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/authorization/3/en/index.html
last_verified: 2026-08-10
related: [authentication-vs-authorization]
evaluations: [idor-requires-resource-authorization]
---

# Authorize resource vs authenticated-only

## Use cases

- Protecting view/edit/delete of a specific record.
- Hardening APIs that accept primary keys from the client.

## Decision questions

1. Does the endpoint load a concrete resource by client-supplied id?
2. Could another user guess/change that id (IDOR)?
3. Is “logged in” sufficient, or must ownership/role on **this** resource be checked?

## Recommended outcome

- **Authorize the loaded resource** (policy on the entity) for sensitive reads/writes.
- Use query scoping for collections.
- “Authenticated only” is insufficient for per-record ownership.

## Rejected alternatives

- Checking only that an identity exists.
- Hiding buttons in the UI without server checks.

## Exceptions

- Truly global actions with no resource (rare) may use action-level checks — still fail closed.

## Examples

- `GET /articles/15` → load article → authorize `view`/`edit` on that article.

## Evaluations

- `idor-requires-resource-authorization`
