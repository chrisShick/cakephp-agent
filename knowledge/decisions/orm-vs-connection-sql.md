---
id: orm-vs-connection-sql
type: decision
scope: orm
framework: cakephp
framework_versions: [">=5.0 <6.0"]
priority: critical
truth_level: PACKAGE_RECOMMENDATION
sources:
  - https://book.cakephp.org/5.x/orm.html
  - https://book.cakephp.org/5.x/orm/query-builder.html
  - https://book.cakephp.org/5.x/orm/database-basics.html
last_verified: 2026-08-11
related: [bulk-update-vs-entity-save, finder-vs-service, contain-vs-join, migration-vs-raw-sql]
evaluations:
  - prefer-orm-over-connection-execute
  - reject-connection-sql-for-simple-find
  - query-expression-preferred-over-string-sql
---

# ORM vs Connection SQL

## Use cases

- Reading or writing application domain rows (entities, associations, validation, rules).
- Needing SQL that the Query builder / expressions cannot express cleanly.
- One-off DBA, reporting, or break-glass SQL outside normal app workflows.

## Decision questions

1. Is this ordinary application data access (find, save, associate, filter)?
2. Can Table finders, `SelectQuery`, associations, or Query expressions express it?
3. Do you need entity marshaling, validation, application rules, or callbacks?
4. Is this schema change work (prefer Migrations when installed) rather than runtime SQL?
5. Is unbound string concatenation involved with user input?

## Recommended outcome

- **Default to the CakePHP ORM:** Table APIs, custom finders, associations (`contain` / `matching`), `newEntity` / `patchEntity` / `save`, and Query **expressions** with bindings.
- Prefer **Query expression callbacks** / bound fragments over hand-built SQL strings when you must drop closer to SQL.
- Use **`Connection::execute` / raw SQL** only when the ORM cannot express the operation reasonably — document why, bind all values, and keep it out of controllers when possible (Table finder, dedicated method, or command).

## Rejected alternatives

- Reaching for `Connection::execute` / PDO because it “feels simpler” for a normal find/save.
- Concatenating user input into SQL strings.
- Putting ad-hoc SQL in controllers when a Table finder or expression query fits.
- Treating bulk `updateAll` / `deleteAll` as interchangeable with `save()` when rules/callbacks matter (`bulk-update-vs-entity-save`).

## Exceptions

- Proven reporting / analytics SQL that is awkward as ORM graphs — isolate, bind, test.
- Database features the ORM does not model well (vendor-specific maintenance).
- Migrations / schema tooling (see `migration-vs-raw-sql` when `cakephp/migrations` is installed).
- Intentional bulk SQL for performance with documented lifecycle tradeoffs.

## Examples

List published articles → `$articles->find('published')`, not `Connection::execute('SELECT …')`.  
Complex `WHERE` with user filters → `where(function (QueryExpression $exp) { … })` with bindings, not string concat.  
Vacuum / vendor maintenance → Connection SQL in a command, not a controller action.

## Evaluations

- `prefer-orm-over-connection-execute`
- `reject-connection-sql-for-simple-find`
- `query-expression-preferred-over-string-sql`
