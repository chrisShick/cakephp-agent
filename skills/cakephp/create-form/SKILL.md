---
name: create-form
description: Add or fix a CakePHP HTML form with FormHelper, entity marshaling, and FormProtection/CSRF participation.
---

# Create form

## Objective

Implement an HTML form that posts into a thin controller action, marshals via Table `newEntity`/`patchEntity`, and participates in the app’s FormProtection/CSRF setup.

## Use when

- Adding create/edit forms or fixing CSRF/FormProtection failures on HTML posts.
- Replacing raw HTML forms that bypass FormHelper conventions in a FormHelper app.

## Do not use when

- Pure JSON API without cookie-session HTML forms — follow API endpoint skill and project CSRF policy.
- The real gap is validation vs rules — use those skills after the form posts.

## Inputs to discover

1. Follow **`inspect-before-coding`**.
2. Inspect Application middleware/components for CSRF/FormProtection.
3. Copy neighboring FormHelper + template patterns (unlocked fields, entity context).
4. Confirm Table validation/rules for the entity.

## Workflow

1. Build/adjust the template with FormHelper and the entity.
2. Ensure the action uses `patchEntity`/`newEntity` + `save` (or project equivalent).
3. Keep FormProtection/CSRF enabled; unlock fields only with explicit reason.
4. Surface validation/rule errors back to the form the project’s way.
5. Add an integration test that posts with tokens as neighboring tests do.

## Framework decisions

- Anti-pattern `csrf-formprotection-required`
- `validation-vs-application-rule` for field vs invariant ownership

## Anti-patterns

- JavaScript-only CSRF for cookie-session HTML forms.
- FormRequest inventions.
- Persistence logic in the template.

## Validation

- Valid posts save; invalid posts re-render errors; forged posts are rejected by FormProtection/CSRF.

## Completion criteria

- Form + action + protection aligned with project patterns and tested.
