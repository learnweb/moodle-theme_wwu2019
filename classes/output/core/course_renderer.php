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
 * Overrides Funktions of the course_renderer for theme_wwu2019
 *
 * @package   theme_wwu2019
 * @copyright 2020 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_wwu2019\output\core;
use stdClass;

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/course/renderer.php');

/**
 * Overrides Funktions of the course_renderer for theme_wwu2019
 *
 * @package   theme_wwu2019
 * @copyright 2020 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends \core_course_renderer {

    /**
     * Renders html to display a course search form.
     *
     * @param string $value default value to populate the search field
     * @param string $format display format - 'plain' (default), 'short' or 'navbar'
     * @return string
     */
    public function course_search_form($value = '', $format = 'plain') {
        static $count = 0;
        $formid = 'coursesearch';
        if ((++$count) > 1) {
            $formid .= $count;
        }

        switch ($format) {
            case 'navbar' :
                $formid = 'coursesearchnavbar';
                $inputid = 'navsearchbox';
                $inputsize = 20;
                break;
            case 'short' :
                $inputid = 'shortsearchbox';
                $inputsize = 12;
                break;
            default :
                $inputid = 'coursesearchbox';
                $inputsize = 30;
        }

        $data = new stdClass();
        $data->searchurl = (new \moodle_url('/course/search.php'))->out(false);
        $data->id = $formid;
        $data->inputid = $inputid;
        $data->inputsize = $inputsize;
        $data->value = $value;
        $data->globalsearch = (new \moodle_url('/search/index.php'))->out(false);

        $output = $this->render_from_template('theme_wwu2019/course_search_form', $data);
        return $output;
    }

}
