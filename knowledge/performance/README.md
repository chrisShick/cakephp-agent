# performance

ORM performance smells and fixes:

| Topic | Unit / skill |
|---|---|
| Hidden N+1 | `anti-patterns/hidden-n-plus-one` |
| Over-eager contain | `anti-patterns/over-eager-contain` |
| Association loading budget | `decisions/contain-vs-matching`, `contain-vs-join` |
| Aggregate in query not accessor | `decisions/entity-accessor-vs-query-calculation` |
| Review workflow | skill `review-query-performance` |
| Diagnosis | skill `diagnose-orm-query` |

Rule: `rules/cakephp/performance`. Prefer fixing query shape before caching.
