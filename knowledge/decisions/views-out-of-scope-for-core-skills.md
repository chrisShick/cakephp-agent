---
id: views-out-of-scope-for-core-skills
type: decision
scope: views
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: medium
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/views.html
last_verified: 2026-08-10
related: [plugin-vs-application-code]
evaluations: [presentation-follows-project-templates, reject-blade-as-cakephp-default]
---

# Views out of scope for core skills

## Use cases

- Deciding how deeply agents should author templates/cells/helpers.
- Choosing whether to introduce a new template engine.

## Decision questions

1. Does the project already have templates/layouts/helpers to mirror?
2. Is the request primarily ownership/ORM/HTTP, not visual design systems?
3. Is someone proposing Blade/Livewire in a vanilla CakePHP app?

## Recommended outcome

- **Follow project templates**; do not expand a deep core skill catalog for views in v1.
- Presentation changes stay thin and convention-matching; domain stays on Tables.

## Rejected alternatives

- Inventing Blade/Livewire/Twig as CakePHP defaults.
- Putting queries/persistence into templates.

## Exceptions

- Projects that already standardize on a cell/helper architecture — extend that, don’t replace it.
- A future pack may deepen views when demanded.

## Examples

Adjust an element to show a field already on the entity — fine. Replace templates with Blade — reject by default.

## Evaluations

- `presentation-follows-project-templates`
- `reject-blade-as-cakephp-default`
