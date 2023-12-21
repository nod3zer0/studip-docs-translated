---
id: pdf-exports
title: PDF-Exports
sidebar_title: PDF-Exports
---

In `lib/classes/exportdocuments/ExportPDF.class.php` there is a class with which it is easy to export to a PDF from text commonly used in Stud.IP, which may also have Stud.IP formatting. In plain language this means: you have a text such as the content of a wiki and want to get a PDF from it in which the tables and graphics are displayed correctly. The ExportPDF class inherits its beautiful simplicity from the TCPDF class, which has a few other nifty features. This means that $doc can also use all the methods of TCPDF.

By directly outputting the PDF document, any other output written before or after is of course ignored. As soon as dispatch is called, only the PDF is transmitted (including the MIME type). The parameter of disptach is also the file name without the ".pdf", which is automatically appended.

## Generation

### From PHP

The code for generating a PDF document from Stud.IP code consists of the following four lines:

```php
<?php
$doc = new ExportPDF();
$doc->addPage();
$doc->addContent('Hello, %%we%% use :studip: formatting.');
$doc->dispatch("test_pdf");
```

First you have to initialize a document object. In our case this is `$doc`. Then you start a new page with `$doc->addPage()` and fill this page with content using the `addContent` method, which must contain Stud.IP formatting information. In the last line, this document is output to the caller of the page.

### Use HTML template

If you have a template that generates HTML code, this HTML code can be transferred directly to ExportPDF in order to create an HTML document from it. The code for rendering the HTML code and converting it into a PDF document can look like this:

```php
<?php
$templateFactory = new Flexi_TemplateFactory(__DIR__ . '/../templates/');
$template = $templateFactory->open('pdf_doc.php');
$template->set_attribute('attr1', $attr1);
$template->set_attribute('plugin', $this->plugin);

$htmlCode = $template->render(); //$htmlCode contains the rendered HTML code

//Conversion to a PDF document:

$pdfdoc = new ExportPDF();
$pdfdoc->addPage();
$pdfdoc->writeHTML($htmlCode);

//Send PDF:

$pdfdoc->dispatch('pdf_doc');
```

Instead of the `addContent` method, `writeHTML` is called. Previously rendered HTML code, which was generated with the help of a template, is passed to this method.


### Save to file area

The PDF created via ExportPDF can be saved directly in the file area of Stud.IP. The code for this is almost identical to the above cases:

```php
<?php
$doc = new ExportPDF();
$doc->addPage();
$doc->addContent('Hello, %%we%% use :studip: formatting.');
$studip_document = $doc->save("test_pdf", $folder_id);
```

Only the last line has been replaced. The method `save` saves the document and returns an object of the type StudipDocument. You can use this object with `$studip_document->getId()` to get the md5 ID of the document. If you also specify a folder_id when calling `save`, the file is also inserted directly into a file area and you don't have to worry about anything else.


## Further topics

* Documentation of TCPDF: [http://www.tcpdf.org/docs.php](http://www.tcpdf.org/docs.php).
