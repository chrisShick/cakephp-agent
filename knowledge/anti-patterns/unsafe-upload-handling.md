---
id: unsafe-upload-handling
type: anti-pattern
scope: security
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: critical
truth_level: PACKAGE_RECOMMENDATION
last_verified: 2026-08-10
---

# unsafe-upload-handling

## Symptoms

Uploaded files are stored using client filenames, without type/size checks, or directly under the webroot executable paths

## Why it matters

Enables malware hosting, path traversal, and unexpected code execution

## False positives

Admin-only import tools with strict allow-lists and non-public storage may be acceptable when audited

## Detection guidance

Inspect upload actions for `getClientFilename` used as storage path, missing MIME/extension allow-lists, and writes into `webroot` without access controls.

## Preferred refactoring

Validate type/size; generate server-side names; store outside webroot or behind controlled download actions

## When no refactor is warranted

Trusted internal pipelines with non-user-controlled paths and no HTTP exposure.
