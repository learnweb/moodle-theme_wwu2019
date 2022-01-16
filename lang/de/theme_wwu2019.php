<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * WWU 2019
 *
 * @package    theme_wwu2019
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'WWU 2019';
$string['choosereadme'] = 'Dieses Theme basiert auf dem Classic-Theme von Moodle und ist hinsichtlich des Corporate Design der WWU Münster angepasst.';

/* Main menu */
$string['mycourses'] = 'Meine Kurse';
$string['termindependent'] = 'Semesterunabhängig';
$string['dashboard'] = 'Dashboard';

$string['thiscourse'] = 'Dieser Kurs';
$string['searchadmin'] = 'Administrationssuche';

$string['openmainmenu'] = 'Hauptmenü öffnen';
$string['mainmenu'] = 'Hauptmenü';

/* User menu */
$string['loggedinas'] = '{$a->real}, als {$a->fake}';
$string['badgepreferences'] = 'Badge';
$string['mygrades'] = 'Meine Noten';

$string['openusermenu'] = 'Nutzermenü öffnen';
$string['usermenu'] = 'Nutzermenü';

/* Settings */
$string['helpurl'] = 'Hilfe-URL';
$string['helpurl_desc'] = 'URL zu einer Hilfeseite, die im Usermenü verlinkt ist.';
$string['cookie_policy_url'] = 'Cookie policy URL';
$string['cookie_policy_url_desc'] = 'URL zu einer Cookie Policy die im Footer verlinkt wird';
$string['matomo_siteurl'] = 'Analytics-URL';
$string['matomo_siteurl_desc'] = 'Ihre "Matomo Analytics"-URL, aber ohne http(s) oder Slash am Ende. Beispiel: "mysite.com/analytics".';
$string['matomo_siteid'] = 'Analytics-Site-ID';
$string['matomo_siteid_desc'] = 'Ihre "Matomo Analytics" Site-ID.';
$string['enable1alert'] = 'Alert 1 zeigen';
$string['enable2alert'] = 'Alert 2 zeigen';
$string['enable3alert'] = 'Alert 3 zeigen';
$string['enablealert_desc'] = 'Ob der jeweilige Alert angezeigt wird.';
$string['alert1type'] = 'Alert-Typ (Alert 1)';
$string['alert1title'] = 'Alert-Titel (Alert 1)';
$string['alert1text'] = 'Alert-Text (Alert 1)';
$string['alert2type'] = 'Alert-Typ (Alert 2)';
$string['alert2title'] = 'Alert-Titel (Alert 2)';
$string['alert2text'] = 'Alert-Text (Alert 2)';
$string['alert3type'] = 'Alert-Typ (Alert 3)';
$string['alert3title'] = 'Alert-Titel (Alert 3)';
$string['alert3text'] = 'Alert-Text (Alert 3)';
$string['alerttype_desc'] = 'Typ des Alerts (vgl. debug levels).';
$string['alerttitle_desc'] = 'Erster Teil der Meldung (wird fettgedruckt).';
$string['alerttext_desc'] = 'Zweiter Teil der Meldung.';


$string['alert_info'] = 'Info';
$string['alert_warning'] = 'Warnung';
$string['alert_general'] = 'Ankündigung';

// Marketing Spots.
$string['marketingheading'] = 'Marketing spots';
$string['marketinginfodesc'] = 'Enter the settings for your marketing spot.';
$string['marketingdesc'] = 'This theme provides the option of enabling three "marketing" or "ad" spots just under the slide show.  These allow you to easily identify core information to your users and provide direct links.';
$string['marketing1'] = 'Marketing spot one';
$string['marketing2'] = 'Marketing spot two';
$string['marketing3'] = 'Marketing spot three';

$string['marketingtitle'] = 'Titel';
$string['marketingtitledesc'] = 'Title to show in this marketing spot';
$string['marketingcontent'] = 'Beschreibung';
$string['marketingcontentdesc'] = 'Content to display in the marketing box.  Keep it short and sweet.';

$string['viewfullsection'] = 'Kompletten Abschnitt anschauen';

