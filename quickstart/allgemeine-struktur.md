---
Titel: Allgemeine Struktur
---



Jeder, der schon einmal mit einer Programmiersprache angefangen hat, weiß, womit man beginnt: mit einem Hallo-Welt-Programm. Ja, man sieht sie vielleicht nicht mehr, aber sie sind nützlich, um die grobe Syntax und die minimalen Konventionen zu verstehen.



Schauen wir uns in Studip eine völlig leere Seite an, die nur ein Zeichen enthält. Um zu sehen, wie viel einheitliches Design und Sitzungsroutinen in Anspruch nehmen, sollten Sie sich einfach vor Augen führen, wie viele Programmzeilen eine solche "nackte" Studip-Seite klein ist.



Die folgende Datei könnte problemlos im öffentlichen Ordner von Studip liegen:





















```php
<?php
/*
test.php - Anzeige einer leeren Gerüstseite von Stud.IP
Copyright (C) 2009 Rasmus Fuhse <ras@fuhse.org>



Dieses Programm ist freie Software; Sie können es weitergeben und/oder
Sie können es unter den Bedingungen der GNU General Public License
wie von der Free Software Foundation veröffentlicht, weitergeben und/oder modifizieren; entweder in Version 2
der Lizenz oder (nach Ihrer Wahl) jeder späteren Version.



Dieses Programm wird in der Hoffnung weitergegeben, dass es nützlich sein wird,
aber OHNE JEGLICHE GEWÄHRLEISTUNG; auch ohne die stillschweigende Garantie der
MARKTGÄNGIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.  Siehe die
GNU General Public License für weitere Einzelheiten.



Sie sollten ein Exemplar der GNU General Public License
zusammen mit diesem Programm erhalten haben; falls nicht, schreiben Sie an die Free Software
Foundation, Inc, 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.



$Id: test.php 12381 2009-06-03 16:57:46Z Krassmus $
*/



//Ab hier fangen wir an, über den Code nachzudenken.



//// Initialisierungen: Include-Pfad etc.
require '../lib/bootstrap.php';



//// Hier wird eine Sitzung gestartet.
page_open(array('sess' => 'Seminar_Session',
  auth' => 'Seminar_Default_Auth',
  'perm' => 'Seminar_Perm',
  'user' => 'Seminar_User'));
$auth->login_if($_REQUEST['again'] && ($auth->auth["uid"] == "nobody"));
$perm->check("user");



include ('lib/seminar_open.php'); // Stud.IP-Sitzung initialisieren






//// Variablen, die bei der Anzeige helfen.
$HELP_KEYWORD="Basis.Testseite"; // Wenn Sie auf Hilfe klicken, werden Sie zur Basis.Testseite weitergeleitet.
$CURRENT_PAGE = _("Testseite"); // Zeigt den Namen dieser Seite






//// Ab hier wird der erste Text in das HTML-Dokument geschrieben



//HTML-Header bis zu den <body>-Anweisungen
include 'lib/include/html_head.inc.php';



//Studip-Header, d.h. die Navigationssymbole, die über fast jeder Seite erscheinen.
include 'lib/include/header.php';






//Hier kommt die eigentliche Nachricht
$output_format = '<table class="blank" width="100%%"
border="0" cellpadding="0" cellspacing="0">
<tr><td class="topic"><b>&nbsp;%s </b>%s</td></tr>
<tr><td class="steel1">&nbsp;</td></tr><tr><td class="steel1"><blockquote>%s</blockquote></td></tr>
<tr><td class="steel1">&nbsp;</td></tr>
</table><br>'."\n";



printf ($output_format, htmlReady( _("und jetzt") ), *, formatReady( _("Hello World!") ));






// Daten zurück in die Datenbank speichern.
page_close();



?>
```






