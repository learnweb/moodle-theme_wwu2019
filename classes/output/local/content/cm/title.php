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
 * Activity title override.
 *
 * This class is usually rendered inside the cmname inplace editable.
 *
 * @package   theme_wwu2019
 * @copyright 2023 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_wwu2019\output\local\content\cm;

/**
 * Activity title override.
 *
 * This class is usually rendered inside the cmname inplace editable.
 *
 * @package   theme_wwu2019
 * @copyright 2023 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class title extends \core_courseformat\output\local\content\cm\title {

    /**
     * Return the title template data to be used inside the inplace editable.
     *
     */
    protected function get_title_displayvalue(): string {
        global $CFG;

        $returnvalue = parent::get_title_displayvalue();

        if (file_exists($CFG->dirroot . '/filter/wwwsso/filter.php')) {
            require_once($CFG->dirroot . '/filter/wwwsso/filter.php');
            $returnvalue = filter_wwwsso::staticFilter($returnvalue);
        }

        return $returnvalue;
    }

}
