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

    // Matomo.
    $setting = new admin_setting_configtext('theme_wwu2019/matomo_siteurl',
        get_string('matomo_siteurl', 'theme_wwu2019'),
        get_string('matomo_siteurl_desc', 'theme_wwu2019'), '');
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_wwu2019/matomo_siteid',
        get_string('matomo_siteid', 'theme_wwu2019'),
        get_string('matomo_siteid_desc', 'theme_wwu2019'), '');
    $page->add($setting);

    // Alerts.
    $setting = new admin_setting_configcheckbox('theme_wwu2019/enable1alert',
        get_string('enable1alert', 'theme_wwu2019'),
        get_string('enablealert_desc', 'theme_wwu2019'), false, true, false);
    $page->add($setting);

    $alertinfo = get_string('alert_info', 'theme_wwu2019');
    $alertwarning = get_string('alert_warning', 'theme_essential');
    $alertgeneral = get_string('alert_general', 'theme_wwu2019');
    $alerttypedefault = 'info';
    $alerttypechoices = array('info' => $alertinfo, 'error' => $alertwarning, 'success' => $alertgeneral);
    $setting = new essential_admin_setting_configselect('theme_wwu2019/alert1type',
        get_string('alert1type', 'theme_wwu2019'),
        get_string('alerttype_desc', 'theme_wwu2019'),
        $alerttypedefault, $alerttypechoices);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_wwu2019/alert1title',
        get_string('alert1title', 'theme_wwu2019'),
        get_string('alerttitle_desc', 'theme_wwu2019'), '');
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_wwu2019/alert1text',
        get_string('alert1text', 'theme_wwu2019'),
        get_string('alerttext_desc', 'theme_wwu2019'), '');
    $page->add($setting);

    $setting = new admin_setting_configcheckbox('theme_wwu2019/enable2alert',
        get_string('enable2alert', 'theme_wwu2019'),
        get_string('enablealert_desc', 'theme_wwu2019'), false, true, false);
    $page->add($setting);

    $setting = new essential_admin_setting_configselect('theme_wwu2019/alert2type',
        get_string('alert2type', 'theme_wwu2019'),
        get_string('alerttype_desc', 'theme_wwu2019'),
        $alerttypedefault, $alerttypechoices);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_wwu2019/alert2title',
        get_string('alert2title', 'theme_wwu2019'),
        get_string('alerttitle_desc', 'theme_wwu2019'), '');
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_wwu2019/alert2text',
        get_string('alert2text', 'theme_wwu2019'),
        get_string('alerttext_desc', 'theme_wwu2019'), '');
    $page->add($setting);

    $setting = new admin_setting_configcheckbox('theme_wwu2019/enable3alert',
        get_string('enable2alert', 'theme_wwu2019'),
        get_string('enablealert_desc', 'theme_wwu2019'), false, true, false);
    $page->add($setting);

    $setting = new essential_admin_setting_configselect('theme_wwu2019/alert3type',
        get_string('alert3type', 'theme_wwu2019'),
        get_string('alerttype_desc', 'theme_wwu2019'),
        $alerttypedefault, $alerttypechoices);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_wwu2019/alert3title',
        get_string('alert3title', 'theme_wwu2019'),
        get_string('alerttitle_desc', 'theme_wwu2019'), '');
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_wwu2019/alert3text',
        get_string('alert3text', 'theme_wwu2019'),
        get_string('alerttext_desc', 'theme_wwu2019'), '');
    $page->add($setting);

    $settings->add($page);

    // Marketing spot settings.
    $essentialsettingsfrontpage->add(new admin_setting_heading('theme_wwu2019_marketing',
        get_string('marketingheading', 'theme_wwu2019'),
        format_text(get_string('marketingdesc', 'theme_wwu2019'), FORMAT_MARKDOWN)));

    foreach (range(1, 3) as $marketingspotnumber) {
        // This is the descriptor for Marketing Spot in $marketingspotnumber.
        $name = 'theme_wwu2019/marketing' . $marketingspotnumber . 'info';
        $heading = get_string('marketing' . $marketingspotnumber, 'theme_wwu2019');
        $information = get_string('marketinginfodesc', 'theme_wwu2019');
        $setting = new admin_setting_heading($name, $heading, $information);
        $essentialsettingsfrontpage->add($setting);

        // Marketing spot.
        $name = 'theme_wwu2019/marketing' . $marketingspotnumber;
        $title = get_string('marketingtitle', 'theme_wwu2019');
        $description = get_string('marketingtitledesc', 'theme_wwu2019');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $essentialsettingsfrontpage->add($setting);

        $name = 'theme_wwu2019/marketing' . $marketingspotnumber . 'content';
        $title = get_string('marketingcontent', 'theme_wwu2019');
        $description = get_string('marketingcontentdesc', 'theme_wwu2019');
        $default = '';
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $essentialsettingsfrontpage->add($setting);
    }
}
