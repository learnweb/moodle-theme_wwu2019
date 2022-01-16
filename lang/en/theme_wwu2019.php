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
$string['choosereadme'] = 'This theme is based on moodle\'s classic theme and aligned with the corporate design of the University of Münster';

/* Main menu */
$string['mycourses'] = 'My courses';
$string['termindependent'] = 'Semester independent';
$string['dashboard'] = 'Dashboard';

$string['thiscourse'] = 'This Course';
$string['searchadmin'] = 'Admin search';

$string['openmainmenu'] = 'Open main menu';
$string['mainmenu'] = 'Main menu';

/* User menu */
$string['loggedinas'] = '{$a->real} logged in as {$a->fake}';
$string['badgepreferences'] = 'Badge';
$string['mygrades'] = 'My grades';

$string['openusermenu'] = 'Open user menu';
$string['usermenu'] = 'User menu';

/* Settings */
$string['helpurl'] = 'Help URL';
$string['helpurl_desc'] = 'URL to helppage that will be linked to in the usermenu';
$string['matomo_siteurl'] = 'Analytics URL';
$string['matomo_siteurl_desc'] = 'Enter your "Matomo Analytics" URL without http(s) or a trailing slash. For example "mysite.com/analytics".';
$string['matomo_siteid'] = 'Analytics Site ID';
$string['matomo_siteid_desc'] = 'Enter your "Matomo Analytics" site id.';
$string['enable1alert'] = 'Show alert 1';
$string['enable2alert'] = 'Show alert 2';
$string['enable3alert'] = 'Show alert 3';
$string['enablealert_desc'] = 'Show/hide a specific alert.';
$string['alert1type'] = 'Alert type (alert 1)';
$string['alert1title'] = 'Alert title (alert 1)';
$string['alert1text'] = 'Alert text (alert 1)';
$string['alert2type'] = 'Alert type (alert 2)';
$string['alert2title'] = 'Alert title (alert 2)';
$string['alert2text'] = 'Alert text (alert 2)';
$string['alert3type'] = 'Alert type (alert 3)';
$string['alert3title'] = 'Alert title (alert 3)';
$string['alert3text'] = 'Alert text (alert 3)';
$string['alerttype_desc'] = 'Type of alert (similar to debug levels).';
$string['alerttitle_desc'] = 'First part of the alert (printed in bold).';
$string['alerttext_desc'] = 'Second part of the alert.';


$string['alert_info'] = 'Info';
$string['alert_warning'] = 'Warning';
$string['alert_general'] = 'General';

// Marketing Spots.
$string['marketingheading'] = 'Marketing spots';
$string['marketinginfodesc'] = 'Enter the settings for your marketing spot.';
$string['marketingdesc'] = 'This theme provides the option of enabling three "marketing" or "ad" spots just under the slide show.  These allow you to easily identify core information to your users and provide direct links.';
$string['marketing1'] = 'Marketing spot one';
$string['marketing2'] = 'Marketing spot two';
$string['marketing3'] = 'Marketing spot three';

$string['marketingtitle'] = 'Title';
$string['marketingtitledesc'] = 'Title to show in this marketing spot';
$string['marketingcontent'] = 'Content';
$string['marketingcontentdesc'] = 'Content to display in the marketing box.  Keep it short and sweet.';

$string['viewfullsection'] = 'View full section';

// Theme switcher
$string['choosetheme'] = 'Choose theme';
$string['light'] = 'Light';
$string['dark'] = 'Dark';
$string['ostheme'] = 'System colors';
$string['ostheme_help'] = 'Switches between light and dark theme depending on the OS settings.';

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

$string['invalidconsentdate'] = "Invalid date for consentdate";
