---
title: Report error
---

The [Gitlab](http://gitlab.studip.de) on the development server is used for reporting and managing bugs.
All known and corrected errors are documented there, and the planning for future Stud.IP releases is also organized there.

## How do I report a bug?

There are various ways to report errors in Stud.IP:

* Directly via the form in [Gitlab](https://gitlab.studip.de/studip/studip/-/issues/new) (registration in the developer and user forum required)
* Report in the developer board in the forum [Event Bugboard (BIESTs)](https://develop.studip.de/studip/dispatch.php/course/forum/index?cid=28e888802838a57bc1fbac4e39f0b13a) in the [Developer and User Forum](https://develop.studip.de/studip/) (registration required)
* by e-mail to [studip-users@lists.sourceforge.net](mailto:studip-users@lists.sourceforge.net)
* Or let us know in the [Developer Chat](https://develop.studip.de/studip/assets/images/icons/blue/chat.svg).

**In any case, please always state:**

* In which Stud.IP version or at which location does the error occur? (e.g.: Version 5.4, University of Göttingen)
* Which browser and version are you using? (e.g.: Internet Explorer 7)
* What is your role in the system? (e.g. "Lecturer")
* Describe as precisely as possible what needs to be done under which circumstances to reproduce the error.

### Error report via the gitlab

The "normal" way to report an error in Stud.IP is to use the corresponding form in gitlab: https://gitlab.studip.de/studip/studip/-/issues/new

Please note that it is helpful for the developers if you provide as precise information as possible about the error.

- What exactly do you have to do to reproduce the error?
- Does the error occur in every browser or only in some (Chrome, Firefox, Safari, Edge)
- In which version of Stud.IP does the error occur (your Stud.IP version is usually listed in the imprint)?
- Ideally, please upload a screenshot where the address bar of the browser is also visible.


### Error report via e-mail

Alternatively, errors can also be reported by e-mail to [studip-users@lists.sourceforge.net](mailto:studip-users@lists.sourceforge.net). However, these do not automatically end up in our Stud.IP ticket system, so it can occasionally happen that they are unprocessed for a long time or even completely forgotten.

Therefore, if possible, errors should not be reported via e-mail, but via the channels mentioned under 1.1 or 1.2.

## How do I report a suggestion for improvement?

The first point of contact for suggestions for enhancements or improvements should be the forum of the event [Developer-Board](https://develop.studip.de/studip/forum.php?cid=a70c45ca747f0ab2ea4acbb17398d370&view=tree) in the [Developer and User Forum](https://develop.studip.de/studip/). There you can discuss with the Stud.IP developers whether and in which forum your ideas could be implemented. Suggestions for improvement should (apart from exceptional cases) not be entered into gitlab without prior discussion.

### Types of tickets

There are the following ticket types:

| Type | Description |
| ---- | ---- |
| BIEST | a bug in the official release |
| Lifters | a long-term revision, must first be approved by the core group |
| StEP | an improvement proposal, must be voted on by the core group beforehand |
| TIC | a "small" improvement proposal |


### Milestone
// TODO: needs to be revised

A milestone in gitlab corresponds to an official release of Stud.IP (such as 5.3 or 5.4) and is represented as a label.
The milestone information in the ticket is used to manage which tickets for which Stud.IP release have been successfully closed or still need to be completed. Only tickets relating to the official release have a milestone, and the milestone should only be processed by the person to whom the ticket is assigned (or one of the release managers). The following rules apply:

| Type | Description |
| ---- | ---- |
| An open BIEST does not have a milestone. If the BIEST is closed, the milestone indicates the first version of Stud.IP that contains this correction, which is usually the current release branch. |
**StEP**, **TIC** | The milestone is the version for which the StEP or TIC is to be installed. |
| **Lifters**| A lifter has no milestone. |

**Important**: The milestone does *not* indicate in which version the error occurred. This should be part of the description text.


### Additional fields for quality assurance

For tickets of the StEP and TIC types, there are additional fields that are used for quality assurance by the core group and are linked to the corresponding responsibilities with veto power. The following fields are currently used:

| Type| Description |
| ---- | --- |
| **Code quality?** | Code review desired |
| **Code-Quality+** | Code-Review positive |
| **Code-Quality-** | Code-Review negative, i.e. veto of the responsible person |
| **Security?** | Security review desired |
| **Security+** | Security review positive |
| **Security-** | Security review negative, i.e. veto by the person responsible |
| **Code conventions?** | Review of formal code conventions desired |
| **Code conventions+** | Review of formal code conventions positive |
| **Code conventions-** | Review of formal code conventions negative, i.e. veto by responsible person |
| **Developer documentation?** | Developer documentation review desired |
| **Developer documentation+** | Developer documentation review positive |
| **Developer documentation-** | Developer documentation review negative, i.e. veto by responsible person |
| **user documentation?** | user documentation review desired |
| **user documentation+** | user documentation review positive |
| **User documentation-** | User documentation review negative, i.e. veto by responsible person |
| **Functionality?** | Functionality test from user perspective review desired |
| **Functionality+** | Functionality test from user perspective review positive |
| **Functionality-** | Functionality test from user's perspective review negative, i.e. veto by responsible person |
| **GUI guidelines?** | Review of the user interface desired |
| **GUI guidelines+** | Review regarding the user interface positive |
| **GUI-Guidelines-** | Review regarding the user interface negative, i.e. veto of the person responsible |


## Keywords

Markings on a ticket that go beyond the standard attributes are mapped using keywords.
