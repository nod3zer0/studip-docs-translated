---
title: Dialogs
sidebar_label: Dialogs
---


Dialogs are used to obtain input or confirmations from the user.
Since the introduction of the sidebar, dialogs are also generally regarded as a representation of actions.
Actions remain on the respective page and avoid a context change

Nevertheless, dialogs should not be used excessively. If possible, interaction should take place directly on the page,
on which the information or elements to be processed are also displayed.


## General
Dialogs should be kept simple and not too complex, i.e. only one associated action should be executed per dialog.

Dialogs should be self-explanatory and contain as little (explanatory) text as possible.

Dialogs should not be stacked, i.e. a dialog should not open in a dialog (except e.g. date picker, where no button needs to be pressed). For more complex dialogs, wizards should be used in which several dialogs are connected in series or areas within the dialogs are expanded and collapsed.

## Modal and non-modal dialog windows
With modal dialog windows, the user cannot continue working in other windows of the application, but only in the dialog window. In contrast, non-modal dialog windows also allow the user to interact with the background window. Non-modal dialog windows are used in Stud.IP, for example, in the timetable for entering appointments. However, the majority of dialog boxes in Stud.IP are modal.

When a modal dialog window is called up, the background window is marked as inactive by suitable optical manipulation ("greying out" or darkening with an overlay in dark blue).

Dialogs must be distinguished from notifications, which are never modal and do not appear as a separate window.

## Pop-up window

Pop-up windows, i.e. windows that open separately, must not be used.

## Properties
Dialog windows are usually [form-like](Visual-Style-Guide#Forms).

Dialogs should be readable without requiring scrolling in the dialog. Vertical scrolling is an exception: If it cannot be avoided (for example, because content, long lists or drop-down elements do not fit the dialog box), scrolling is allowed within the dialog box.

The page size within a dialog box should not change during editing. Exceptions to this restriction are, for example, the dynamic reloading of elements in a drop-down list or the dynamic display of completion instructions for mandatory fields.

## Behavior
If you click next to a dialog window, it should not close.

If the user presses the Escape button, the dialog window closes, unless entries have already been made. The auto-formsaver must be activated here so that the user is notified of any lost content.


### Schematic structure of a dialog window
![image](../assets/ce19cb16e52e35fa80ad2fd66ee7fbac/image.png)

#### Layout/Design

Generally, dialogs are designed in such a way that they start with a dark blue header (Stud.
IP-Brand-Color, see [design](Design)) and have a white background. They have a
thin white border, a slight shadow and darken the page behind it. Buttons have a separate footer, similar to tables or forms.

Example for the design:

![image](../assets/c37f69398215d78b12784d3428c89a9c/Bildschirmfoto_2021-11-15_um_15.35.11.png)

#### Text in the title bar
The title text should be meaningful and specific so that users know exactly what they are supposed to do. Duplication with the content must be avoided.

#### Buttons
Each dialog has an X icon on the right in the title bar to close the dialog. There is also a separate "Cancel"/"Close" button, as many users overlook the x icon in the title bar.

A tick icon is displayed on the accept button.

The text on the accept button should be a specific verb such as "Delete" or "Create" and not just "OK".

### Security queries
Security prompts are used especially when deleting important elements or for other critical and irrevocable actions.

Security prompts are a simplified form of the modal dialog.

They contain a message or question text and two buttons for confirming and rejecting.
