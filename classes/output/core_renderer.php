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

use context_course;
use moodle_page;
use moodle_url;
use navigation_node;
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
                'wwwroot' => $CFG->wwwroot,
                'right-menu-icons' => [
                        [
                                'icon' => (new pix_icon('i/cogs', ''))->export_for_pix(),
                        ],
                        [
                                'icon' => (new pix_icon('i/book', ''))->export_for_pix(),
                        ],
                        [
                                'icon' => (new pix_icon('i/cogs', ''))->export_for_pix(),
                        ]
                ],
                'user-menu' => $this->get_user_menu()
        ];

        $this->page->requires->js_call_amd('theme_wwu2019/menu', 'init');
        return $this->render_from_template('theme_wwu2019/menu', $templatecontext);
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
                        'name' => $node->get_content()
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
                    $templateformat['menu'] = $this->settingsnav_for_template($node->children);
                } else {
                    $templateformat['hasmenu'] = false;
                    $templateformat['menu'] = null;
                }
                if ($node->has_action() && !$node->has_children()) {
                    $templateformat['href'] = $node->action->out(false);
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
     * Gets and sorts all of the user's courses into terms.
     * @return array The sorted courses, ready for use in templates.
     */
    private function get_courses() {

        $courses = enrol_get_my_courses(array(), 'c.startdate DESC');
        $terms = [];

        $calendaricon = (new pix_icon('i/calendar', ''))->export_for_pix();
        $courseicon = (new pix_icon('i/graduation-cap', ''))->export_for_pix();
        $hiddencourseicon = (new pix_icon('i/hidden', ''))->export_for_pix();

        $termindependentlimit = new \DateTime("2000-00-00");

        foreach ($courses as $course) {

            if (!$course->visible &&
                    !has_capability('moodle/course:viewhiddencourses', context_course::instance($course->id))) {
                continue;
            }

            $coursestart = new \DateTime();
            $coursestart->setTimestamp($course->startdate);

            $year = (int) $coursestart->format('Y');
            $term = 0;
            $istermindependent = false;

            $term0start = new \DateTime("$year-04-01");
            $term1start = new \DateTime("$year-10-01");

            if ($coursestart < $termindependentlimit) {
                $istermindependent = true;
            } else if ($coursestart < $term0start) {
                $year--;
                $term = 1;
            } else if ($coursestart < $term1start) {
                $term = 0;
            } else {
                $term = 1;
            }

            $termid = $istermindependent ? 0 : $year . '_' . $term;
            if (!array_key_exists($termid, $terms)) {
                if ($istermindependent) {
                    $name = get_string('termindependent', 'theme_wwu2019');
                } else {
                    if ($term == 0) {
                        $name = 'SoSe ' . $year;
                    } else {
                        $name = 'WiSe ' . $year . '/' . ($year + 1);
                    }
                }
                $terms[$termid] = [
                    'name' => $name,
                    'icon' => $calendaricon,
                    'hasmenu' => true,
                    'menu' => []
                ];
            }

            $terms[$termid]['menu'][] = [
                'name' => $course->visible ? $course->shortname : '<i>' . htmlentities($course->shortname) . '</i>',
                'dontescape' => !$course->visible,
                'href' => (new moodle_url('/course/view.php', array('id' => $course->id)))->out(false),
                'icon' => $course->visible ? $courseicon : $hiddencourseicon,
                'hasmenu' => false,
                'menu' => null
            ];
        }
        return array_values($terms);
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
                            'href' => (new moodle_url('/grade/report/index.php', array('id' => $this->page->course->id)))
                                    ->out(false)
                    ];
                }
                $activities[] = [
                        'name' => get_string('badgesview', 'badges'),
                        'icon' => (new pix_icon('i/trophy', ''))->export_for_pix(),
                        'hasmenu' => false,
                        'menu' => null,
                        'href' => (new moodle_url('/badges/view.php', array('id' => $this->page->course->id, 'type' => 2)))
                                ->out(false)
                ];

                $data = $this->get_course_activities();
                foreach ($data as $modname => $modfullname) {
                    if ($modname === 'resources') {
                        $activities[] = [
                                'name' => $modfullname,
                                'icon' => (new pix_icon('icon', '', 'mod_page'))->export_for_pix(),
                                'hasmenu' => false,
                                'menu' => null,
                                'href' => (new moodle_url('/course/resources.php', array('id' => $this->page->course->id)))
                                        ->out(false)
                        ];
                    } else {
                        $activities[] = [
                                'name' => $modfullname,
                                'icon' => (new pix_icon('icon', '', $modname))->export_for_pix(),
                                'hasmenu' => false,
                                'menu' => null,
                                'href' => (new moodle_url("/mod/$modname/index.php", array('id' => $this->page->course->id)))
                                        ->out(false)
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
     * @return array
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    private function get_user_menu() {
        global $USER, $CFG;

        $menucontent = [];

        /* ??? */
        $course = $this->page->course;
        $context = context_course::instance($course->id);

        // TODO Roleswitcher.

        // Link Profile page.
        if (\core\session\manager::is_loggedinas()) {
            $realuser = \core\session\manager::get_realuser();
            $menucontent[] = [
                    'name' => get_string('loggedinas', 'theme_wwu2019',
                            array('real' => fullname($realuser, true), 'fake' => fullname($USER, true))),
                    'hasmenu' => false,
                    'menu' => null,
                    'href' => (new moodle_url('/user/profile.php', array('id' => $USER->id)))->out(false),
                    'icon' => (new pix_icon('i/key', ''))->export_for_pix()
            ];
        } else {
            $menucontent[] = [
                    'name' => fullname($USER, true),
                    'hasmenu' => false,
                    'menu' => null,
                    'href' => (new moodle_url('/user/profile.php', array('id' => $USER->id)))->out(false),
                    'icon' => (new pix_icon('i/user', ''))->export_for_pix()
            ];
        }

        // Preferences Submenu.
        $menucontent[] = [
                'name' => get_string('settings'),
                'hasmenu' => true,
                'menu' => $this->get_user_settings_submenu($context),
                'icon' => (new pix_icon('i/cogs', ''))->export_for_pix()
        ];

        // TODO Add divider here.

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

        // TODO Add divider here.

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

        // TODO Add divider here.

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

        // TODO Add Help link.

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
        $output .= '<h2>' . $this->page->course->fullname . '</h2>';
        return $output;
    }

}
