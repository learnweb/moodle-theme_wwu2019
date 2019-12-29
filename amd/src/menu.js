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
 * Manage the user menu.
 *
 * @package    theme_wwu2019
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

/**
 * Init function
 */
export function init() {
    stickMenu();
    openMenu();
}

/**
 * Adds sticky class to main menu nav when needed.
 */
function stickMenu() {
    const mainMenu = $('#main-menu');
    const topPos = mainMenu.offset().top;
    let sticky = $(window).scrollTop() > topPos;
    if (sticky) {
        mainMenu.addClass('sticky');
    }
    $(window).scroll(() => {
        if (!sticky && $(window).scrollTop() > topPos) {
            mainMenu.addClass('sticky');
            sticky = true;
        } else if (sticky && $(window).scrollTop() < topPos) {
            mainMenu.removeClass('sticky');
            sticky = false;
        }
    });
}

/**
 * Opens submenu when hovering
 */
function openMenu() {
    const delay = 400;
    let menuitems = $('li.main-menu-item');
    let openMenu = null;
    let openCandidate = null;
    let openTimeoutId = null;
    let closeTimeoutId = null;
    menuitems.mouseenter((ev) => {
        if (ev.currentTarget === openMenu) {
            clearTimeout(closeTimeoutId);
            return;
        }

        openCandidate = ev.currentTarget;
        openTimeoutId = setTimeout(() => {
            $(openMenu).removeClass('open');
            $(ev.currentTarget).addClass('open');
            openMenu = ev.currentTarget;
            openCandidate = null;
            openTimeoutId = null;
        }, delay);
    });
    menuitems.mouseleave((ev) => {
        if (openCandidate && ev.currentTarget === openCandidate) {
            clearTimeout(openTimeoutId);
            return;
        }
        if (openMenu && ev.currentTarget === openMenu) {
            closeTimeoutId = setTimeout(() => {
                $(ev.currentTarget).removeClass('open');
                openMenu = null;
                closeTimeoutId = null;
            }, delay);
        }
    });
}