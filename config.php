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
 * WWU 2019 Config
 *
 * @package    theme_wwu2019
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// Variable $THEME is defined before this page is included and we can define settings by adding properties to this global object.

// The first setting we need is the name of the theme. This should be the last part of the component name, and the same
// as the directory name for our theme.
$THEME->name = 'wwu2019';

// This setting list the style sheets we want to include in our theme. Because we want to use SCSS instead of CSS - we won't
// list any style sheets. If we did we would list the name of a file in the /styles/ folder for our theme without any css file
// extensions.
$THEME->sheets = [];

// This is a setting that can be used to provide some styling to the content in the TinyMCE text editor. This is no longer the
// default text editor and "Atto" does not need this setting so we won't provide anything. If we did it would work the same
// as the previous setting - listing a file in the /styles/ folder.
$THEME->editor_sheets = [];

// This is a critical setting. We want to inherit from theme_classic because it provides a great starting point for SCSS bootstrap4
// themes. We have added add more than one parent here to inherit from multiple parents, and if we did they would be processed in
// order of importance (later themes overriding earlier ones). Things we will inherit from the parent theme include
// styles and mustache templates and some (not all) settings.
$THEME->parents = ['boost', 'classic'];

// A dock is a way to take blocks out of the page and put them in a persistent floating area on the side of the page.
// does not support a dock so we won't either - but look at bootstrapbase for an example of a theme with a dock.
$THEME->enable_dock = false;

// This is an old setting used to load specific CSS for some YUI JS. We don't need it in Classic based themes because Classic
// provides default styling for the YUI modules that we use. It is not recommended to use this setting anymore.
$THEME->yuicssmodules = array();

// Most themes will use this rendererfactory as this is the one that allows the theme to override any other renderer.
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

$THEME->prescsscallback = 'theme_wwu2019_get_pre_scss';

// Since we are using 2 parent themes the correct location of the layout files needs to be defined. For this theme we need the
// multiple column layouts.
$THEME->layouts = [
        // Most backwards compatible layout without the blocks - this is the layout used by default.
        'base' => array(
                'theme' => 'wwu2019',
                'file' => 'columns.php',
                'regions' => array(),
        ),
        // Standard layout with blocks, this is recommended for most pages with general information.
        'standard' => array(
                'theme' => 'wwu2019',
                'file' => 'columns.php',
                'regions' => array('side-post'),
                'defaultregion' => 'side-post',
        ),
        // Main course page.
        'course' => array(
                'theme' => 'wwu2019',
                'file' => 'columns.php',
                'regions' => array('side-post'),
                'defaultregion' => 'side-post',
                'options' => array('langmenu' => true),
        ),
        'coursecategory' => array(
                'theme' => 'wwu2019',
                'file' => 'columns.php',
                'regions' => array('side-post'),
                'defaultregion' => 'side-post',
        ),
        // Part of course, typical for modules - default page layout if $cm specified in require_login().
        'incourse' => array(
                'theme' => 'wwu2019',
                'file' => 'columns.php',
                'regions' => array('side-post'),
                'defaultregion' => 'side-post',
        ),
        // The site home page.
        'frontpage' => array(
                'theme' => 'wwu2019',
                'file' => 'frontpage.php',
                'regions' => array('side-post'),
                'defaultregion' => 'side-post',
                'options' => array('nonavbar' => true),
        ),
        // Server administration scripts.
        'admin' => array(
                'theme' => 'wwu2019',
                'file' => 'columns.php',
                'regions' => array('side-post'),
                'defaultregion' => 'side-post',
        ),
        // My dashboard page.
        'mydashboard' => array(
                'theme' => 'wwu2019',
                'file' => 'columns.php',
                'regions' => array('side-post'),
                'defaultregion' => 'side-post',
                'options' => array('nonavbar' => true, 'langmenu' => true, 'nocontextheader' => true),
        ),
        // My public page.
        'mypublic' => array(
                'theme' => 'wwu2019',
                'file' => 'columns.php',
                'regions' => array('side-post'),
                'defaultregion' => 'side-post',
        ),
        'login' => array(
                'theme' => 'wwu2019',
                'file' => 'columns.php',
                'regions' => array(),
                'options' => array('langmenu' => true),
        ),

        // Pages that appear in pop-up windows - no navigation, no blocks, no header.
        'popup' => array(
                'theme' => 'wwu2019',
                'file' => 'contentonly.php',
                'regions' => array(),
                'options' => array('nofooter' => true, 'nonavbar' => true),
        ),
        // No blocks and minimal footer - used for legacy frame layouts only!
        'frametop' => array(
                'theme' => 'wwu2019',
                'file' => 'contentonly.php',
                'regions' => array(),
                'options' => array('nofooter' => true, 'nocoursefooter' => true),
        ),
        // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
        'embedded' => array(
                'theme' => 'boost',
                'file' => 'embedded.php',
                'regions' => array()
        ),
        // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
        // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
        // Please be extremely careful if you are modifying this layout.
        'maintenance' => array(
                'theme' => 'boost',
                'file' => 'maintenance.php',
                'regions' => array(),
        ),
        // Should display the content and basic headers only.
        'print' => array(
                'theme' => 'wwu2019',
                'file' => 'contentonly.php',
                'regions' => array(),
                'options' => array('nofooter' => true, 'nonavbar' => false),
        ),
        // The pagelayout used when a redirection is occuring.
        'redirect' => array(
                'theme' => 'boost',
                'file' => 'embedded.php',
                'regions' => array(),
        ),
        // The pagelayout used for reports.
        'report' => array(
                'theme' => 'wwu2019',
                'file' => 'columns.php',
                'regions' => array('side-post'),
                'defaultregion' => 'side-post',
        ),
        // The pagelayout used for safebrowser and securewindow.
        'secure' => array(
                'theme' => 'wwu2019',
                'file' => 'secure.php',
                'regions' => array('side-post'),
                'defaultregion' => 'side-post'
        )
];

// This is the function that returns the SCSS source for the main file in our theme.
$THEME->scss = function($theme) {
    return theme_wwu2019_get_main_scss_content($theme);
};

$THEME->iconsystem = '\\theme_wwu2019\\output\\icon_system_fontawesome';

$THEME->requiredblocks = '';

$THEME->haseditswitch = false;
