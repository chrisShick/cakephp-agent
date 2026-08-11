# Security Policy

This package installs into developer repositories and may later offer an opt-in Composer plugin. Treat it as supply-chain sensitive.

## Reporting

Please open a private security advisory or email the maintainers. Do not file public issues for undisclosed vulnerabilities.

## Guarantees we aim for

- No arbitrary remote code execution during install
- No downloading executable scripts during install
- No modification of application source files
- Path traversal protection for extension/manifest paths
- Read-only Composer metadata inspection for detection
- Opt-in only for any future Composer plugin auto-install
