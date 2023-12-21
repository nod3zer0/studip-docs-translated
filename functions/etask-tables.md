---
id: etask-tables
title: Description of the `etask_*`-tables
sidebar_label: etask_* - tables
---

## etask_tasks - Tasks
As of Stud.IP 4.0 there are common tables for tasks in Stud.IP, which can be used by various tools (and also plugins). Since many tools want to store specific additional information, there is an additional field `options` in many tables, which can hold (almost) any additional data in JSON format. Of course, separate tables can still be created for additional requirements.

The `task_tasks` table contains the individual tasks. A task can be used in different contexts, which is why, for example, the information on the evaluation (if there is such a thing) is not directly associated with the task. The type should actually be a PHP class name, but there are no defined classes for the task types in the core yet, so there is currently only the type "`multiple-choice`".

| attribute | description |
| ---- | ---- |
| **id** | ID of the task (primary key, auto-increment) |
| **type** | Task type (multiple choice, text, assignment, etc.), planned: PHP class name |
| **title** | task title (without formatting) |
| **description** | task text (with formatting) |
| **task** | task content in a type-specific JSON representation (see examples below) |
| **user_id** | task creator |
| **mkdate** | creation date |
**chdate** | modification date |
**options** | further, non-type-specific data in JSON format (as required) |


## etask_task_tags - keywords for tasks

If a tool wants to assign keywords to tasks, the `etask_task_tags` table can be used for this purpose. Each user only sees their own keywords.

| attribute | description |
| ---- | ---- |
| **task_id** | ID of the task |
| **user_id** | User of the keyword |
| **tag** | keyword|


## etask_tests - Task collections

Tasks can be grouped into task collections (like files in folders). Each task collection is stored in the `etask_test` table.

| attribute | description |
| ---- | ---- |
| **id** | ID of the task collection (primary key, auto-increment) |
| **title** | Title of the task collection (without formatting) |
| **description** | Description of the task collection (with formatting) |
| **user_id** | Creator of the task collection |
| **mkdate** | creation date |
| **chdate** | modification date |
**options** | further data in JSON format (as required) |

## etask_test_tags - keywords for task collections

If a tool wants to assign keywords to task collections, the `etask_test_tags` table can be used for this purpose. Each user only sees their own keywords.

| attribute | description |
| ---- | ---- |
| **test_id** | ID of the task collection |
| **user_id** | User of the keyword |
| **tag** | keyword |


## etask_test_tasks - Assignment of the tasks to the task collections

The `etask_test_tasks` table stores the assignment of the individual tasks to the task collections as well as any other information related to the assignment, such as the score.

| attribute | description |
| ---- | ---- |
| **test_id** | ID of the task collection |
| **task_id** | ID of the task |
| **position** | Position in the collection (numbered from 1) |
| **points** | achievable points (optional) |
| **options** | further data in JSON format (as required) |


## etask_assignments - set task collections (task sheets)

A task collection is initially just a set of tasks without an assignment. The task collection is presented to a set of participants via an `etask_assignments` (e.g. in the form of a task sheet or a vote). In the case of a single assignment to a context, the assignment is directly in this table, in the case of multiple assignments in the `etask_assignment_ranges` table.

| Attribute | Description |
| ---- | ---- |
| **id** | ID of the task sheet (primary key, auto-increment) |
| **test_id** | ID of the task collection |
| **range_type** | Context type, currently defined are: `course`, `global`, `group`, `institute`, `user` |
| **range_id** | Context of the assignment, e.g. the ID of an event |
| **type** | Presentation mode as text, depending on the tool (not predefined) |
| **start** | Start of the editing period (optional) |
| **end**| End of the editing period (optional) |
| **active**| visible/invisible for participants, can be used for a draft mode, for example |
| **options**| further data in JSON format (as required) |

## etask_assignment_ranges -multiple assignment of task collections to contexts

If task collections are to be assigned to multiple contexts, these are stored in `etask_assignment_ranges`.

| attribute | description |
| ---- | ---- |
| **id** | ID of the assignment (primary key, auto-increment) |
| **assignment_id** | ID of the assignment sheet |
| **range_type** | Context type, currently defined are: `course`, `global`, `group`, `institute`, `user` |
| **range_id** | Context of the assignment, e.g. the ID of an event |
| **options** | further data in JSON format (as required) |

## etask_assignment_attempts - individual solution attempt for an assignment sheet

In `etask_assignment_attempts`, it is stored for each participant whether a task sheet has already been started (and when) and, if applicable, an individual end date for processing. Additional information can also be saved depending on the tool.

| Attribute | Description |
| ---- | ---- |
| **id** | ID of the attempted solution (primary key, auto-increment) |
| **assignment_id** | ID of the assignment sheet |
| **user_id** | participant |
| **start** | individual start of processing (optional) |
| **end** | individual end of processing (optional) |
| **options** | further data in JSON format (as required) |

