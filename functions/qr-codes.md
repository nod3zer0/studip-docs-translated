---
title: Create QRCodes
---

There is a nice little feature in the questionnaires: You can click on a QR code icon and a QR code of the link to the questionnaire will be displayed in full screen mode.

Plugins or other places in Stud.IP can also generate a QR code relatively easily in this way. If you have an `<a>` link, you only need to add the tag `data-qr-code` to it.

```html
<a href="http://localhost/studip_trunk/dispatch.php/questionnaire/answer/c9b030df1bd556c8383dc56259d0f9c3?cid=c0fd14b93d003f35bed648d2056346aa"
   data-cr-code="Please participate quickly.">
    Link to the survey
</a>
```

The text in the data-qr-code tag is optional and provides a description that is displayed below the generated QR code.
