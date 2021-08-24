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

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string All fixed Sass for this theme.
 */
function theme_wwu2019_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';

    // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.
    // ...Export $wwwroot as scss-variable.
    $wwwnormalizedroot = preg_replace('/https?:\/\/(www|[xs]sso|sso\d?)\./i',  'https://www.', $CFG->wwwroot, 1);
    $pre = '$wwwroot: "' . $wwwnormalizedroot . '";' . "\n";
    // ...Export $learnwebblue (themecolor) as scss-variable.
    $pre .= '$learnwebblue: ' . \theme_wwu2019\output\core_renderer::THEMECOLOR . ";\n";

    $pre .= file_get_contents($CFG->dirroot . '/theme/wwu2019/scss/pre.scss');

    // Main CSS - Get the CSS from theme Classic.
    $scss .= file_get_contents($CFG->dirroot . '/theme/classic/scss/classic/pre.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/wwu2019/scss/preset/default.scss');
    $scss .= file_get_contents($CFG->dirroot . '/theme/classic/scss/classic/post.scss');

    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.
    $post = file_get_contents($CFG->dirroot . '/theme/wwu2019/scss/post.scss');

    return $pre . "\n" . $scss . "\n" . $post;
}

function theme_wwu2019_user_preferences() {
    $preferences['theme_wwu2019_theme'] = [
            'null' => NULL_NOT_ALLOWED,
            'default' => 0,
            'type' => PARAM_INT,
            'choices' => [0, 1, 2,]
    ];
    return $preferences;
}

/**
 * Adds a link to the content bank to the course menu.
 *
 * @param navigation_node $navigation The navigation node to extend
 * @param stdClass $course The course to object for the tool
 * @param context $context The context of the course
 * @return void|null return null if we don't want to display the node.
 */
function theme_wwu2019_extend_navigation_course($navigation, $course, $context) {
    global $PAGE;

    // Only add this settings item on non-site course pages.
    if (!$PAGE->course || $PAGE->course->id == SITEID || !has_capability('moodle/contentbank:access', $context)) {
        return null;
    }

    $node = navigation_node::create(
            get_string('contentbank'),
            new moodle_url('/contentbank/index.php', ['contextid' => $context->id]),
            navigation_node::NODETYPE_LEAF,
            null,
            'contentbank',
            new pix_icon('i/contentbank', '')
    );

    $navigation->add_node($node);
}
