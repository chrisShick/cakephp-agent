---
name: debug-authorization
description: Diagnose unexpected CakePHP Authorization allow/deny outcomes.
---

# Debug authorization

## Objective

Determine why an action is forbidden or incorrectly allowed and fix the policy/call site.

## Use when

- 403/forbidden surprises; owner cannot edit; stranger can.
- Suspect missing authorize or wrong resource passed.

## Do not use when

- User cannot log in at all and Authentication is installed — start with AuthN debug.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Reproduce with two identities; capture action, resource id, policy method.
3. Inspect authorize call arguments and policy logic.

## Workflow

1. Confirm Authorization middleware/component is active as designed.
2. Verify the correct policy method runs.
3. Check resource type/id and identity shape.
4. Fix policy or call site; add allow+deny tests.
5. If identity is missing, inspect identity source (do not invent AuthN if absent).

## Framework decisions

- Fail closed; fix policies rather than bypassing authorization

## Anti-patterns

- Commenting out authorize to “unblock”
- Broadening `_accessible` instead of fixing policy

## Validation

- Expected allow/deny matrix restored.

## Completion criteria

- Root cause + fix + tests.
