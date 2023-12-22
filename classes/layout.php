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
        if (!function_exists('wwusso_username')) {
            return;
        }
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
        global $PAGE, $SITE, $CFG;

        $renderer = $PAGE->get_renderer('core');

        $PAGE->navbar->get_items();

        $bodyattributes = $renderer->body_attributes();

        $blockspost = $renderer->blocks('side-post');

        $hassidepost = $PAGE->blocks->region_has_content('side-post', $renderer);

        $secondarynavigation = false;

        $header = $PAGE->activityheader;
        $headercontent = $header->export_for_template($renderer);

        $overflow = '';
        if ($PAGE->has_secondary_navigation() && $PAGE->pagelayout != 'course') {
            $tablistnav = $PAGE->has_tablist_secondary_navigation();
            $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
            $secondarynavigation = $moremenu->export_for_template($renderer);
            $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
            if (!is_null($overflowdata)) {
                $overflow = $overflowdata->export_for_template($renderer);
            }
        }

        $templatecontext = [
            'sitename' => format_string($SITE->shortname, true,
                ['context' => \context_course::instance(SITEID), "escape" => false]),
            'isexamweb' => core_renderer::is_examweb(),
            'output' => $renderer,
            'sidepostblocks' => $blockspost,
            'secondarymoremenu' => $secondarynavigation,
            'haspostblocks' => $hassidepost,
            'headercontent' => $headercontent,
            'bodyattributes' => $bodyattributes,
            'footer' => $renderer->get_footer_context(),
            'alerts' => alerts::get_alerts(),
            'supportemail' => $CFG->supportemail,
        ];

        return $templatecontext;
    }

    /**
     * Do important snow stuff.
     */
    public static function handle_snow() {
        global $PAGE;
        $snowenable = get_config('theme_wwu2019', 'snow_enable');
        if ($snowenable == 1 || $snowenable == 2 && (
                        get_config('theme_wwu2019', 'snow_start') < time()
                        && get_config('theme_wwu2019', 'snow_end') > time())) {
            user_preference_allow_ajax_update('theme_wwu2019_snow', PARAM_INT);
            $snowpreference = get_user_preferences('theme_wwu2019_snow', '1');
            $PAGE->requires->js_call_amd('theme_wwu2019/snow', 'init', [intval($snowpreference)]);
        }
    }


}