## etask_responses - Answers or solutions to individual tasks

The responses to the individual tasks are stored in the `etask_responses` table. The responses themselves are of course type-specific and are stored in a JSON format like the task content.

| attribute | description |
| ---- | ---- |
| **id** | ID of the response (primary key, auto-increment) |
| **assignment_id** | ID of the task sheet |
| **task_id** | ID of the task |
| **user_id** | participant |
| **response** | Response in JSON format (depending on task type) |
| **state** | Status (numerical, to be defined by the tool) |
| **points** | Rating in points (optional) |
| **feedback** | Feedback on the answer (with formatting) |
| **grader_id** | User ID of the feedback provider |
| **mkdate** | creation date |
| **chdate** | modification date |
| **options** | further data in JSON format (as required) |


## JSON formats for task types

This section defines the JSON format of the most important task types. Please note that the task text is not part of this JSON description, but is stored directly in the `description` column in the `task_tasks` table. For other task types, the listing here would have to be supplemented accordingly.

#### Multiple choice

There is a common schema for all types of tasks with a choice of answers (with the exception of cloze text).

Example:
```json
{
   "select": "multiple",
   "optional":false,
   "answers":[
      {
         "text": "Is the sky blue?",
         "score":1,
         "feedback":"..."
      },
      {
         "text": "Can you find a pot of gold at the end of the rainbow?",
         "score":0,
         "feedback":"..."
      }
   ]
}
```

| attribute | description |
| ---- | ---- |
| **select** | `single` or `multiple` |
| **optional** | `true` (answer is optional) or `false` (answer must be given) |
| **answers** | List of possible answers (including automatic feedback if applicable) |

Response:
```json
[
    1, 0
]
```

If a question remains unanswered (optional answer), a value of `-1` is entered.

#### Text questions

For text tasks there is a scheme similar to multiple choice with small extensions. However, the predefined answers here are not answer options for selection, but the automatically evaluable answers with corresponding evaluation (the list can be empty if there is no evaluation).

Example:
```json
{
   "layout": "textarea",
   "template":"...",
   "compare": "ignorecase",
   "answers":[
      {
         "text": "Paris",
         "score":1,
         "feedback":"..."
      }
   ]
}
```

| attribute | description |
| ---- | ---- |
| **layout** | `input` (single-line) or `textarea` (multi-line) |
| **template** | initial text template for the participants' solution |
| **compare** | comparison criterion for the evaluation, e.g. `ignorecase` or `levenshtein` |
| **answers** | List of automatically evaluated answers (including automatic feedback, if applicable) |


Response:
```json
[
"foobar"
]
```

#### Cloze texts

In cloze text, the answers are stored separately from the actual cloze text, in which only the gaps are marked. A gap is marked in the text with `[=[]()=]`. The cloze text can also be formatted (Stud.IP formatting or WYSIWYG editor).

Example:
```json
{
   "text": "The vase is []() the []().",
   "select":true,
   "compare": "ignorecase",
   "answers":[
      [
         {
            "text": "on",
            "score":1
         },
         {
            "text": "next to",
            "score":0,
            "feedback":"..."
         },
         {
            }, { "text": "below",
            "score":0,
            "feedback":"..."
         }
      ],
      [
         {
            "text": "chair",
            "score":0
         },
         {
            "text": "Table",
            "score":1
         },
         {
            "text": "Carpet",
            "score":0
         }
      ]
   ]
}
```


| attribute | description |
| ---- | ---- |
| **text** | Text of the gap text (with formatting), gaps are marked with `[=[]()=]` |
| **select** | `true` (selection from list) or `false` (input as text) |
| **compare** | comparison criterion for the evaluation, e.g. `ignorecase` or `levenshtein` |
| **answers** | List of answer options or automatically evaluated answers (including automatic feedback, if applicable) |


Answer;
```json
[
"on", "table"
]
```

#### Assignments

Assignments consist of a list of groups (categories) and a list of answers that can be assigned to these groups. There may be answers that must remain unassigned - these are assigned the group with the index "`-1`".

Example:
```json
{
    "select": "single",
    "groups":[
        "instrument",
        "tool"
    ],
    "answers":[
        {
            "id":42,
            "text": "Hammer",
            "group":1
        },
        {
            "id":7,
            "text": "Violin",
            "group":0
        }
    ]
}
```


| attribute | description |
| ---- | ---- |
| **select** | (single assignment) or `multiple` (multiple assignment in each group) |
| **groups** | List of groups for the assignment (numbered from 0) |
| **answers** | List of answer options (possibly including automatic feedback) and group assignment |

Response:
```json
{
"42": 0, "7": 1
}
```

If a response remains unassigned, a value of "`-1`" is entered.
