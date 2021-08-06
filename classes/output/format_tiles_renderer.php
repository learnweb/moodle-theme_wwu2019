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
 * Renderer for outputting the tiles course format.
 *
 * @package format_tiles
 * @copyright 2018 David Watson {@link http://evolutioncode.uk}
 * @copyright Based partly on previous topics format renderer and general course format renderer
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.7
 */

namespace theme_wwu2019\output;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/course/format/tiles/renderer.php');

/**
 * Basic renderer for tiles format.
 * @package format_tiles
 * @copyright 2016 David Watson {@link http://evolutioncode.uk}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_tiles_renderer extends \format_tiles_renderer {
    use wwu_format_trait;

    /**
     * Generate the display of the header part of a section before
     * course modules are included
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @param bool $onsectionpage true if being printed on a single-section page
     * @param int $sectionreturn The section to return to after an action
     * @return string HTML to output.
     */
    protected function section_header($section, $course, $onsectionpage, $sectionreturn = null) {
        return $this->wwu_section_header($section, $course, $onsectionpage);
    }

    /**
     * Generate a summary of a section for display on the 'course index page'
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @param array    $mods (argument not used)
     * @return string HTML to output.
     */
    protected function section_summary($section, $course, $mods) {
        return $this->wwu_section_summary($section, $course, $mods);
    }

    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        $templateable = new \format_tiles\output\course_output($course, false, $displaysection, $this->courserenderer);
        $data = $templateable->export_for_template($this);
        echo $this->render_from_template('format_tiles/single_section_page', $data);
    }

    /**
     * Output the html for a multiple section page
     * i.e. what the users see when they first enter a course with all tiles shown
     *
     * @param \stdClass $course The course entry from DB
     * @param array $sections (argument not used)
     * @param array $mods (argument not used)
     * @param array $modnames (argument not used)
     * @param array $modnamesused (argument not used)
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \moodle_exception
     */
    public function print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused) {
        $templateable = new \format_tiles\output\course_output($course, false, 0, $this->courserenderer);
        $templateable->courseformatoptions['courseshowtileprogress'] = true;
        $data = $templateable->export_for_template($this);
        $progress = [];
        $defaultsectionpre = get_string('sectionname', 'format_tiles');
        $unnamedtiles = false;
        foreach ($data['tiles'] as $tile) {
            $defaulttiletitle = htmlspecialchars_decode($defaultsectionpre . $tile['tileid']);
            if ($defaulttiletitle != $tile['title']) {
                $unnamedtiles = true;
                break;
            }
        }
        $modinfo = get_fast_modinfo($course);
        $sec0data = $modinfo->get_section_info(0);
        $data["section_zero"]["name"] = is_null($sec0data->name) ?
            get_string("format_tiles_generalsec0name", "theme_wwu2019") : $sec0data->name;
        $data['unnamedtiles'] = $unnamedtiles;
        echo $this->render_from_template('format_tiles/multi_section_page', $data);
    }
}
