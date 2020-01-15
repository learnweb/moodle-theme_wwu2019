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
 * Additional Icons for WWU2019
 *
 * @package     theme_wwu2019
 * @copyright   2020 Justus Dieckmann WWU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_wwu2019\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Additional Icons for WWU2019
 *
 * @package     theme_wwu2019
 * @copyright   2020 Justus Dieckmann WWU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class icon_system_fontawesome extends \core\output\icon_system_fontawesome {

    /**
     * Mapping of moodle icon names to fontawesome icon names.
     * @return array the map
     */
    public function get_core_icon_map() {
        $iconmap = parent::get_core_icon_map();

        $override = array(
                'core:i/briefcase' => 'fa-briefcase',
                'core:i/cogs' => 'fa-cogs',
                'core:i/graduation-cap' => 'fa-graduation-cap',
                'core:i/navigationitem' => 'fa-wrench'
        );

        return array_merge($iconmap, $override);
    }

}

