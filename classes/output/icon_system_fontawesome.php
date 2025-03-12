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

/**
 * Additional Icons for WWU2019
 *
 * @package     theme_wwu2019
 * @copyright   2020 Justus Dieckmann WWU
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class icon_system_fontawesome extends \core\output\icon_system_fontawesome {

    /**
     * @var array $map Cached map of moodle icon names to font awesome icon names.
     */
    private $map = [];

    /**
     * Mapping of moodle icon names to fontawesome icon names.
     * @return array the map
     */
    public function get_core_icon_map() {
        $iconmap = parent::get_core_icon_map();

        $override = [
            'core:i/briefcase' => 'fa-solid fa-briefcase',
            'core:i/cogs' => 'fa-solid fa-cogs',
            'core:i/graduation-cap' => 'fa-solid fa-graduation-cap',
            'core:i/navigationbranch' => 'fa-solid fa-wrench',
            'core:i/navigationitem' => 'fa-solid fa-angle-double-right',
            'core:i/book' => 'fa-solid fa-book',
            'core:i/trophy' => 'fa-solid fa-trophy',
            'core:i/key' => 'fa-solid fa-key',
            'core:i/comment' => 'fa-solid fa-comment',
            'core:i/rss-square' => 'fa-solid fa-rss-square',
            'core:i/hidden' => 'fa-solid fa-eye-slash',
            'core:i/list' => 'fa-solid fa-list',
            'core:i/logout' => 'fa-solid fa-sign-out',
            'core:i/help' => 'fa-solid fa-question-circle',
            'core:i/hamburger' => 'fa-solid fa-bars',
            'core:i/sun' => 'fa-solid fa-sun-o',
            'core:i/moon' => 'fa-solid fa-moon-o',
            'core:i/magic' => 'fa-solid fa-magic',
            'core:i/theme' => 'fa-solid fa-paint-brush',
        ];

        return array_merge($iconmap, $override);
    }

    /**
     * Overridable function to get a mapping of all icons.
     * Default is to do no mapping.
     */
    public function get_icon_name_map() {
        if ($this->map === []) {
            $cache = \cache::make('theme_wwu2019', 'fontawesomeiconmapping');

            $this->map = $cache->get('mapping');

            if (empty($this->map)) {
                $this->map = $this->get_core_icon_map();
                $callback = 'get_fontawesome_icon_map';

                if ($pluginsfunction = get_plugins_with_function($callback)) {
                    foreach ($pluginsfunction as $plugintype => $plugins) {
                        foreach ($plugins as $pluginfunction) {
                            $pluginmap = $pluginfunction();
                            $this->map += $pluginmap;
                        }
                    }
                }
                $cache->set('mapping', $this->map);
            }

        }
        return $this->map;
    }

}

