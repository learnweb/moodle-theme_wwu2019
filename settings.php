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
 * WWU2019 theme settings file.
 *
 * @package    theme_wwu2019
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $settings = new theme_boost_admin_settingspage_tabs('themesettingwwu2019',
            get_string('pluginname', 'theme_wwu2019'));
    $page = new admin_settingpage('theme_classic_wwu2019', get_string('generalsettings', 'theme_boost'));

    $setting = new admin_setting_configtext('theme_wwu2019/helpurl',
            get_string('helpurl', 'theme_wwu2019'),
            get_string('helpurl_desc', 'theme_wwu2019'), '', PARAM_URL);

    $page->add($setting);

    $settings->add($page);
}
