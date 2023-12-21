---
id: tree structures
title: Tree structures
sidebar_label: Tree structures
---

With Stud.IP 5.4, the old implementations for the display and management of study areas and institution hierarchies were re-implemented and a generic solution for the mapping of tree structures was created.

## PHP
The basis is the new interface `StudipTreeNode`. This provides generic methods for accessing trees:
- `static getNode($id)` returns the node with the specified ID
- `hasChildNodes()` shows whether the current node has child nodes
- `getChildNodes()` returns the child nodes of the current node
- `static getCourseNodes($course_id)` returns the nodes to which the specified course is assigned.
- getter methods for ID, name, description, image/icon
- `countCourses($semester_id, $semclass, $withChildren)` counts the courses that are assigned to this node (or, depending on the setting `withChildren`, also to the subnodes), filtered by semester and category
- `getCourses($semester_id, $semclass, $searchterm, $withChildren)` returns the courses assigned to this node (or, depending on the setting `withChildren`, also to the sub-nodes), filtered by semester, category and/or search term
- `getAncestors` returns a list of all "ancestors" in the hierarchy, in the form `{ id, name }`

Implementing classes are currently `StudipStudyArea` and `RangeTreeNode`.

## Vue
The central component here is `StudipTree`. A tree can be displayed in various ways, for which there are further Vue components:
- `StudipTreeTable` displays the levels of the tree as a table, analogous to the file area
- `StudipTreeList` displays the levels of the tree as a list of tiles, analogous to the old event search
- `StudipTreeNode` shows the tree as an expandable hierarchy

`StudipTree` can be configured in many ways to control the output:
- `viewType` defines the type of view, either 'table', 'list' or 'tree'
- `startId` ID of the start node for display (the IDs are of the form 'ClassName_ID')
- `title` Title to be displayed for the tree
- `openNodes` List of already open nodes (only useful for display as a tree)
- `openLevels` General number of open levels (only useful for display as a tree)
- `withChildren` Show sublevels?
- `withCourses` Display assigned courses?
- `semester` Preset semester in the sidebar filter
- `semClass` Preset category in the sidebar filter
- `breadcrumbIcon` Icon for the breadcrumb navigation
- `itemIcon` Icon for the sublevels in the table display (currently hardwired to "Folder")
- `withSearch` Display an event search?
- `withExport` Show an export link for existing events/search results?
- `editUrl` URL to the edit form of an existing node
- `createUrl` URL to the creation dialog of a new node
- `deleteUrl` URL for deleting an existing node
- `showStructureAsNavigation` Show a tree structure as a table of contents in addition to the regular display? (Only useful for table or list display)
- `assignable` Are the nodes assignable?

## JSON-API
New routes for constructing the tree structure have been provided:
- `/tree-node/{id}` Get the node with the specified ID
- `/tree-node/{id}/children` Get the child nodes of the specified ID
- `/tree-node/{id}/courseinfo` Get information about the number of assigned events
- `/tree-node/{id}/courses` Get the assigned courses
- `/tree-node/course/pathinfo/{classname}/{id}` Get the paths in the tree to which the specified course is assigned
- `/tree-node/course/details/{id}` Get information about the specified course that is assigned to a tree node (lecturers, semester, dates)

## Usage
A tree display is automatically generated for each element with the attribute `data-studip-tree` if the Vue component `StudipTree` is available there.
