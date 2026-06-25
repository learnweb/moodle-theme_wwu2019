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

use context_course;
use core_course_category;
use core_course_list_element;
use core_tag_tag;
use coursecat_helper;
use html_writer;
use moodle_url;
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


    /**
     * Returns HTML to display course category name.
     *
     * @param coursecat_helper $chelper
     * @param core_course_list_element $course
     * @return string
     */
    protected function course_category_name(coursecat_helper $chelper, core_course_list_element $course): string {
        $content = '';
        // Display course category if necessary (for example in search results).
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_EXPANDED_WITH_CAT) {
            if ($cat = core_course_category::get($course->category, IGNORE_MISSING)) {
                $catname = $cat->get_formatted_name();
                $content .= html_writer::start_tag('div', ['class' => 'coursecat']);
                $content .= html_writer::start_tag('span', ['class' => 'fw-bold']);
                $content .= get_string('category').': ';
                $content .= html_writer::link(new moodle_url('/course/index.php', ['categoryid' => $cat->id]),
                    $catname, ['class' => $cat->visible ? '' : 'dimmed']);
                $content .= html_writer::end_tag('span');
                if (str_contains($catname, 'Archiv ')) {
                    if ($tags = core_tag_tag::get_item_tags('core', 'course', $course->id, true )) {
                        $context = context_course::instance($course->id);
                        foreach ($tags as $tag) {
                            $tname = get_string('before', 'theme_wwu2019').": ".core_tag_tag::make_display_name($tag, false);
                            $turl = core_tag_tag::make_url($tag->tagcollid, $tag->rawname, 0, $context->id);
                            $content .= html_writer::link($turl, $tname, ['class' => 'ml-4 customfieldvalue']);
                        }
                    }
                }
                $content .= html_writer::end_tag('div');
            }
        }
        return $content;
    }

}
