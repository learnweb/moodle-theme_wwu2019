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
 * Renderer for WWU 2019 Theme
 *
 * @package    theme_wwu2019
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_wwu2019\output;

use action_link;
use context_course;
use moodle_page;
use moodle_url;
use navigation_node;
use navigation_node_collection;
use pix_icon;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderer for WWU 2019 Theme
 *
 * @package    theme_wwu2019
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \core_renderer {

    /**
     * core_renderer constructor.
     * Overrides parent to require admin tree init in $PAGE->settingsnav
     *
     * @param moodle_page $page the page we are doing output for.
     * @param string $target one of rendering target constants.
     */
    public function __construct(moodle_page $page, $target) {
        parent::__construct($page, $target);
        navigation_node::require_admin_tree();
    }

    /**
     * Renders logo heading.
     * @return string HTML string.
     */
    public function logo_header() {
        global $CFG;

        $templatecontext = [
                'wwwroot' => $CFG->wwwroot,
                'logo' => $CFG->wwwroot . '/theme/wwu2019/pix/learnweb_logo.svg'
        ];
        return $this->render_from_template('theme_wwu2019/logo_header', $templatecontext);
    }

    /**
     * Renders main menu.
     * @return string HTML string.
     */
    public function main_menu() {
        global $CFG, $USER;

        $mainmenu = [];

        // Add MyCourses menu.
        if (count($courses = $this->get_courses())) {
            $mainmenu[] = [
                    'name' => get_string('mycourses', 'theme_wwu2019'),
                    'hasmenu' => true,
                    'menu' => $this->add_breakers($courses),
                    'icon' => (new pix_icon('i/graduation-cap', ''))->export_for_pix()
            ];
        }

        // Add This Course menu.
        if (count($thiscourse = $this->get_activity_menu())) {
            $mainmenu[] = [
                    'name' => get_string('thiscourse', 'theme_wwu2019'),
                    'hasmenu' => true,
                    'menu' => $this->add_breakers($thiscourse),
                    'icon' => (new pix_icon('i/book', ''))->export_for_pix()
            ];
        }

        // Add Administration menu.
        if (count($settings = $this->settingsnav_for_template($this->page->settingsnav->children))) {
            $mainmenu[] = [
                    'name' => get_string('pluginname', 'block_settings'),
                    'hasmenu' => true,
                    'menu' => $this->add_breakers($settings),
                    'icon' => (new pix_icon('i/cogs', ''))->export_for_pix()
            ];
        }

        // Add dashboard.
        $mainmenu[] = [
                'name' => get_string('dashboard', 'theme_wwu2019'),
                'hasmenu' => false,
                'menu' => null,
                'href' => $CFG->wwwroot . '/my/',
        ];

        $templatecontext = [
                'left-menu' => $mainmenu,
                'login' => $this->get_login(),
                'user-menu' => $this->get_user_menu(),
                'langs' => $this->get_languages(),
                'isadmin' => has_capability('moodle/site:config', \context_system::instance()),
                'right-menu-icons' => [
                ],
                'navbaradditions' => $this->navbar_plugin_output(),
                'wwwroot' => $CFG->wwwroot,
        ];
        $this->page->requires->js_call_amd('theme_wwu2019/menu', 'init');
        return $this->render_from_template('theme_wwu2019/menu', $templatecontext);
    }
    /**
     * Add additional items to edit and to go to bottom of page.
     * Allow plugins to provide some content to be rendered in the navbar.
     * The plugin must define a PLUGIN_render_navbar_output function that returns
     * the HTML they wish to add to the navbar.
     *
     * @return string HTML for the navbar
     */
    public function navbar_plugin_output() {
        $output = '';
        if ($this->page->user_allowed_editing()) {
            $output .= $this->add_edit_button();
        }
        if (!$this->is_login_page()) {
            $output .= $this->add_endofpage_button();
        }
        $output .= parent::navbar_plugin_output();

        return $output;
    }

    /**
     * Returns HTML to display a "Turn editing on/off" button in a form.
     *
     * @param moodle_url $url The URL + params to send through when clicking the button
     * @return string HTML the button
     */
    public function edit_button(moodle_url $url) {
        $url->param('sesskey', sesskey());
        $class = '';
        if ($this->page->user_is_editing()) {
            $url->param('edit', 'off');
            $editstring = get_string('turneditingoff');
            $class = 'red-edit-button';
        } else {
            $url->param('edit', 'on');
            $editstring = get_string('turneditingon');
        }

        return $this->single_button($url, $editstring, 'post', array('class' => 'singlebutton ' . $class));
    }

    /**
     * Renders an edit button.
     * @return string HTML for editbutton
     */
    private function add_edit_button() {
        $html = '';
        $url = $this->get_edit_button_url_by_pagetype();
        $url->param('sesskey', sesskey());

        if ($this->page->user_is_editing()) {
            $icon = 'power-off';
        } else {
            $icon = 'edit';
        }
        $attributes['class'] = 'icon fa fa-' . $icon;
        $attributes['aria-hidden'] = 'true';
        $attributes['href'] = $url;
        $tag = \html_writer::tag('a', '', $attributes);
        $html .= \html_writer::div($tag, 'nav-link');
        return $html;
    }

    /**
     * Checks the current pagetype and sets the edit param based on the pagetype.
     * TODO: n_herr03 Not all cases of the switch statement are tested. Moreover, there might exist pages that are currently ...
     * TODO cont. ... handeled by the default case but would require other url parameters.
     * @return object url with the required edit parameter
     */
    private function get_edit_button_url_by_pagetype() {
        $pagetype = $this->page->pagetype;
        if (strpos($pagetype, 'admin-setting') !== false) {
            $pagetype = 'admin-setting'; // Deal with all setting page types.
        } else if ((strpos($pagetype, 'mod-') !== false) &&
            ((strpos($pagetype, 'edit') !== false) ||
                (strpos($pagetype, 'view') !== false) ||
                (strpos($pagetype, '-mod') !== false))) {
            $pagetype = 'mod-edit-view'; // Deal with all mod edit / view / -mod page types.
        } else if (strpos($pagetype, 'mod-data-field') !== false) {
            $pagetype = 'mod-data-field'; // Deal with all mod data field page types.
        } else if (strpos($pagetype, 'mod-lesson') !== false) {
            $pagetype = 'mod-lesson'; // Deal with all mod lesson page types.
        }
        switch ($pagetype) {
            case 'site-index':
            case 'calendar-view':  // Slightly faulty as even the navigation link goes back to the frontpage.  TODO: MDL.
                $url = new moodle_url('/course/view.php');
                $url->param('id', 1);
                if ($this->page->user_is_editing()) {
                    $url->param('edit', 'off');
                } else {
                    $url->param('edit', 'on');
                }
                break;
            case 'admin-index':
            case 'admin-search':
            case 'admin-setting':
                $url = $this->page->url;
                if ($this->page->user_is_editing()) {
                    $url->param('adminedit', 0);
                } else {
                    $url->param('adminedit', 1);
                }
                break;
            case 'course-index':
            case 'course-management':
            case 'course-search':
            case 'mod-resource-mod':
            case 'tag-search':
                $url = new moodle_url('/tag/search.php');
                if ($this->page->user_is_editing()) {
                    $url->param('edit', 'off');
                } else {
                    $url->param('edit', 'on');
                }
                break;
            case 'mod-data-field':
            case 'mod-edit-view':
            case 'mod-forum-discuss':
            case 'mod-forum-index':
            case 'mod-forum-search':
            case 'mod-forum-subscribers':
            case 'mod-lesson':
            case 'mod-quiz-index':
            case 'mod-scorm-player':
                $url = new moodle_url('/course/view.php');
                $url->param('id', $this->page->course->id);
                $url->param('return', $this->page->url->out_as_local_url(false));
                if ($this->page->user_is_editing()) {
                    $url->param('edit', 'off');
                } else {
                    $url->param('edit', 'on');
                }
                break;
            case 'my-index':
            case 'user-profile':
                $url = $this->page->url;
                // Umm! Both /user/profile.php and /user/profilesys.php have the same page type but different parameters!
                if ($this->page->user_is_editing()) {
                    $url->param('adminedit', 0);
                    $url->param('edit', 0);
                } else {
                    $url->param('adminedit', 1);
                    $url->param('edit', 1);
                }
                break;
            default:
                $url = $this->page->url;
                if ($this->page->user_is_editing()) {
                    $url->param('edit', 'off');
                } else {
                    $url->param('edit', 'on');
                }
                break;
        }
        return $url;
    }

    /**
     * Adds a end_of_page button.
     * @return string html for endofpage button
     */
    private function add_endofpage_button() {
        $html = '';
        $attributes['class'] = 'icon fa fa-arrow-circle-o-down';
        $attributes['aria-hidden'] = 'true';
        $attributes['href'] = new moodle_url('#wwu-footer');
        $tag = \html_writer::tag('a', '', $attributes);
        $html .= \html_writer::div($tag, 'nav-link');

        return $html;
    }

    /**
     * Puts the given $nodecollection into a format properly usable in templates.
     *
     * @param \navigation_node_collection $nodecollection
     * @return array the array usable in templates.
     */
    private function settingsnav_for_template(\navigation_node_collection $nodecollection) {
        $items = [];
        $navbranchicon = (new pix_icon('i/navigationbranch', ''))->export_for_pix();
        $navitemicon = (new pix_icon('i/navigationitem', ''))->export_for_pix();
        foreach ($nodecollection as $node) {
            if ($node->display) {

                $templateformat = array(
                        'name' => $node->get_content(),
                        'class' => ''
                );

                if ($node->icon && !$node->hideicon) {
                    if ($node->icon->pix == 'i/navigationitem' && $node->has_children()) {
                        $templateformat['icon'] = $navbranchicon;
                    } else {
                        $templateformat['icon'] = $node->icon->export_for_pix();
                    }
                } else {
                    $templateformat['icon'] = $navitemicon;
                }

                if ($node->has_children()) {
                    $templateformat['hasmenu'] = true;

                    $templateformat['isexpanded'] = $node->forceopen;
                    if ($node->forceopen) {
                        $templateformat['class'] = 'open';
                    }
                    if (!$node->has_action()) {
                        $children = $node->children;
                    } else {
                        // Create artificial first element in submenu that duplicates the parent's action.
                        // We have to rewrite the entire collection because generally there is no way to prepend an item to the collection.
                        // That is, unless we know the first child's key, which does not always exist in any submenu.
                        $children = new navigation_node_collection();
                        $duplicatedaction = new navigation_node([
                            'text' => $node->text,
                            'action' => $node->action,
                            'parent' => $node,
                        ]);
                        $children->add($duplicatedaction);
                        foreach ($node->children as $c) {
                            $children->add($c);
                        }
                    }
                    $templateformat['menu'] = $this->settingsnav_for_template($children);
                } else {
                    $templateformat['hasmenu'] = false;
                    $templateformat['menu'] = null;
                }
                if ($node->has_action() && !$node->has_children()) {
                    if (is_string($node->action)) {
                        $templateformat['href'] = $node->action;
                    } else if ($node->action instanceof moodle_url) {
                        $templateformat['href'] = $node->action->out(false);
                    } else if ($node->action instanceof action_link) {
                        $templateformat['href'] = $node->action->url->out(false);
                    }
                }
                $items[] = $templateformat;
            }
        }
        return $items;
    }

    /**
     * Adds breakers for submenu items, which causes the items to be divided equally in two or three column menus.
     * @param array $menuitems
     * @return array The menuitems with breakers.
     */
    private function add_breakers(array $menuitems) {
        if (count($menuitems) == 0) {
            return array();
        }
        $columntwo = intval(ceil(count($menuitems) / 2.0) - 1);
        $columnthree1 = intval(ceil(count($menuitems) / 3.0) - 1);
        $columnthree2 = intval(min(ceil(count($menuitems) / 3.0) * 2 - 1, count($menuitems) - 1));
        $menuitems[$columntwo]['breaker'] = ['c2'];
        if (array_key_exists('breaker', $menuitems[$columnthree1])) {
            $menuitems[$columnthree1]['breaker'][] = 'c3';
        } else {
            $menuitems[$columnthree1]['breaker'] = ['c3'];
        }
        if (array_key_exists('breaker', $menuitems[$columnthree2])) {
            $menuitems[$columnthree2]['breaker'][] = 'c3';
        } else {
            $menuitems[$columnthree2]['breaker'] = ['c3'];
        }

        return $menuitems;
    }

    /**
     * Gets and sorts all of the user's courses into terms based on a customfield.
     * @return array The sorted courses, ready for use in templates.
     */
    private function get_courses() {
        global $DB;
        $courseicon = (new pix_icon('i/graduation-cap', ''))->export_for_pix();
        $hiddencourseicon = (new pix_icon('i/hidden', ''))->export_for_pix();
        $terms = [];

        // Create an array where the key points to the string representation of the customfield value.
        if (($field = $DB->get_record('customfield_field', array('name' => 'Semester', 'type' => 'semester')))
                && class_exists('customfield_semester\data_controller')) {

            $courseswithsemester = $this->get_courses_with_semester($field->id);

            // Render each course.
            foreach ($courseswithsemester as $course) {
                if (!$course->visible &&
                    !has_capability('moodle/course:viewhiddencourses', context_course::instance($course->id))) {
                    continue;
                }
                $termid = intval($course->value);

                if (!array_key_exists($termid, $terms)) {
                    $terms[$termid] = $this->create_term($termid);
                }
                $terms[$termid]['menu'][] = [
                    'name' => $course->visible ? $course->shortname : '<i>' . htmlentities($course->shortname) . '</i>',
                    'dontescape' => !$course->visible,
                    'href' => (new moodle_url('/course/view.php', array('id' => $course->id)))->out(false),
                    'icon' => $course->visible ? $courseicon : $hiddencourseicon,
                    'class' => $course->visible ? '' : 'dimmed_text',
                    'hasmenu' => false,
                    'menu' => null
                ];
            }
        } else {
            $courses = enrol_get_my_courses();
            foreach ($courses as $course) {
                $terms['Kurse']['menu'][] = [
                    'name' => $course->visible ? $course->shortname : '<i>' . htmlentities($course->shortname) . '</i>',
                    'dontescape' => !$course->visible,
                    'href' => (new moodle_url('/course/view.php', array('id' => $course->id)))->out(false),
                    'icon' => $course->visible ? $courseicon : $hiddencourseicon,
                    'class' => $course->visible ? '' : 'dimmed_text',
                    'hasmenu' => false,
                    'menu' => null
                ];
            }
        }
        return array_values($terms);
    }

    /**
     * Creates the entry for one entry of the navigation.
     * @param int $termid the termid as given by the customfield_semester plugin.
     * @return array
     */
    private function create_term($termid) {
        $calendaricon = (new pix_icon('i/calendar', ''))->export_for_pix();

        $name = \customfield_semester\data_controller::get_name_for_semester($termid);
        return [
            'name' => $name,
            'icon' => $calendaricon,
            'hasmenu' => true,
            'menu' => []
        ];
    }


    /**
     * Get all courses the user is enrolled with the customfield defining the semester.
     * @param int $fieldid
     * @return array
     */
    private function get_courses_with_semester($fieldid) {
        global $DB;
        // Remark: The function always returns the basefields.
        $courses = enrol_get_my_courses();
        // Transform the ids of all enrolled courses to an string to use in the in-sql clause.
        $courseids = array_keys($courses);
        if (count($courseids) === 0) {
            return [];
        }

        list ($instring, $params) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);

        // Get for each course where the user is enrolled the customfield value (here encoded as number).
        $fromtable = 'SELECT cs.id,cs.visible,cd.value,cs.shortname
                                FROM mdl_course as cs
                                INNER JOIN mdl_customfield_data as cd ON cs.id=cd.instanceid
                                WHERE cs.id ' . $instring . '
                                AND cd.fieldid = :fieldid
                                ORDER BY cd.intvalue DESC, cs.shortname ASC';
        $params['fieldid'] = $fieldid;
        return $DB->get_records_sql($fromtable, $params);
    }

    /**
     * Returns all activity types of a course.
     * @return array The activity types, ready for use in templates.
     */
    private function get_activity_menu() {
        $activities = [];
        if (!isguestuser()) {
            if (in_array($this->page->pagelayout, array('course', 'incourse', 'report', 'admin', 'standard')) &&
                    (!empty($this->page->course->id) && $this->page->course->id > 1)) {

                $activities[] = [
                        'name' => get_string('participants'),
                        'icon' => (new pix_icon('i/users', ''))->export_for_pix(),
                        'hasmenu' => false,
                        'menu' => null,
                        'href' => (new moodle_url('/user/index.php', array('id' => $this->page->course->id)))->out(false)
                ];

                $context = context_course::instance($this->page->course->id);
                if (((has_capability('gradereport/overview:view', $context) ||
                                        has_capability('gradereport/user:view', $context)) &&
                                $this->page->course->showgrades) || has_capability('gradereport/grader:view', $context)) {
                    $activities[] = [
                            'name' => get_string('grades'),
                            'icon' => (new pix_icon('i/grades', ''))->export_for_pix(),
                            'hasmenu' => false,
                            'menu' => null,
                            'href' => (new moodle_url('/grade/report/index.php', array('id' => $this->page->course->id)))->out(false)
                    ];
                }
                $activities[] = [
                        'name' => get_string('badgesview', 'badges'),
                        'icon' => (new pix_icon('i/trophy', ''))->export_for_pix(),
                        'hasmenu' => false,
                        'menu' => null,
                        'href' => (new moodle_url('/badges/view.php', array('id' => $this->page->course->id, 'type' => 2)))->out(false)
                ];

                $data = $this->get_course_activities();
                foreach ($data as $modname => $modfullname) {
                    if ($modname === 'resources') {
                        $activities[] = [
                                'name' => $modfullname,
                                'icon' => (new pix_icon('icon', '', 'mod_page'))->export_for_pix(),
                                'hasmenu' => false,
                                'menu' => null,
                                'href' => (new moodle_url('/course/resources.php', array('id' => $this->page->course->id)))->out(false)
                        ];
                    } else {
                        $activities[] = [
                                'name' => $modfullname,
                                'icon' => (new pix_icon('icon', '', $modname))->export_for_pix(),
                                'hasmenu' => false,
                                'menu' => null,
                                'href' => (new moodle_url("/mod/$modname/index.php", array('id' => $this->page->course->id)))->out(false)
                        ];
                    }
                }
            }
        }
        return $activities;
    }

    /**
     * Collections information about the course's activities.
     * @return array in format $modname => $modfullname
     */
    private function get_course_activities() {
        // A copy of block_activity_modules.
        $course = $this->page->course;
        $modinfo = get_fast_modinfo($course);
        $course = \course_get_format($course)->get_course();
        $modfullnames = array();
        $archetypes = array();

        foreach ($modinfo->get_section_info_all() as $section => $thissection) {
            if (((!empty($course->numsections)) and ($section > $course->numsections)) or (empty($modinfo->sections[$section]))) {
                // This is a stealth section or is empty.
                continue;
            }
            foreach ($modinfo->sections[$thissection->section] as $modnumber) {
                $cm = $modinfo->cms[$modnumber];
                // Exclude activities which are not visible or have no link (=label).
                if (!$cm->uservisible or !$cm->has_view()) {
                    continue;
                }
                if (array_key_exists($cm->modname, $modfullnames)) {
                    continue;
                }
                if (!array_key_exists($cm->modname, $archetypes)) {
                    $archetypes[$cm->modname] = plugin_supports('mod', $cm->modname, FEATURE_MOD_ARCHETYPE, MOD_ARCHETYPE_OTHER);
                }
                if ($archetypes[$cm->modname] == MOD_ARCHETYPE_RESOURCE) {
                    if (!array_key_exists('resources', $modfullnames)) {
                        $modfullnames['resources'] = get_string('resources');
                    }
                } else {
                    $modfullnames[$cm->modname] = $cm->modplural;
                }
            }
        }
        \core_collator::asort($modfullnames);

        return $modfullnames;
    }

    /**
     * Returns Array for displaying User-Sub-Menu in Template
     * @return array|false
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    private function get_user_menu() {
        global $USER, $CFG, $DB;

        if (!isloggedin()) {
            return false;
        }

        $menucontent = [];

        $course = $this->page->course;
        $context = context_course::instance($course->id);

        // Create Roleswitcher.
        $rolemenuitem = null;
        $rolename = null;
        if (\is_role_switched($course->id)) { // Has switched roles.
            if ($role = $DB->get_record('role', array('id' => $USER->access['rsw'][$context->path]))) {
                $rolemenuitem = [
                        'name' => get_string('switchrolereturn'),
                        'hasmenu' => false,
                        'menu' => null,
                        'href' => (new moodle_url('/course/switchrole.php', array('id' => $course->id, 'sesskey' => sesskey(),
                                'switchrole' => 0, 'returnurl' => $this->page->url->out_as_local_url(false))))->out(false),
                        'icon' => (new pix_icon('i/user', ''))->export_for_pix()
                ];
                $rolename = ' - '.role_get_name($role, $context);
            }
        } else {
            $roles = \get_switchable_roles($context);
            if (is_array($roles) && (count($roles) > 0)) {
                $rolemenuitem = [
                        'name' => get_string('switchroleto'),
                        'hasmenu' => false,
                        'menu' => null,
                        'href' => (new moodle_url('/course/switchrole.php', array('id' => $course->id,
                                'switchrole' => -1, 'returnurl' => $this->page->url->out_as_local_url(false))))->out(false),
                        'icon' => (new pix_icon('i/users', ''))->export_for_pix()
                ];
            }
        }

        // Link Profile page.
        if (\core\session\manager::is_loggedinas()) {
            $realuser = \core\session\manager::get_realuser();
            $menucontent[] = [
                    'name' => get_string('loggedinas', 'theme_wwu2019',
                            array('real' => fullname($realuser, true), 'fake' => fullname($USER, true))) .
                            ($rolename ? $rolename : ''),
                    'hasmenu' => false,
                    'menu' => null,
                    'href' => (new moodle_url('/user/profile.php', array('id' => $USER->id)))->out(false),
                    'icon' => (new pix_icon('i/key', ''))->export_for_pix()
            ];
        } else {
            $menucontent[] = [
                    'name' => fullname($USER, true) . ($rolename ? $rolename : ''),
                    'hasmenu' => false,
                    'menu' => null,
                    'href' => (new moodle_url('/user/profile.php', array('id' => $USER->id)))->out(false),
                    'icon' => (new pix_icon('i/user', ''))->export_for_pix()
            ];
        }

        if ($rolemenuitem) {
            $menucontent[] = $rolemenuitem;
        }

        // Preferences Submenu.
        $menucontent[] = [
                'name' => get_string('settings'),
                'hasmenu' => true,
                'menu' => $this->get_user_settings_submenu($context),
                'icon' => (new pix_icon('i/cogs', ''))->export_for_pix()
        ];

        $menucontent[count($menucontent) - 1]['class'] = 'divider';

        // Calendar.
        if (has_capability('moodle/calendar:manageownentries', $context)) {
            $menucontent[] = [
                    'name' => get_string('pluginname', 'block_calendar_month'),
                    'hasmenu' => false,
                    'menu' => null,
                    'icon' => (new pix_icon('i/calendar', ''))->export_for_pix(),
                    'href' => (new moodle_url('/calendar/view.php'))->out(false)
            ];
        }

        // Messaging.
        if (!empty($CFG->messaging)) {
            $menucontent[] = [
                    'name' => get_string('messages', 'message'),
                    'hasmenu' => false,
                    'menu' => null,
                    'icon' => (new pix_icon('i/comment', ''))->export_for_pix(),
                    'href' => (new moodle_url('/message/index.php'))->out(false)
            ];
        }

        // Files.
        if (has_capability('moodle/user:manageownfiles', $context)) {
            $menucontent[] = [
                    'name' => get_string('privatefiles', 'block_private_files'),
                    'hasmenu' => false,
                    'menu' => null,
                    'icon' => (new pix_icon('i/files', ''))->export_for_pix(),
                    'href' => (new moodle_url('/user/files.php'))->out(false)
            ];
        }

        // Forum.
        $menucontent[] = [
                'name' => get_string('forumposts', 'mod_forum'),
                'hasmenu' => false,
                'menu' => null,
                'icon' => (new pix_icon('i/log', ''))->export_for_pix(),
                'href' => (new moodle_url('/mod/forum/user.php', array('id' => $USER->id)))->out(false),
        ];

        if (has_capability('mod/forum:viewdiscussion', $context)) {
            $menucontent[] = [
                    'name' => get_string('discussions', 'mod_forum'),
                    'hasmenu' => false,
                    'menu' => null,
                    'icon' => (new pix_icon('i/list', ''))->export_for_pix(),
                    'href' => (new moodle_url('/mod/forum/user.php',
                            array('id' => $USER->id, 'mode' => 'discussions')))->out(false)
            ];
        }

        $menucontent[count($menucontent) - 1]['class'] = 'divider';

        // Grades.
        $menucontent[] = [
                'name' => get_string('mygrades', 'theme_wwu2019'),
                'hasmenu' => false,
                'menu' => null,
                'icon' => (new pix_icon('i/grades', ''))->export_for_pix(),
                'href' => (new moodle_url('/grade/report/overview/index.php',
                        array('userid' => $USER->id)))->out(false)
        ];

        // Badges.
        if (!empty($CFG->enablebadges) && has_capability('moodle/badges:manageownbadges', $context)) {
            $menucontent[] = [
                    'name' => get_string('badges'),
                    'hasmenu' => false,
                    'menu' => null,
                    'icon' => (new pix_icon('i/badge', ''))->export_for_pix(),
                    'href' => (new moodle_url('/badges/mybadges.php'))->out(false)
            ];
        }

        $menucontent[count($menucontent) - 1]['class'] = 'divider';

        // Logout.
        if (\core\session\manager::is_loggedinas()) {
            $branchurl = new moodle_url('/course/loginas.php', array('id' => $course->id, 'sesskey' => sesskey()));
        } else {
            $branchurl = new moodle_url('/login/logout.php', array('sesskey' => sesskey()));
        }
        $menucontent[] = [
                'name' => get_string('logout'),
                'hasmenu' => false,
                'menu' => null,
                'icon' => (new pix_icon('i/logout', ''))->export_for_pix(),
                'href' => $branchurl->out(false)
        ];

        // Help link.
        if ($url = get_config('theme_wwu2019', 'helpurl')) {
            $menucontent[] = [
                    'name' => get_string('help'),
                    'hasmenu' => false,
                    'menu' => null,
                    'icon' => (new pix_icon('i/help', ''))->export_for_pix(),
                    'href' => $url
            ];
        }

        $userpic = new \user_picture($USER);

        $usermenu = [
                'name' => sprintf('%.1s. %s', $USER->firstname, $USER->lastname),
                'pic' => $userpic->get_url($this->page)->out(true),
                'menu' => $this->add_breakers($menucontent)
        ];

        return $usermenu;

    }

    /**
     * Returns Array for Template that displays the settings submenu in the user menu.
     * @param \context $context context to check privileges for.
     * @return array
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    private function get_user_settings_submenu($context) {
        global $USER, $CFG;

        $menu = [];

        $menu[] = [
                'name' => get_string('user'),
                'icon' => (new pix_icon('i/user', ''))->export_for_pix(),
                'href' => (new moodle_url('/user/preferences.php', array('userid' => $USER->id)))->out(false),
                'hasmenu' => false,
        ];

        if (has_capability('moodle/user:editownprofile', $context)) {
            $menu[] = [
                    'name' => get_string('editmyprofile'),
                    'icon' => (new pix_icon('i/edit', ''))->export_for_pix(),
                    'href' => (new moodle_url('/user/edit.php', array('id' => $USER->id)))->out(false),
                    'hasmenu' => false,
            ];
        }

        if (has_capability('moodle/user:changeownpassword', $context)) {
            $menu[] = [
                    'name' => get_string('changepassword'),
                    'icon' => (new pix_icon('i/key', ''))->export_for_pix(),
                    'href' => (new moodle_url('/login/change_password.php'))->out(false),
                    'hasmenu' => false,
            ];
        }
        if (has_capability('moodle/user:editownmessageprofile', $context)) {
            $menu[] = [
                    'name' => get_string('message', 'message'),
                    'icon' => (new pix_icon('i/comment', ''))->export_for_pix(),
                    'href' => (new moodle_url('/message/edit.php', array('id' => $USER->id)))->out(false),
                    'hasmenu' => false,
            ];
        }
        if ($CFG->enableblogs) {
            $menu[] = [
                    'name' => get_string('blog', 'blog'),
                    'icon' => (new pix_icon('i/rss-square', ''))->export_for_pix(),
                    'href' => (new moodle_url('/blog/preferences.php'))->out(false),
                    'hasmenu' => false,
            ];
        }
        if ($CFG->enablebadges && has_capability('moodle/badges:manageownbadges', $context)) {
            $menu[] = [
                    'name' => get_string('badgepreferences', 'theme_wwu2019'),
                    'icon' => (new pix_icon('i/badge', ''))->export_for_pix(),
                    'href' => (new moodle_url('/badge/preferences.php'))->out(false),
                    'hasmenu' => false,
            ];
        }

        return $menu;
    }

    /**
     * Returns an array of languages to use in theme_wwu2019/menu template.
     * @return array|null
     * @throws \moodle_exception
     */
    private function get_languages() {
        global $CFG;

        $langs = get_string_manager()->get_list_of_translations();
        $templateobj = [];

        if (count($langs) < 2 || empty($CFG->langmenu) || ($this->page->course != SITEID && !empty($this->page->course->lang))) {
            return null;
        }
        foreach ($langs as $langtype => $langname) {
            $templateobj[] = [
                    'short' => $langtype,
                    'full' => $langname,
                    'href' => (new moodle_url($this->page->url, array('lang' => $langtype)))->out(false)
            ];
        }
        return $templateobj;
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        global $PAGE;

        if ($PAGE->include_region_main_settings_in_header_actions() && !$PAGE->blocks->is_block_present('settings')) {
            // Only include the region main settings if the page has requested it and it doesn't already have
            // the settings block on it. The region main settings are included in the settings block and
            // duplicating the content causes behat failures.
            $PAGE->add_header_action(\html_writer::div(
                    $this->region_main_settings_menu(),
                    'd-print-none',
                    ['id' => 'region-main-settings-menu']
            ));
        }

        $header = new \stdClass();
        $header->hasnavbar = empty($PAGE->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->contextheader = $PAGE->pagelayout === 'mypublic' ? $this->context_header() : '';
        $header->pageheadingbutton = $this->page_heading_button();
        return $this->render_from_template('theme_wwu2019/full_header', $header);
    }

    /**
     * Returns course-specific information to be output immediately above content on any course page
     * (for the current course)
     *
     * @param bool $onlyifnotcalledbefore output content only if it has not been output before
     * @return string
     */
    public function course_content_header($onlyifnotcalledbefore = false) {
        $output = parent::course_content_header($onlyifnotcalledbefore);

        if ($this->page->course->id == SITEID) {
            return $output;
        }

        // TODO Do this nicely.
        $output .= '<h1 class="page-title">' . $this->page->course->fullname . '</h1>';
        return $output;
    }

    /**
     * returns false if user is logged in, or an array of urls otherwise.
     */
    private function get_login() {
        global $CFG;

        if (isloggedin()) {
            return false;
        }

        $loginurl = get_login_url();
        $wwwhost = htmlentities(selfmsp(true));
        $ssologinurl = str_ireplace($wwwhost, 'https://sso.uni-muenster.de', $CFG->wwwroot);

        return ['url' => $loginurl, 'ssourl' => $ssologinurl];
    }

    /**
     * Provide data for the footer
     */
    public function get_footer_context() {
        global $CFG;
        $elements = [
            'wwwroot' => $CFG->wwwroot,
            'year' => userdate(time(), '%Y'),
        ];
        if (!empty($CFG->sitepolicyhandler) && $CFG->sitepolicyhandler == 'tool_policy') {
            $elements['policyurl'] = (new moodle_url('/admin/tool/policy/view.php', ['policyid' => 1]))->out();
        }
        return $elements;

    }

    /**
     * Renders the login form.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     * @throws \moodle_exception
     */
    public function render_login(\core_auth\output\login $form) {
        global $CFG;

        $context = $form->export_for_template($this);

        // Override because rendering is not supported in template yet.
        if ($CFG->rememberusername == 0) {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabledonlysession');
        } else {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
        }
        $context->errorformatted = $this->error_text($context->error);

        // Set the context variables for the mustache template.
        global $CFG, $SESSION;
        $wwwhost = htmlentities(selfmsp(true));
        $context->ssofield = (stripos($wwwhost, "www") !== false && stripos($CFG->wwwroot, $wwwhost) !== false);
        $wantsurl = empty($SESSION->wantsurl) ? $CFG->wwwroot : $SESSION->wantsurl;
        $context->ssoactionurl = str_ireplace($wwwhost, 'https://sso.uni-muenster.de', $wantsurl);
        $context->xssoactionurl = str_ireplace($wwwhost, 'https://xsso.uni-muenster.de', $wantsurl);
        // Read parameters from url, thus they can be used in form as hidden fields
        // $wantsurl can contain parameters e.g. user/view.php?id=5&course=10
        // form method needs to be 'get', because an xsso forward would drop post values.
        // within the get action of a form query string values are dropped as well.
        $params = array();
        parse_str(parse_url($wantsurl, PHP_URL_QUERY), $params);
        $paramsmustache = array();
        foreach ($params as $key => $val) {
            $paramsmustache[] = ["key" => $key, "value" => $val];
        }
        $context->ssoparams = $paramsmustache;

        return $this->render_from_template('core/loginform', $context);
    }

    /**
     * Constructs and returns the trackurl for matomo (piwik).
     * @return string
     * @throws \coding_exception
     * @throws \dml_exception
     */
    private function matomo_trackurl() {
        global $DB, $PAGE;
        $pageinfo = get_context_info_array($PAGE->context->id);
        // Adds page title.
        $trackurl = "'";

        if ((isset($pageinfo[1]->category)) || (isset($pageinfo[1]->fullname)) || (isset($pageinfo[2]->name))) {
            // Adds course category name.
            if (isset($pageinfo[1]->category)) {
                if ($category = $DB->get_record('course_categories', array('id' => $pageinfo[1]->category))) {
                    $cats = explode("/", $category->path);
                    foreach (array_filter($cats) as $cat) {
                        if ($categorydepth = $DB->get_record("course_categories", array("id" => $cat))) {
                            $trackurl .= $categorydepth->name . '/';
                        }
                    }
                }
            }

            // Adds course full name.
            if (isset($pageinfo[1]->fullname)) {
                if (isset($pageinfo[2]->name)) {
                    $trackurl .= $pageinfo[1]->fullname . '/';
                } else if ($PAGE->user_is_editing()) {
                    $trackurl .= $pageinfo[1]->fullname . '/' . get_string('edit');
                } else {
                    $trackurl .= $pageinfo[1]->fullname . '/' . get_string('view');
                }
            }

            // Adds activity name.
            if (isset($pageinfo[2]->name)) {
                $trackurl .= $pageinfo[2]->modname . '/' . $pageinfo[2]->name;
            }
        } else {
            $trackurl .= $PAGE->title;
        }

        $trackurl .= "'";
        return $trackurl;
    }

    /**
     * Insert code that triggers ~~piwik~~matomo.
     */
    public function matomo_insert_html() {
        $siteurl = get_config('theme_wwu2019', 'matomo_siteurl');
        $tracking = '';

        if (!empty($siteurl)) {
            $imagetrack = true;
            $siteid = get_config('theme_wwu2019', 'matomo_siteid');
            $trackadmin = false;
            $useuserid = false;
            $cleanurl = true;

            if ($imagetrack) {
                $addition = '<noscript><p><img src="//'.$siteurl.'/piwik.php?idsite='.$siteid;
                $addition .= '" style="border:0" alt="" /></p></noscript>';
            } else {
                $addition = '';
            }

            if ($useuserid) {
                global $USER;
                if ($USER->id) {
                    $userid = "".PHP_EOL."_paq.push(['setUserId', '".$USER->id."']);";
                } else {
                    $userid = "";
                }
            } else {
                $userid = "";
            }

            if ($cleanurl) {
                $doctitle = "".PHP_EOL."_paq.push(['setDocumentTitle', " . $this->matomo_trackurl() . "]);";
            } else {
                $doctitle = "";
            }

            if (!is_siteadmin() || $trackadmin) {
                $tracking = "
<script type='text/javascript'>
var _paq = _paq || [];".$doctitle."
_paq.push(['setCookieDomain', '*.uni-muenster.de']);
_paq.push(['setDomains', ['*.uni-muenster.de/LearnWeb']]);
_paq.push(['enableLinkTracking']);".$userid."
_paq.push(['trackPageView']);
(function(){
    var u=(('https:' == document.location.protocol) ? 'https' : 'http') + '://" . $siteurl . "/';
    _paq.push(['setSiteId', " . $siteid . "]);
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    var d=document,
        g=d.createElement('script'),
        s=d.getElementsByTagName('script')[0];
    g.type='text/javascript';
    g.defer=true;
    g.async=true;
    g.src=u+'piwik.js';
    s.parentNode.insertBefore(g,s);
})();
</script>".$addition;
            }
        }
        return $tracking;
    }
    /**
     * Renders the slideshow.
     * @return string
     */
    public function slideshow() {
        global $CFG, $OUTPUT, $USER;

        $output = '';

        if (file_exists($CFG->dirroot . '/local/marketing/locallib.php')) {
            // Retrieve slides if none are cached.
            // Also, force re-cache if user has changed ID recently (i.e., a login has occurred).
            if (!isset($_SESSION["theme_wwu2019_slides"]) || !is_array($_SESSION["theme_wwu2019_slides"]) ||
                $_SESSION["theme_wwu2019_slides_cachedfor"] !== $USER->id || empty($_SESSION["theme_wwu2019_slides"])
            ) {
                require_once($CFG->dirroot . '/local/marketing/locallib.php');
                $allslides = \local_marketing\slide_manager::get_slides_for();
                $slides = array();
                $index = 0;
                foreach ($allslides as $slide) {
                    // Add slide index for slide navigation in the mustache template.
                    $slide->index = $index++;
                    // Get slide image or fallback to default.
                    $slideimage = $slide->image;
                    if ($slideimage) {
                        $component = 'local_marketing';
                        require_once($CFG->libdir . '/weblib.php');
                        $slideimage = \moodle_url::make_pluginfile_url(1, $component,
                            'slidesfilearea', $slide->id, '/', $slideimage);
                        $slideimage = preg_replace('|^https?://|i', '//', $slideimage->out(false));
                    } else {
                        $slideimage = self::pix_url('default_slide', 'theme');
                    }
                    $slide->slideimage = $slideimage;

                    // Attach tracking params for matomo.
                    $concatenate = strpos($slide->link, '?') !== false ? '&' : '?';
                    $titleslug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $slide->title)));
                    $slide->link .= $concatenate . 'pk_medium=local_marketing&pk_campaign=' . $slide->id . '--' . $titleslug;

                    $slides[] = $slide;
                }
                if ($index > 0) {
                    $slides[0]->active = true;
                }

                $_SESSION["theme_wwu2019_slides"] = $slides;
                $_SESSION["theme_wwu2019_slides_cachedfor"] = $USER->id;
            } else {
                $slides = $_SESSION["theme_wwu2019_slides"];
            }

            if ($slides) {
                $output .= $OUTPUT->render_from_template('theme_wwu2019/slideshow', array('slides' => $slides));
            }
        }
        return $output;
    }

    /**
     * For a specific set of users, add current hostname to standard footer.
     * @return string|void
     */
    public function standard_footer_html() {
        $standardfooter = parent::standard_footer_html();
        if (!is_siteadmin()) {
            return $standardfooter;
        }

        return \html_writer::tag('div', sprintf("Hostname: %s", gethostname()), array('class' => 'hostname')) .
            $standardfooter;
    }

}
