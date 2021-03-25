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
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        $templateable = new \format_tiles\output\course_output($course, false, $displaysection, $this->courserenderer);
        $data = $templateable->export_for_template($this);
        echo $this->render_from_template('format_tiles/single_section_page', $data);
    }

    /**
     * Output the html for a multiple section page
     * i.e. what the users see when they first enter a course with all tiles shown
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections (argument not used)
     * @param array $mods (argument not used)
     * @param array $modnames (argument not used)
     * @param array $modnamesused (argument not used)
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused) {
        $templateable = new \format_tiles\output\course_output($course, false, 0, $this->courserenderer);
        $templateable->courseformatoptions['courseshowtileprogress'] = true;
        $data = $templateable->export_for_template($this);
        $progress = [];
        foreach ($data['tiles'] as $tile) {
            if (array_key_exists('progress', $tile) && !empty($tile) and
                $tile['progress'] and $tile['progress']['numOutOf'] > 0) {
                $progresstileinfo = new \stdClass();
                $progresstileinfo->name = $tile['title'];
                $progresstileinfo->value =
                    $tile['progress']['numComplete'] . " / " . $tile['progress']['numOutOf'];
                $progresstileinfo->percentage =
                    $tile['progress']['numComplete'] * 100 / $tile['progress']['numOutOf'];
                array_push($progress, $progresstileinfo);
            }
        }
        $modinfo = get_fast_modinfo($course);
        $sec0data = $modinfo->get_section_info(0);
        $data["section_zero"]["name"] = is_null($sec0data->name) ?
            get_string("format_tiles_generalsec0name", "theme_wwu2019") : $sec0data->name;
        $data["progress-sections"] = $progress;
        echo $this->render_from_template('format_tiles/multi_section_page', $data);
    }
}
