---
id: authentication-vs-authorization
type: decision
scope: security
framework: cakephp
package: cakephp/authentication
framework_versions: [">=5.0 <6.0"]
package_versions: [">=4.0 <5.0"]
priority: critical
truth_level: PLUGIN_SEMANTIC
sources:
  - https://book.cakephp.org/authentication/3/en/index.html
  - https://book.cakephp.org/authorization/3/en/index.html
last_verified: 2026-08-10
related: []
evaluations: [authn-only-no-policy-apis, authz-only-no-authentication-apis]
---

# Authentication vs authorization

## Use cases

- Establishing who the actor is (login, tokens, session identity).
- Deciding whether that actor may perform an action on a resource.

## Decision questions

1. Is the question “who is this?” or “may they do this?”
2. Is `cakephp/authentication` installed?
3. Is `cakephp/authorization` installed?
4. Does the project already have a non-plugin permission model?

## Recommended outcome

- **Authentication plugin:** identity only.
- **Authorization plugin:** permissions/policies/scopes.
- **Neither / one only:** do not invent the missing plugin’s APIs; follow installed packages and `.ai/`.

## Rejected alternatives

- Treating authenticated as authorized for arbitrary resource ids.
- Recommending Policy APIs in AuthN-only apps.
- Requiring Authentication classes in AuthZ-only apps.

## Exceptions

- Custom SSO may supply identity without the Authentication plugin — still do not invent AuthZ APIs if Authorization is absent.

## Examples

- Login form → Authentication.
- “Can edit this article?” → Authorization policy (if installed) or project permission model.

## Evaluations

- `authn-only-no-policy-apis`
- `authz-only-no-authentication-apis`
