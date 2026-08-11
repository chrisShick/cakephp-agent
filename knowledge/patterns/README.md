# patterns

Reusable CakePHP patterns are documented as **decisions** + skills. Start here:

| Pattern | Decision / skill |
|---|---|
| ORM-first domain access | `decisions/orm-vs-connection-sql` |
| Custom finder for query semantics | `decisions/finder-vs-behavior`, skill `create-finder` |
| Association load vs filter | `decisions/contain-vs-matching`, `contain-vs-join` |
| QueryExpression + bindings | `decisions/orm-vs-connection-sql`, rule `advanced-orm` |
| Transaction around multi-write | `decisions/transaction-vs-independent-save` |
| CounterCache for denormalized counts | skill `add-advanced-finder`, rule `advanced-orm` |

Add `patterns/*.md` only when a cross-cutting recipe is not already covered by a decision + skill pair.
