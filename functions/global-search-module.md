---
id: global-search-module
title: Modules for the global search
siebar_label: Global search
---

# Modules for the global search
The global search (as of Stud.IP 4.1) has a separate class for each search category, which performs the necessary operations to find suitable search results.

Each module is derived from the abstract superclass `GlobalSearchModule` and implements the following methods:

## Methods
* `getName()` returns a display name for the module. This appears in the configuration of the search modules and in the categorized overview of the search results.
* `getSQL($search)` generates the SQL query to be executed to read the search results from the database. Complete SQL code must be returned here, i.e. no prepared statement or SQL with parameters. To keep the search query performant, `LIMIT` should be used to limit the number of results. The variable `GLOBALSEARCH_MAX_RESULT_OF_TYPE` from the global configuration is used to control how many results are displayed per category in the quick search. As each category is clickable and then displays more results, `LIMIT (4 * Config::get()->GLOBALSEARCH_MAX_RESULT_OF_TYPE)` can be specified, for example.
* `filter($data, $search)` prepares a single search result for further processing. A single database row is passed here, from which the attributes required for the display are then generated. The return is an array of the type

```php
[
    'id' => <Stud.IP-ID of the object>,
    'name' => <title/name of the object, best marked via GlobalSearchModule::mark>,
    'url' => <URL to call up the result in the system, e.g. profile, event page, etc.>,
    'date' => <creation/modification date/semester>,
    'description' => <more detailed information, description text, text excerpts etc., best marked via GlobalSearchModule::mark>,
    'additional' => <Further data, e.g. subtitle, list of lecturers, etc.>,
    'expand' => <URL for further search>,
    'img' => <URL of an image/avatar for this result>
]
```
* `getSearchURL($searchterm)` returns the URL of a further search, e.g. forum search within a course or Stud.IP-wide course search
## Marking of the search term
To mark why a result is displayed at all, the static method `GlobalSearchModule::mark($string, $query, $longtext = false, $filename = true)` can be used, which marks the search word in a given string and shortens it if necessary. The HTML tag `<mark>` is used for this.
# GlobalSearchFulltext
Instead of the "normal" SQL search via `LIKE`, the full text search via `MATCH AGAINST` can also be used in certain MySQL versions. The interface `GlobalSearchFulltext` must be implemented for this. This has three methods:
* `enable()`: Actions that are to be executed when the full-text search of this module is activated, e.g. creation of necessary table indices
* `disable()`: Actions to be executed when the full-text search of this module is deactivated, e.g. removing table indices
* `getFulltextSearc($search)` generates the SQL query analogous to `GlobalSearchModule->getSQL()` to retrieve the search results from the database
# Activation and sorting of search modules
A new search module class must still be activated under Admin->Global Search, where you can also sort the order in which the modules are queried and the search results are displayed using drag & drop.
