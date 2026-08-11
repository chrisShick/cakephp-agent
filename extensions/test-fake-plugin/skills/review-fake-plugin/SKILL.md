---
name: review-fake-plugin
description: Review usage of the fake plugin (Phase 2 test skill).
---

# Review fake plugin

## Objective

Confirm the fake plugin extension skill is installed only when detected.

## Use when

- Testing cakephp-agent extension resolution.

## Do not use when

- Working on real CakePHP applications (this is a test pack).

## Workflow

1. Confirm `cakephp-agent/fake-plugin` is present in Composer metadata.
2. Inspect project usage of the fake plugin.
3. Summarize findings.
