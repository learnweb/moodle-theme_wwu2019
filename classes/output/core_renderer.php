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
 * Renderer for WWU 2019 Theme
 *
 * @package    theme_wwu2019
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_wwu2019\output;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderer for WWU 2019 Theme
 *
 * @package    theme_wwu2019
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \core_renderer {

    public function logo_header() {
        global $CFG;

        $templatecontext = [
                'wwwroot' => $CFG->wwwroot,
                'logo' => $CFG->wwwroot . '/theme/wwu2019/pix/learnweb_logo.svg'
        ];
        return $this->render_from_template('theme_wwu2019/logo_header', $templatecontext);
    }

    public function main_menu() {
        global $PAGE;
        $templatecontext = [
                'mainmenu' => [
                        [
                                'name' => 'Meine Kurse',
                                'icon' => 'briefcase',
                                'hasmenu' => 'true',
                                'menu' => [
                                        [
                                                'name' => 'WiSe 2019/2020',
                                                'icon' => 'calendar',
                                                'menu' => '',
                                                'hasmenu' => 'false',
                                                'breaker' => ''
                                        ],
                                        [
                                                'name' => 'SoSe 2019',
                                                'icon' => 'calendar',
                                                'menu' => '',
                                                'hasmenu' => 'false',
                                                'breaker' => 'c3 c2'
                                        ],
                                        [
                                                'name' => 'WiSe 2018/2019',
                                                'icon' => 'calendar',
                                                'menu' => '',
                                                'hasmenu' => 'false',
                                                'breaker' => 'c3'
                                        ],
                                        [
                                                'name' => 'SoSe 2018',
                                                'icon' => 'calendar',
                                                'menu' => '',
                                                'hasmenu' => 'false',
                                                'breaker' => ''
                                        ],
                                ]
                        ],
                        [
                                'name' => 'Dieser Kurs',
                                'icon' => 'book',
                                'hasmenu' => 'true',
                                'menu' => [
                                        [
                                                'name' => 'Etwas ganz anderes',
                                                'icon' => 'calendar',
                                                'menu' => '',
                                                'hasmenu' => 'false',
                                                'breaker' => 'c2 c3'
                                        ],
                                        [
                                                'name' => 'Noch etwas anderes',
                                                'icon' => 'calendar',
                                                'menu' => '',
                                                'hasmenu' => 'false',
                                                'breaker' => 'c3'
                                        ]
                                ]
                        ],
                ]
        ];
        $PAGE->requires->js_call_amd('theme_wwu2019/menu', 'init');
        return $this->render_from_template('theme_wwu2019/menu', $templatecontext);
    }

}