# Wichtige Elemente der Testseite: Bootstrap
[#bootstrap](#bootstrap)



Die erste Anweisung eines Skriptes in `public` ist immer:



```php
// Initialisierungen: Include-Pfad etc.
require '../lib/bootstrap.php';
```



Dies setzt den $STUDIP_BASE_PATH, passt den Include-Pfad an und lädt alle wichtigen Konfigurations- und Systemklassendateien.



# Wichtige Elemente der Testseite: Sessions
[#page_open](#page_open)



Studip macht aus dem einen oder anderen Grund einiges anders als andere PHP-Module. Das beginnt zum Beispiel mit der Session. PHP hat eine eingebaute Sitzungsverwaltung, die es theoretisch erlaubt, global auf dem Server Variablen darüber zu speichern, was der Benutzer gerade tut, welche Daten er eingegeben hat und so weiter. Leider ist dies aber erst ab PHP4 möglich. Da Studip unter PHP3 erstellt wurde, wird auch heute noch ein Session-Management-System verwendet, das auf der PHPLIB-Erweiterung basiert und für moderne PHP-Entwickler etwas altbacken wirkt. Im Grunde ist es aber dasselbe wie eine normale PHP-Session und auch einfach zu bedienen. Auf der Testseite wird diese Session repräsentiert durch



```php
page_open(array('sess' => 'Seminar_Session',
  auth' => 'Seminar_Default_Auth',
  'perm' => 'Seminar_Perm',
  'user' => 'Seminar_User'));
```



und geschlossen durch



`Seite_schließen();`



wieder geschlossen. Die PHPLIB-Sitzung muss beendet werden, damit in der nächsten Sitzung (auf der nächsten Stud.IP-Seite) wirklich alle Variablen wieder zur Verfügung stehen.



# Sicherheitsüberprüfung



Unmittelbar nach dem page_open(...) folgt die Sicherheitsüberprüfung, ob der Benutzer die Seite überhaupt sehen darf. Mit



`$perm->check("user");`



wird z.B. geprüft, ob der Betrachter der Seite die Rechte eines "Benutzers" hat. Bei einer Seite, die nur mit Admin-Rechten betrachtet werden soll, wäre dies



`$perm->check("admin");`



angezeigt werden.
Es gibt fünf Sicherheitsstufen: Gast (d.h. ohne besondere Rechte, der nur öffentliche Veranstaltungen sehen darf, für die die Sicherheitsabfrage im Code fehlt), "user", "tutor", "lecturer" und "admin". Unmittelbar nach der Sicherheitsabfrage wird die Include-Datei



`include ('lib/seminar_open.php');`



die Session mit allen möglichen Variablen gefüllt, die auf den meisten Seiten relevant sind, aber noch nicht auf unserer kleinen Testseite.



# Aufbau der Kopfzeilen



Studip bemüht sich um ein einheitliches, solides Design. Das bedeutet, dass alle Seiten (mit Ausnahme des Messengers, zum Beispiel) die gleiche Kopfzeile und die gleichen Stilanweisungen haben. Dies geschieht in den Zeilen:



`include 'lib/include/html_head.inc.php';`



für die grundlegende HTML-Struktur von `<html>` bis `<body>` einschließlich aller CSS-Dateien und so weiter und



`include 'lib/include/header.php';`



die den eigentlich sichtbaren Header mit den Icons für die Startseite, News, Homepage, das Studip-Logo usw. darstellt. Der Header enthält auch den Namen der Seite und einen Link zur Hilfeseite, die auch Informationen darüber enthält, was genau eine Hilfeseite darstellen soll. Beide Informationen werden in der header.php über zwei Variablen gesetzt. Aus diesem Grund sollte der Code diese Informationen bereits VORHER enthalten:



```php
$HELP_KEYWORD="Basis.testpage";
$CURRENT_PAGE = _("Testseite");
```



# Textbausteine in Studip



Was wir [später in dieser Hilfe] noch genauer erklären werden (Schnellstart/Internationalisierung), sind die Textbausteine, wie der eben erschienene



```php
_("Testseite")
```



Ein Studip-Neuling wird sich unweigerlich fragen, was diese Unterstrich-Funktion sein soll. Normaler Text würde hier sicherlich ausreichen. Das Problem ist gewissermaßen die Möglichkeit, jede Studip-Seite in Englisch oder theoretisch auch in jeder anderen Sprache anzeigen zu lassen. Die entsprechende Übersetzungsarbeit wird nicht im Code geleistet (was den Code noch unübersichtlicher machen würde, als er ohnehin schon ist), sondern in der Deklaration der Funktion Unterstrich "_" oder gettext(). Daher müssen Entwickler sicherstellen, dass jedes Stück Text, das keine HTML-Anweisung ist, durch die Funktion _("...") geleitet wird.



Das obige Beispiel ist ein gutes Beispiel dafür, was genau von gettext gesetzt werden muss und was nicht. Die Variable `$CURRENT_PAGE` wird als tatsächlich sichtbarer Text in die Kopfzeile geschrieben und die Variable `$HELP_KEYWORD` wird nur als Link-Parameter verwendet, der nicht sichtbar ist, sondern nur in der Adressleiste des Browsers an die Hilfeseite übergeben wird.
