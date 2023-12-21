---
title: Error
---


The Stud.IP-JSON:API uses the error codes that are also used in the <a
href="http://jsonapi.org/format">JSON API specification</a>.
are used.


Error code | Meaning
---------- | -------
401 | Unauthorized - You do not have the required authorization.
403 | Forbidden - The requested operation is not available.
404 | Not Found - The desired resource or relation could not be found.
409 | Conflict - Restrictions in Stud.IP are violated when creating or changing resources or relations. Example: A resource of the wrong type is to be added to a relation.
500 | Internal Server Error - There is a problem on the server. Try again later!
