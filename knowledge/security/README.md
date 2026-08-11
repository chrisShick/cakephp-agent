# security

Security guidance is enforced via rules/skills and these anti-patterns:

| Topic | Unit |
|---|---|
| Mass assignment | `anti-patterns/mass-assignment-overexposure` |
| Authz only in UI | `anti-patterns/authorization-only-in-ui` |
| Serialization leaks | `anti-patterns/serialization-overexposure` |
| FormProtection / CSRF | `anti-patterns/csrf-formprotection-required` |
| Uploads | `anti-patterns/unsafe-upload-handling` |
| SQL injection via concat | `anti-patterns/unsafe-sql-concatenation` |
| ORM bypass (injection + ownership) | `decisions/orm-vs-connection-sql` |
| Open redirects | `anti-patterns/open-redirect` |
| Debug in production | `anti-patterns/debug-in-production` |

Skills: `review-cakephp-security`. Rule: `rules/cakephp/security`.
