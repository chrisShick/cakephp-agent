---
id: config-vs-hardcoded-service
type: decision
scope: architecture
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: high
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/development/configuration.html
  - https://book.cakephp.org/5.x/development/dependency-injection.html
last_verified: 2026-08-10
related: [plugin-vs-application-code]
evaluations: [shared-client-prefers-application-services, reject-hardcoded-credentials-in-controller]
---

# Config vs hardcoded service

## Use cases

- Shared HTTP clients, SDK wrappers, feature flags, credentials.
- One-off Table usage with no infrastructure.

## Decision questions

1. Is the value environment-specific (secret, host, key)?
2. Is the dependency shared across controllers/commands?
3. Does the project already use `Application::services()`?

## Recommended outcome

- **Config/env** for values; **Application::services()** for shared wiring when DI is established.
- Keep domain Tables free of hardcoded infrastructure credentials.

## Rejected alternatives

- Hardcoding API keys/hosts in controllers.
- Inventing Laravel ServiceProviders/facades as the CakePHP default.

## Exceptions

- Throwaway scripts may hardcode with clear warnings — not production app code.

## Examples

Mail API key in `app_local.php`; Mailer client registered in `Application::services()`.

## Evaluations

- `shared-client-prefers-application-services`
- `reject-hardcoded-credentials-in-controller`
