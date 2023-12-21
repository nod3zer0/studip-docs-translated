---
id: csrf-protection
title: CSRFProtection - Protection against cross-site request forgeries
sidebar_label: CSRFProtection
---

CSRF (or XSRF) stands for "Cross-Site Request Forgery", i.e. cross-site request manipulation. This attack method works by injecting malicious code or a link into the user's browser, which then executes unauthorized commands with the user's rights (such as sending compromising messages).

Simple examples can be found, for example, at [Wikipedia](http://de.wikipedia.org/wiki/CSRF#Beispiele).

Two years ago, data-quest made a corresponding attempt, which ultimately ended in the installation of the image proxy, among other things. Since then, dangerous actions can be secured with Stud.IP tickets. Currently #check_ticket is used in 14 files (e.g. the study groups, the administration of plugins and their roles, the guestbook).

Apparently, however, there are still some places that would also need to be secured. The solution currently in use would have to be used consistently in every corresponding place. There are also problems with the use of multiple tabs, as the current solution only recognizes one valid ticket.

This proposed StEP aims to prevent CSRF/XSRF by checking every "dangerous" web request. Following Tim Berners-Lee [Axioms](http://www.w3.org/DesignIssues/Axioms.html), all requests are differentiated into page effect-free and page effect-triggering. Calling up the guestbook should be free of side effects. Sending an entry to the guestbook triggers page effects (naively: database changes).

According to Berners-Lee, page effect-free requests should be sent via GET and the others via POST.

This StEP adds a further value to each POST request by providing each form with a hidden parameter. When a POST request arrives, the server checks whether the hidden parameter is included and valid. If this is not the case, the request is rejected.

This hidden parameter is always the same during the session. This means that there are no problems with persistence and invalidation, as is the case in the current solution. The use of multiple tabs is therefore completely unproblematic.

In addition, every request from future code is automatically secured as long as the developers adhere to the semantics of HTTP verbs (GET/POST), which should be taken for granted with forms.

#### Functionality

In order to protect Stud.IP from forged requests, every POST request (but not Ajax) must now send an additional parameter "security_token", the value of which is compared with one in the session. To be precise, a 256-bit token is generated for each user at the start of their session and stored in the $_SESSION. Each(!) Stud.IP POST form has been enriched with an [=input```phptype=hidden]-element=] that sends this token. As soon as a request arrives at Stud.IP, it is checked:

* whether it is a GET request, in order to then cancel the further check
* whether it is an XHR (i.e. Ajax with jQuery or prototype), in order to then cancel the further check
* whether the parameter "security_token" sent along exists and matches the token from the session

This check takes place automatically at the end of #page_open, on the assumption that the necessary session then exists.

If the check is negative, an error (status 403) is reported and further processing is aborted.


#### Application

First a link to the API documentation [http://hilfe.studip.de/api/class_c_s_r_f_protection.html](http://hilfe.studip.de/api/class_c_s_r_f_protection.html)

Future developments must take into account that form elements whose "method" attribute has the value POST require an additional, hidden input element whose name is "security_token" and whose value corresponds to the token from the session. The easiest way to do this is as follows:

```php
<form method="POST" ... >
<?= CSRFProtection::tokenTag() ?>
...
</form>
```

**Very important:** This method must '+NOT+' be called if it is a GET form, as the token is then transferred to the URL and thus to third-party pages via the referrer header. In this case, the protection becomes ineffective.

#### Difficulties

The hidden parameter must never be included in a URL, as this would render all efforts ineffective.
If the token falls into the hands of an attacker, they can make any number of requests.

The existing tickets that are sent via GET requests must be remodeled. The conversion has not yet taken place there

If, according to the Stud.IP code, a request with a page effect should actually be sent via POST, but an attacker simply places a GET link, the request does not require a security token, as only POST requests are automatically checked. This has the following consequences:

Forms **must** still be handled as described above.
The evaluation must be carried out manually for each routine that triggers page effects. This means that all places in the code must be identified and supplemented with the evaluation.
In preparation, the CSRFProtection class was supplemented by the [#verifyUnsafeRequest](http://hilfe.studip.de/api/class_c_s_r_f_protection.html#a5b6301200e525d59e4cc63e5ea36d6d3) method. If you have found a place in the code that actually causes a page effect via POST (more precisely: all except GET or HEAD), the following code must be inserted there:

```php
  CSRFProtection::verifyUnsafeRequest();
```


  The call verifies that:

* %alpha% it is an unsafe request (according to RFC 2616)
* %alpha% this request carries the security token
* %alpha% the token matches the one in the session


If this is not the case, there is a MethodNotAllowed exception if the request is not insecure,
or an InvalidSecurityTokenException if the token does not match.
