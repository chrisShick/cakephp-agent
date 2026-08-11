---
name: review-cakephp-security
description: Review CakePHP changes for mass assignment, IDOR, CSRF, uploads, serialization, redirects, secrets, and SQL safety.
---

# Review CakePHP security

## Objective

Perform a CakePHP-native security review of a diff or area: exploitable ownership bugs first, then exposure and config hygiene — without inventing Auth plugin APIs when absent.

## Use when

- Reviewing PRs that touch authz, forms, uploads, entities, APIs, or redirects.
- Hardening before a release.
- The security reviewer agent needs a procedural skill companion.

## Do not use when

- The change is pure presentation with no data mutation — still skim redirects/XSS vectors lightly, then stop.
- You need to implement a fix immediately — review first, then hand off (`create-form`, Auth pack skills, etc.).

## Inputs to discover

1. Follow **`inspect-before-coding`** on touched paths.
2. Confirm Composer AuthN/AuthZ/CRUD presence before prescribing plugin APIs.
3. Inspect `_accessible`, `$_hidden`, FormProtection/CSRF setup, upload handlers, and id→mutate paths.
4. Check `debug` / DebugKit expectations for the environment.

## Workflow

1. **IDOR / authz:** ensure resource loads are authorized/scoped server-side.
2. **Mass assignment:** reject wide `_accessible` on sensitive fields.
3. **CSRF/FormProtection:** state-changing HTML forms must participate.
4. **Uploads:** validate type/size; server-side names; not raw webroot exec paths.
5. **Serialization:** hide secrets; shape API output explicitly.
6. **SQL:** no unbound concatenation; prefer ORM expressions (`unsafe-sql-concatenation`, `orm-vs-connection-sql`).
7. **Redirects:** no open redirects from user input.
8. **Secrets/debug:** no credentials in code/logs; debug off in production.
9. Produce findings with severity, evidence, and CakePHP-native remediation skills.

## Framework decisions

- Auth packs when installed; otherwise do not invent plugin APIs
- Anti-patterns: mass-assignment-overexposure, authorization-only-in-ui, csrf-formprotection-required, unsafe-upload-handling, serialization-overexposure

## Anti-patterns

- Reviewing without Composer/`inspect-before-coding`
- Demanding Laravel Gates/Policies when Authorization is absent
- Nitpicking style over exploitable bugs

## Validation

- Each finding cites code evidence and a concrete fix path.
- Recommendations match installed packages.

## Completion criteria

- Written prioritized security review with remediations and follow-up skills.