// Theme switcher.
$string['choosetheme'] = 'Theme auswählen';
$string['light'] = 'Hell';
$string['dark'] = 'Dunkel';
$string['ostheme'] = 'Systemfarben';
$string['ostheme_help'] = 'Wechselt zwischen hellem und dunklem Theme basierend auf den Systemeinstellungen.';

$string['cookiepolicy'] = "Cookie Datenschutzrichtlinie";

// Cookie consent.
$string['cookie_consent_title'] = "Zustimmung zur Verwendung von Cookies";
$string['cookie_consent_title_one'] = "Diese Website verwendet Cookies";
$string['cookie_consent_body_one'] = "Das Learnweb braucht Cookies um zu funktionieren." .
    " Da wir kein Interesse am Verkauf deiner Daten haben setzen wir nur solche Cookies ein, die zum Betrieb der Website notwendig sind.";
$string['cookie_explanation_title'] = "Was sind Cookies?";
$string['cookie_explanation_body'] = "Cookies sind kleine Textdateien die das Learnweb auf Ihrem PC speichert."
    . " In dieser Textdatei wird dann ein Wert und ein Ablaufdatum gespeichert."
    . " Der Name dieser Textdatei, sowie ihr Inhalt werden dann immer an das Learnweb gesendet wenn Sie es besuchen."
    . " Wenn das gespeicherte Ablaufdatum erreicht ist wird der Cookie automatisch von ihrem PC gelöscht.";
$string['cookie_classes_title'] = "Was für Arten von Cookies gibt es?";
$string['cookie_classes_body'] = "Grundsätzlich unterscheidet man vier Arten von Cookies:" .
    " <br> <ul>" .
        "<li><p class='text-bold'>Essentielle Cookies</p> sind Cookies die zum Betrieb einer Website unbedingt nötig sind." .
        " Außerdem ist bei diesen Cookies sicherzustellen, dass sie nur an den Betreiber einer Website gesendet werden.</li>" .
        "<li><p class='text-bold'>Funktionale Cookies</p> sind Cookies die dazu dienen eine Website um Funktionen zu bereichern." .
        " Beispielsweise kann so die Sprache eines Nutzers gespeichert werden und die Website auf dieser Sprache angezeigt werden." .
        " Die informationen die in Funktionalen Cookies gespeichert sind müssen anonymisiert werden.</li>" .
        "<li><p class='text-bold'>Performance Cookies</p> sind Cookies die dazu genutzt werden die Performance einer Webseite zu analysieren." .
        " Sie speichern Beispielsweise welche Unterseiten ein Nutzer besonders oft besucht oder welchen Text er besonders lang und aufmerksam durchgelesen hat.</li>" .
        "<li><p class='text-bold'>Marketing Cookies</p> sind alle Cookies deren Informationen primär dazu genutzt werden um " .
        " (personalisierte) Werbung für den Nutzer anzuzeigen.</li>" .
    "</ul>";

$string['cookie_reason_title'] = "Wofür benutzt das Learnweb Cookies?";
$string['cookie_reason_body'] = "Das Learnweb setzt Cookies für verschiedene Zwecke ein. Wenn Sie sich Beispielsweise einloggen "
    . "speichert das Learnweb einen Cookie der einen Zugangscode für Sie enthält. Wenn sie dann eine andere Seite des Learnwebs aufrufen "
    . "kann das Learnweb sie anhand dieses Codes identifizieren und Sie müssen sich nicht erneut einloggen. <br> "
    . "Ein anderer Anwendungsbereich sind einige Texteditoren des Learnwebs. Wenn sie einen Text schreiben so speichert ihr PC diesen Text "
    . "während des Schreibens immer wieder in einem Cookie. Somit ist sichergestellt, dass sie auch bei Verbindungsabbruch oder zwischenzeitlichem "
    . "Verlassen des Learnwebs keinen Verlust ihres Textes befürchten müssen.";
$string['agree'] = "Zustimmung erteilen";

$string['invalidconsentdate'] = "Ungültiges Datum für Zustimmungserteilung";
