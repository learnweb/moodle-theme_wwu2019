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

/**
 * Overriden functions for format_topcoll_renderer and format_section_renderer_base.
 *
 * @copyright 2020 Justus Dieckmann WWU
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_topcoll_renderer extends \format_topcoll\output\renderer {
    use wwu_format_trait;

    /**
     * Generate the section.
     *
     * @param stdClass $section The course_section entry from DB.
     * @param stdClass $course The course entry from DB.
     * @param bool $onsectionpage true if being printed on a section page.
     * @param int $sectionreturn The section to return to after an action.
     * @param array $displayoptions The display options.
     *
     * @return string HTML to output.
     */
    protected function topcoll_section($section, $course, $onsectionpage, $sectionreturn = null, $displayoptions = array()) {
        $context = context_course::instance($course->id);

        $sectioncontext = array(
            'rtl' => $this->rtl,
            'sectionid' => $section->id,
            'sectionno' => $section->section,
            'sectionreturn' => $sectionreturn
        );

        if ($section->section != 0) {
            // Only in the non-general sections.
            if (!$section->visible) {
                $sectioncontext['sectionstyle'] = 'hidden';
            }
            if ($section->section == $this->currentsection) {
                $sectioncontext['sectionstyle'] = 'current';
            }
        }

        if (empty($this->tcsettings)) {
            $this->tcsettings = $this->courseformat->get_settings();
        }

        if ($this->tcsettings['layoutcolumnorientation'] == 2) { // Horizontal column layout.
            if ($this->formatresponsive) {
                $sectioncontext['horizontalwidth'] = $this->tccolumnwidth;
            } else if ((!$onsectionpage) && ($section->section != 0)) {
                $sectioncontext['horizontalclass'] = $this->get_column_class($this->tcsettings['layoutcolumns']);
            }
        }

        if ((($this->mobiletheme === false) && ($this->tablettheme === false)) || ($this->userisediting)) {
            $sectioncontext['nomtore'] = true;
            $sectioncontext['leftcontent'] = $this->section_left_content($section, $course, $onsectionpage);
            $sectioncontext['rightcontent'] = '';
            $sectioncontext['rightcontent'] .= $this->section_right_content($section, $course, $onsectionpage);
        }
        if ($section->section != 0) {
            $sectioncontext['contentaria'] = true;
        }
        $sectioncontext['sectionavailability'] = $this->section_availability($section);

        if (($onsectionpage == false) && ($section->section != 0)) {
            $sectioncontext['sectionpage'] = false;
            $sectioncontext['toggleiconset'] = $this->tcsettings['toggleiconset'];
            $sectioncontext['toggleiconsize'] = $this->tctoggleiconsize;

            if ((!($section->toggle === null)) && ($section->toggle == true)) {
                $sectioncontext['toggleopen'] = true;
            } else {
                $sectioncontext['toggleopen'] = false;
            }

            if ($this->userisediting) {
                $title = $this->section_title($section, $course);
            } else {
                $title = $this->courseformat->get_topcoll_section_name($course, $section, true);
            }
            $sectioncontext['heading'] = $title;

            $sectioncontext['sectionsummary'] = $this->section_summary_container($section);
            $sectioncontext['sectionsummarywhencollapsed'] = ($this->tcsettings['showsectionsummary'] == 2);

            if ($this->userisediting) {
                // CONTRIB-7434.
                $sectioncontext['usereditingtitle'] = $this->courseformat->get_topcoll_section_name($course, $section, false);
            }
        } else {
            $sectioncontext['sectionpage'] = true;
            // When on a section page, we only display the general section title, if title is not the default one.
            $title = $this->section_title($section, $course);
            $sectioncontext['heading'] = $title;
            $sectioncontext['sectionsummary'] = $this->format_summary_text($section);
        }
        if ($this->userisediting && has_capability('moodle/course:update', $context)) {
            $sectioncontext['usereditingicon'] = $this->output->pix_icon('t/edit', get_string('edit'));
            $sectioncontext['usereditingurl'] = new moodle_url('/course/editsection.php', array('id' => $section->id, 'sr' => $sectionreturn));
        }

        if ($section->uservisible) {
            $sectioncontext['cscml'] = $this->courserenderer->course_section_cm_list($course, $section, $sectionreturn, $displayoptions);
            $sectioncontext['cscml'] .= $this->courserenderer->course_section_add_cm_control($course, $section->section, $sectionreturn);
        }

        return $this->render_from_template('format_topcoll/section', $sectioncontext);
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

    /**
     * Generate the content to displayed on the right part of a section
     * before course modules are included.
     *
     * @param stdClass $section The course_section entry from DB.
     * @param stdClass $course The course entry from DB.
     * @param bool $onsectionpage true if being printed on a section page.
     * @param bool $sectionishidden true if section is hidden.
     *
     * @return string HTML to output.
     */
    protected function section_right_content($section, $course, $onsectionpage, $sectionishidden = false) {
        $o = '';

        $controls = $this->section_edit_control_items($course, $section, $onsectionpage);
        if (!empty($controls)) {
            $o .= $this->section_edit_control_menu($controls, $course, $section);
        } else if ((!$onsectionpage) && (!$sectionishidden)) {
            if (empty($this->tcsettings)) {
                $this->tcsettings = $this->courseformat->get_settings();
            }
            $url = new moodle_url('/course/view.php', array('id' => $course->id, 'section' => $section->section));
            // Get the specific words from the language files.
            $topictext = null;
            if (($this->tcsettings['layoutstructure'] == 1) || ($this->tcsettings['layoutstructure'] == 4)) {
                $topictext = get_string('setlayoutstructuretopic', 'format_topcoll');
            } else if (($this->tcsettings['layoutstructure'] == 2) || ($this->tcsettings['layoutstructure'] == 3)) {
                $topictext = get_string('setlayoutstructureweek', 'format_topcoll');
            } else {
                $topictext = get_string('setlayoutstructureday', 'format_topcoll');
            }
            if ($this->tcsettings['viewsinglesectionenabled'] == 2) {
                $title = get_string('viewonly', 'format_topcoll', array('sectionname' => $topictext.' '.$section->section));
                switch ($this->tcsettings['layoutelement']) { // Toggle section x.
                    case 1:
                    case 3:
                    case 5:
                    case 8:
                        $o .= html_writer::link($url,
                                $topictext.html_writer::empty_tag('br').
                                $section->section, array('title' => $title, 'class' => 'cps_centre'));
                        break;
                    default:
                        $o .= html_writer::link($url,
                                $this->output->pix_icon('one_section', $title, 'format_topcoll'),
                                array('title' => $title, 'class' => 'cps_centre'));
                        break;
                }
            }
        }

        return $o;
    }

}
