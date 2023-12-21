---
id: db-classes
title: File area classes
sidebar_label: File area classes
---

# Range of functions

There are many things to consider with the new file area from version 4.0.
The file area is mainly, as before, a simple storage of files in Stud.IP.
But there are now also many additional features such as homework folders (time-controlled or not), topic folders, connection to cloud services such as OwnCloud, linking of files instead of copies, mass replacement of multiple files. To enable these features, the class structure is slightly more complicated than before.

# Data structure

There are several entities we are talking about:

| Class | Description |
| ---- | ---- |
| File | A file that is located in the Stud.IP file system, such as a PDF document or an image. |
| FileRef | A representation of a file. What a user sees in a folder in Stud.IP is first and foremost always a FileRef object. Usually there is also a file object behind the FileRef object in which the file is actually located. If you see the image at the end, you can see the file object. But you have called it up by "clicking" on a FileRef object. A file object can also be assigned to several FileRef objects if it is linked several times in the system. This saves memory space and makes it easier to change several files at once. |
| Folder | The folder in the database. The folders table is used to store the folders in the Stud.IP database. |
| Interface FolderType | This is the logic engine behind the folder. The FolderType manages the folder and defines who can see and edit it, but also which files the folder has or is currently displaying. Normally, when we think of the term "folder", we always think of the standard folder (StandardFolder), where we see all the files it has and where everyone can upload something. But homework folders (HomeworkFolder) are special folders where everyone can upload something, but where only the teacher can see what has been uploaded. This type of logic is always managed by FolderType. FolderType itself is an interface and not a class. Only the HomeworkFolder is a real class. The folder_type of each folder is stored in the folders table. However, there are also FolderTypes that do not have a matching entry in the folders table, such as virtual folders in the OwnCloud. These folders are not stored in the Stud.IP database, but are only read out by a plugin if necessary and returned as special FolderTypes. This is sufficient because the Stud.IP code delegates all tasks to the FolderType and never asks the folder object directly. |


As a developer, you can remember this: When it comes to using classes and objects, always use FileRef and FolderType classes and objects respectively.
File and Folder should not be used if possible, even if this would be possible in some situations.
In other situations, such as with OwnCloud plugins, this leads to errors.

**Only those who consistently address FolderType and FileRef can avoid these problems.
