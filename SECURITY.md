# Security Policy

This package installs into developer repositories and may later offer an opt-in Composer plugin. Treat it as supply-chain sensitive.

## Reporting

Report vulnerabilities via a **GitHub private security advisory**:

https://github.com/chrisShick/cakephp-agent/security/advisories/new

Do not file public issues for undisclosed vulnerabilities.

## Guarantees we aim for

- No arbitrary remote code execution during install
- No downloading executable scripts during install
- No modification of application source files
- Path traversal protection for extension/manifest paths
- Read-only Composer metadata inspection for detection
- Opt-in only for any future Composer plugin auto-install
- Project overlays under `.ai/` are never overwritten or uninstalled by this package
