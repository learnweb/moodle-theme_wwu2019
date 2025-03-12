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
 * Port of essential alerts to WWUCD theme.
 *
 * @package    theme_wwu2019
 * @copyright  2020 Jan C. Dageförde, WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_wwu2019;

/**
 * Port of essential alerts to WWUCD theme.
 *
 * @package    theme_wwu2019
 * @copyright  2020 Jan C. Dageförde, WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class alerts {

    /**
     * Get a string containing the rendered alerts.
     *
     * @return string
     */
    public static function get_alerts() {
        global $CFG, $DB;
        $pluginconfig = get_config('theme_wwu2019');

        // Fetch alerts -- but only if they are not suppressed by user cookie.
        if (isset($_COOKIE['essentialalert-1']) && $_COOKIE['essentialalert-1'] === "closed") {
            $enable1alert = false;
        } else {
            $enable1alert = $pluginconfig->enable1alert;
        }

        if (isset($_COOKIE['essentialalert-2']) && $_COOKIE['essentialalert-2'] === "closed") {
            $enable2alert = false;
        } else {
            $enable2alert = $pluginconfig->enable2alert;
        }

        if (isset($_COOKIE['essentialalert-3']) && $_COOKIE['essentialalert-3'] === "closed") {
            $enable3alert = false;
        } else {
            $enable3alert = $pluginconfig->enable3alert;
        }

        if (isset($_COOKIE['essentialalert-wartung']) && $_COOKIE['essentialalert-wartung'] === "closed") {
            $enablewartungsalert = false;
        } else {
            // Once per session, the mdl_forum_discussions table is checked for new alerts. The result is cached.
            if (!isset($_SESSION["theme_essential_alert"])) {
                $_SESSION["theme_essential_alert"] = $DB->get_record_sql(
                    "SELECT id, name FROM {forum_discussions} WHERE forum=1 ORDER BY timemodified DESC LIMIT 1");
            }
            $enablewartungsalert = ($_SESSION["theme_essential_alert"] !== false) && $_SESSION["theme_essential_alert"]->id != 1;
        }

        if (!$enable1alert && !$enable2alert && !$enable3alert && !$enablewartungsalert) {
            return '';
        }

        $alertinfo = '<span class="fa-stack"><span aria-hidden="true" class="fa fa-info fa-stack-1x fa-inverse"></span></span>';
        $alerterror = '<span class="fa-stack"><span aria-hidden="true" class="fa fa-warning fa-stack-1x fa-inverse"></span></span>';
        $alertsuccess = '<span class="fa-stack"><span aria-hidden="true" class="fa fa-bullhorn fa-stack-1x fa-inverse">' .
            '</span></span>';

        $alertstring = '<div class="essentialalerts">';

        if ($enablewartungsalert) {
            $alertstring .= '<div id="essentialalert-wartung" class="useralerts alert alert-success">';
            $alertstring .= '<button type="button" class="close" data-dismiss="alert">' .
                '<span class="fa fa-times-circle" aria-hidden="true"></span></button>';
            $alertwartungsicon = 'alertsuccess';
            $alertstring .= $$alertwartungsicon . '<span class="title">' . 'Wartungsarbeiten' . '</span>' .
                $_SESSION["theme_essential_alert"]->name . ' &mdash; <a href="'.$CFG->wwwroot .
                '/mod/forum/discuss.php?d='.$_SESSION["theme_essential_alert"]->id.'">Mehr</a>';
            $alertstring .= '</div>';
        }

        if ($enable1alert) {
            $alertstring .= '<div id="essentialalert-1" class="useralerts alert alert-'.$pluginconfig->alert1type.'">';
            $alertstring .= '<button type="button" class="close" data-dismiss="alert">' .
                '<span class="fa fa-times-circle" aria-hidden="true"></span></button>';
            $alert1icon = 'alert' . $pluginconfig->alert1type;
            $alertstring .= $$alert1icon.'<span class="title">'.$pluginconfig->alert1title;
            $alertstring .= '</span>'.$pluginconfig->alert1text;
            $alertstring .= '</div>';
        }

        if ($enable2alert) {
            $alertstring .= '<div id="essentialalert-2" class="useralerts alert alert-'.$pluginconfig->alert2type.'">';
            $alertstring .= '<button type="button" class="close" data-dismiss="alert">' .
                '<span class="fa fa-times-circle" aria-hidden="true"></span></button>';
            $alert2icon = 'alert' . $pluginconfig->alert2type;
            $alertstring .= $$alert2icon.'<span class="title">'.$pluginconfig->alert2title;
            $alertstring .= '</span>'.$pluginconfig->alert2text;
            $alertstring .= '</div>';
        }

        if ($enable3alert) {
            $alertstring .= '<div id="essentialalert-3" class="useralerts alert alert-'.$pluginconfig->alert3type.'">';
            $alertstring .= '<button type="button" class="close" data-dismiss="alert">' .
                '<span class="fa fa-times-circle" aria-hidden="true"></span></button>';
            $alert3icon = 'alert' . $pluginconfig->alert3type;
            $alertstring .= $$alert3icon.'<span class="title">'.$pluginconfig->alert3title;
            $alertstring .= '</span>'.$pluginconfig->alert3text;
            $alertstring .= '</div>';
        }

        $alertstring .= '</div>';

        return $alertstring;
    }
}
