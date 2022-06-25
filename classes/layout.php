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
 * This class provides functions for the different layouts of the theme.
 * @package    theme_wwu2019
 * @copyright  2020 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_wwu2019;

use theme_wwu2019\output\core_renderer;

defined('MOODLE_INTERNAL') || die();

/**
 * This class provides functions for the different layouts of the theme.
 * @package    theme_wwu2019
 * @copyright  20120 Tobias Reischmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class layout {

    /**
     * Autologin if access takes place via SSO.
     */
    public static function sso_auto_login() {
        if (!isloggedin() && wwusso_username()) {
            global $CFG;
            $url = qualified_me();
            // Do not require for /login/index.php because that would yield an infinite redirect loop.
            if ($url != $CFG->wwwroot . '/login/index.php') {
                require_login();
                die();
            }
        }
    }

    /**
     * Returns the default context for the columns layout, which can be reused for other layouts.
     * @return array
     */
    public static function get_default_template_context() {
        global $OUTPUT, $PAGE, $SITE, $CFG;

        $bodyattributes = $OUTPUT->body_attributes();

        $blockspost = $OUTPUT->blocks('side-post');

        $hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

        $secondarynavigation = false;
        $overflow = '';
        // DO NOT render secondary menu inside courses?
        if ($PAGE->has_secondary_navigation() && $PAGE->course->id == SITEID) {
            $tablistnav = $PAGE->has_tablist_secondary_navigation();
            $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
            $secondarynavigation = $moremenu->export_for_template($OUTPUT);
            $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
            if (!is_null($overflowdata)) {
                $overflow = $overflowdata->export_for_template($OUTPUT);
            }
        }

        $templatecontext = [
            'sitename' => format_string($SITE->shortname, true,
                ['context' => \context_course::instance(SITEID), "escape" => false]),
            'isexamweb' => core_renderer::is_examweb(),
            'output' => $OUTPUT,
            'sidepostblocks' => $blockspost,
            'secondarymoremenu' => $secondarynavigation,
            'haspostblocks' => $hassidepost,
            'bodyattributes' => $bodyattributes,
            'footer' => $OUTPUT->get_footer_context(),
            'alerts' => alerts::get_alerts(),
            'supportemail' => $CFG->supportemail,
        ];

        return $templatecontext;
    }


}
