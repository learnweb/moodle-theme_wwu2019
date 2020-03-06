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
 * Layout for the WWU 2019 Theme.
 *
 * @package   theme_wwu2019
 * @copyright 2019 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Autologin if access takes place via SSO.
if (!isloggedin() && wwusso_username()) {
    global $CFG;
    $url = qualified_me();
    // Do not require for /login/index.php because that would yield an infinite redirect loop.
    if ($url != $CFG->wwwroot . '/login/index.php') {
        require_login();
        die();
    }
}

$bodyattributes = $OUTPUT->body_attributes();
$blockspost = $OUTPUT->blocks('side-post');

$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepostblocks' => $blockspost,
    'haspostblocks' => $hassidepost,
    'bodyattributes' => $bodyattributes,
    'footer' => $OUTPUT->get_footer_context(),
    'alerts' => \theme_wwu2019\alerts::get_alerts(),
];

$nav = $PAGE->flatnav;

$templatecontext['flatnavigation'] = $nav;
$templatecontext['firstcollectionlabel'] = $nav->get_collectionlabel();

$marketingboxes = [];
for ($i = 0; $i<3; $i++) {
    $marketingboxes[$i] = new stdClass();
    $marketingspot = $i + 1;
    $marketingboxes[$i]->index = $i;
    $marketingboxes[$i]->title = get_config('theme_essential', 'marketing' . $marketingspot);
    $marketingboxes[$i]->content = get_config('theme_essential', 'marketing' . $marketingspot . 'content');
}
$templatecontext['marketingboxes'] = $marketingboxes;

$PAGE->requires->js_call_amd('theme_wwu2019/alert', 'init');

echo $OUTPUT->render_from_template('theme_wwu2019/frontpage', $templatecontext);

