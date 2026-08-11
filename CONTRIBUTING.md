# Contributing

1. Read [docs/plans/cakephp-agent-unified-plan.md](docs/plans/cakephp-agent-unified-plan.md).
2. Implement the next incomplete phase as a vertical slice (code → tests → validation → docs).
3. For CakePHP/plugin behavior, verify against current official docs/source and record provenance under `knowledge/sources/` when adding framework-sensitive content.
4. Distinguish FRAMEWORK REQUIREMENT vs PACKAGE RECOMMENDATION vs PROJECT CONVENTION.
5. Run `composer check` before opening a PR.

Do not mechanically translate Laravel concepts into CakePHP.
