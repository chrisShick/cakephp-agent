# CakePHP Authorization extension

Capability pack for **`cakephp/authorization` ^3.0** (CakePHP 5).

## Boundaries

- Teaches **may this identity perform this action on this resource?**
- Do **not** assume Authentication plugin middleware/APIs unless that pack is also enabled.
- Identity may come from Authentication, custom middleware, or other project mechanisms — inspect before coding.
- Includes IDOR and mass-assignment awareness for authorization-sensitive surfaces.

## Provenance

Verify against [Authorization docs](https://book.cakephp.org/authorization/3/en/index.html) / plugin source for the installed major.
