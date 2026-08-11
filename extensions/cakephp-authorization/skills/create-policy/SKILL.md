---
name: create-policy
description: Create or update a CakePHP Authorization policy for a resource.
---

# Create policy

## Objective

Add a policy that correctly allows/denies actions for a resource using `cakephp/authorization`.

## Use when

- New resource permissions.
- Replacing ad-hoc controller permission ifs with policies.

## Do not use when

- Authorization package absent.
- Task is only login/identity (Authentication pack).

## Inputs to discover

1. Follow **`inspect-before-coding`**; confirm `cakephp/authorization` ^3.
2. Inspect existing Policy classes and how controllers authorize.
3. Determine identity source without assuming Authentication APIs.

## Workflow

1. Create/update policy per plugin + project naming.
2. Implement action methods with clear allow/deny.
3. Wire authorization calls in controllers/services as the app already does.
4. Keep entity mass-assignment tight for update paths.
5. Test allow and deny (including IDOR-style ID access).

## Framework decisions

- Prefer policies over scattered controller conditionals
- IDOR: authorize after load (`rules/idor-and-scoping.mdc`)

## Anti-patterns

- UI-only authorization
- Assuming Authentication plugin classes when not installed

## Validation

- Forbidden identities cannot mutate/view; allowed ones can.

## Completion criteria

- Policy + call sites + tests for allow/deny.
