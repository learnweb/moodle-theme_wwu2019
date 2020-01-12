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

use moodle_page;
use navigation_node;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderer for WWU 2019 Theme
 *
 * @package    theme_wwu2019
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \core_renderer {

    public function __construct(moodle_page $page, $target) {
        parent::__construct($page, $target);
        navigation_node::require_admin_tree();
    }

    public function logo_header() {
        global $CFG;

        $templatecontext = [
                'wwwroot' => $CFG->wwwroot,
                'logo' => $CFG->wwwroot . '/theme/wwu2019/pix/learnweb_logo.svg'
        ];
        return $this->render_from_template('theme_wwu2019/logo_header', $templatecontext);
    }

    public function main_menu() {
        global $CFG;

        $mainmenu = [];

        // Add MyCourses menu
        if (count($courses = $this->get_courses())) {
            $mainmenu[] = [
                    'name' => get_string('mycourses', 'theme_wwu2019'),
                    'hasmenu' => true,
                    'menu' => $this->add_breakers($courses),
                    'icon' => (new \pix_icon('i/graduation-cap', ''))->export_for_pix()
            ];
        }

        // Add Administration menu
        if (count($settings = $this->format_for_template($this->page->settingsnav->children, new \pix_icon('i/settings', '')))) {
            $mainmenu[] = [
                    'name' => get_string('pluginname', 'block_settings'),
                    'hasmenu' => true,
                    'menu' => $this->add_breakers($settings),
                    'icon' => (new \pix_icon('i/cogs', ''))->export_for_pix()
            ];
        }

        // Add dashboard
        $mainmenu[] = [
                'name' => get_string('dashboard', 'theme_wwu2019'),
                'hasmenu' => false,
                'menu' => null,
                'href' => $CFG->wwwroot . '/my/',
        ];

        $templatecontext = [
                'mainmenu' => $mainmenu
        ];

        $this->page->requires->js_call_amd('theme_wwu2019/menu', 'init');
        return $this->render_from_template('theme_wwu2019/menu', $templatecontext);
    }

    private function format_for_template(\navigation_node_collection $node_collection, \pix_icon $default_icon) {
        $items = [];
        foreach ($node_collection as $node) {
            if ($node->display) {

                $templateformat = array(
                        'name' => $node->get_content()
                );

                if ($node->icon && !$node->hideicon) {
                    $templateformat['icon'] = $node->icon->export_for_pix();
                } else {
                    $templateformat['icon'] = $default_icon->export_for_pix();
                }

                if ($node->has_children()) {
                    $templateformat['hasmenu'] = true;
                    $templateformat['menu'] = $this->format_for_template($node->children, $default_icon);
                } else {
                    $templateformat['hasmenu'] = false;
                    $templateformat['menu'] = null;
                }
                if ($node->has_action() && !$node->has_children()) {
                    $templateformat['href'] = $node->action->out();
                }
                $items[] = $templateformat;
            }
        }
        return $items;
    }

    private function add_breakers(array $menuitems) {
        if (count($menuitems) == 0) {
            return array();
        }
        $c2 = intval(ceil(count($menuitems) / 2.0) - 1);
        $c3_1 = intval(ceil(count($menuitems) / 3.0) - 1);
        $c3_2 = intval(min(ceil(count($menuitems) / 3.0) * 2 - 1, count($menuitems) - 1));
        $menuitems[$c2]['breaker'] = ['c2'];
        if (array_key_exists('breaker', $menuitems[$c3_1])) {
            $menuitems[$c3_1]['breaker'][] = 'c3';
        } else {
            $menuitems[$c3_1]['breaker'] = ['c3'];
        }
        if (array_key_exists('breaker', $menuitems[$c3_2])) {
            $menuitems[$c3_2]['breaker'][] = 'c3';
        } else {
            $menuitems[$c3_2]['breaker'] = ['c3'];
        }

        return $menuitems;
    }

    private function get_courses() {
        global $CFG;

        $courses = enrol_get_my_courses(array(), 'c.startdate DESC');
        $terms = [];

        $termindependentlimit = new \DateTime("2000-00-00");

        foreach ($courses as $course) {
            $coursestart = new \DateTime();
            $coursestart->setTimestamp($course->startdate);

            $year = (int) $coursestart->format('Y');
            $term = 0;
            $istermindependent = false;

            $term0start = new \DateTime("$year-04-01");
            $term1start = new \DateTime("$year-10-01");

            if ($coursestart < $termindependentlimit) {
                $istermindependent = true;
            } else if ($coursestart < $term0start) {
                $year--;
                $term = 1;
            } else if ($coursestart < $term1start) {
                $term = 0;
            } else {
                $term = 1;
            }

            $termid = $istermindependent ? 0 : $year . '_' . $term;
            if (!array_key_exists($termid, $terms)) {
                if ($istermindependent) {
                    $name = get_string('termindependent', 'theme_wwu2019');
                } else {
                    if ($term == 0) {
                        $name = 'SoSe ' . $year;
                    } else {
                        $name = 'WiSe ' . $year . '/' . ($year + 1);
                    }
                }
                $terms[$termid] = [
                    'name' => $name,
                    'icon' => (new \pix_icon('i/calendar', ''))->export_for_pix(),
                    'hasmenu' => true,
                    'menu' => []
                ];
            }

            $terms[$termid]['menu'][] = [
                'name' => $course->shortname,
                'href' => $CFG->wwwroot . '/course/view.php?id=' . $course->id,
                'icon' => (new \pix_icon('i/graduation-cap', ''))->export_for_pix(),
                'hasmenu' => false,
                'menu' => null
            ];
        }
        return array_values($terms);
    }

}
