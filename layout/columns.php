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

$layout = new \theme_wwu2019\layout();

$layout->sso_auto_login();

$templatecontext = $layout->get_default_template_context();

$PAGE->requires->js_call_amd('theme_wwu2019/alert', 'init');

echo $OUTPUT->render_from_template('theme_wwu2019/columns', $templatecontext);

