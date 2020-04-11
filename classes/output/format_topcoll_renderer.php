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
 * Overriden functions for format_topcoll_renderer and format_section_renderer_base.
 *
 * @package theme_wwu2019
 * @copyright 2020 Justus Dieckmann WWU
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_wwu2019\output;

use completion_info;
use context_course;
use html_writer;
use moodle_url;
use stdClass;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/course/format/topcoll/renderer.php');

/**
 * Overriden functions for format_topcoll_renderer and format_section_renderer_base.
 *
 * @copyright 2020 Justus Dieckmann WWU
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_topcoll_renderer extends \format_topcoll_renderer {
    use wwu_format_trait;

    /**
     * Generate the display of the header part of a section before
     * course modules are included.
     *
     * @param stdClass $section The course_section entry from DB.
     * @param stdClass $course The course entry from DB.
     * @param bool $onsectionpage true if being printed on a section page.
     * @param int $sectionreturn The section to return to after an action.
     * @return string HTML to output.
     */
    protected function section_header($section, $course, $onsectionpage, $sectionreturn = null) {
        $o = '';

        $sectionstyle = '';
        $rightcurrent = '';
        $context = context_course::instance($course->id);

        if ($section->section != 0) {
            // Only in the non-general sections.
            if (!$section->visible) {
                $sectionstyle = ' hidden';
            }
            if ($section->section == $this->currentsection) {
                $sectionstyle = ' current';
                $rightcurrent = ' left';
            }
        }

        if ((!$this->formatresponsive) && ($section->section != 0) &&
                ($this->tcsettings['layoutcolumnorientation'] == 2)) { // Horizontal column layout.
            $sectionstyle .= ' ' . $this->get_column_class($this->tcsettings['layoutcolumns']);
        }
        $liattributes = array(
                'id' => 'section-' . $section->section,
                'class' => 'section main clearfix' . $sectionstyle,
                'role' => 'region',
                'aria-label' => $this->courseformat->get_topcoll_section_name($course, $section, false)
        );
        if (($this->formatresponsive) && ($this->tcsettings['layoutcolumnorientation'] == 2)) { // Horizontal column layout.
            $liattributes['style'] = 'width: ' . $this->tccolumnwidth . '%;';
        }
        $o .= html_writer::start_tag('li', $liattributes);

        if (($onsectionpage == false) && ($section->section != 0)) {

            $o .= html_writer::start_tag('div',
                    array('class' => 'header sectionhead toggle toggle-'.$this->tcsettings['toggleiconset'],
                            'id' => 'toggle-'.$section->section, 'tabindex' => '0')
            );

            $leftcontent = $this->section_left_content($section, $course, $onsectionpage);
            $o .= html_writer::tag('div', $leftcontent, array('class' => 'left side'));

            if ((!($section->toggle === null)) && ($section->toggle == true)) {
                $toggleclass = 'toggle_open';
                $ariaexpanded = 'true';
                $sectionclass = ' sectionopen';
            } else {
                $toggleclass = 'toggle_closed';
                $ariaexpanded = 'false';
                $sectionclass = '';
            }
            $toggleclass .= ' the_toggle ' . $this->tctoggleiconsize;

            if (empty($this->tcsettings)) {
                $this->tcsettings = $this->courseformat->get_settings();
            }

            if ($this->userisediting) {
                $title = $this->section_title($section, $course);
            } else {
                $title = $this->courseformat->get_topcoll_section_name($course, $section, true);
            }

            $o .= html_writer::tag('span', $title, array('class' => 'sectionname ' . $toggleclass, 'role' => 'button',
                    'aria-expanded' => $ariaexpanded));

            $o .= $this->section_availability($section);

            $rightcontent = $this->section_right_content($section, $course, $onsectionpage);
            $o .= html_writer::tag('div', $rightcontent, array('class' => 'right side'));

            $o .= html_writer::end_div();

            if ($this->tcsettings['showsectionsummary'] == 2) {
                $o .= $this->section_summary_container($section);
            }

            $o .= html_writer::start_tag('div',
                    array('class' => 'content sectionbody toggledsection' . $sectionclass,
                            'id' => 'toggledsection-' . $section->section)
            );

            if ($this->userisediting) {
                // CONTRIB-7434.
                $o .= html_writer::tag('span',
                        $this->courseformat->get_topcoll_section_name($course, $section, false),
                        array('class' => 'hidden', 'aria-hidden' => 'true'));
            }

            if ($this->tcsettings['showsectionsummary'] == 1) {
                $o .= $this->section_summary_container($section);
            }
        } else {
            // When on a section page, we only display the general section title, if title is not the default one.

            // Swap content.
            $o .= html_writer::start_div('header');
            $leftcontent = $this->section_left_content($section, $course, $onsectionpage);
            $o .= html_writer::tag('div', $leftcontent, array('class' => 'left side'));
            $o .= $this->output->heading($this->section_title($section, $course), 3, 'section-title sectionname');
            $rightcontent = $this->section_right_content($section, $course, $onsectionpage);
            $o .= html_writer::tag('div', $rightcontent, array('class' => 'right side'));
            $o .= html_writer::end_div();

            $o .= html_writer::start_div('content');
            $o .= $this->section_availability($section);
            $o .= html_writer::start_tag('div', array('class' => 'summary'));

            $o .= $this->format_summary_text($section);

            $o .= html_writer::end_div();
        }
        return $o;
    }

    /**
     * Generate the display of the footer part of a section after
     * course modules are included.
     *
     * @param stdClass $section The course_section entry from DB.
     * @param stdClass $course The course entry from DB.
     * @param bool $onsectionpage true if being printed on a section page.
     * @param int $sectionreturn The section to return to after an action.
     * @return string HTML to output.
     */
    protected function topcoll_section_footer($section, $course, $onsectionpage, $sectionreturn = null) {
        $o = html_writer::end_tag('li');
        return $o;
    }

    /**
     * Generate the content to displayed on the left part of a section
     * before course modules are included.
     *
     * @param stdClass $section The course_section entry from DB.
     * @param stdClass $course The course entry from DB.
     * @param bool $onsectionpage true if being printed on a section page.
     * @return string HTML to output.
     */
    protected function section_left_content($section, $course, $onsectionpage) {
        $o = '';

        if (($section->section != 0) && (!$onsectionpage)) {
            // Only in the non-general sections.
            if ($this->courseformat->is_section_current($section)) {
                $o .= get_accesshide(get_string('currentsection', 'format_' . $course->format));
            }
            if (empty($this->tcsettings)) {
                $this->tcsettings = $this->courseformat->get_settings();
            }
        }
        return $o;
    }

}
