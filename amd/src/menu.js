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

    updateMaxMenuHeight();
    $(window).resize(updateMaxMenuHeight);
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
 * Updates max-height of submenus on page-init and window resize.
 */
function updateMaxMenuHeight() {
    const placeholder = $('#main-menu-placeholder');
    const botPos = placeholder.offset().top + placeholder.outerHeight() + 24;
    $('.sub-menu-scroll-container').css('max-height', ($(window).height() - botPos) + 'px');
}

/**
 * Opens submenu when hovering
 */
function openMenu() {
    const delay = 300;
    let openMenu = null;
    let openCandidate = null;
    let openTimeoutId = null;
    let closeTimeoutId = null;

    /**
     * Opens a menu.
     * @param {Node} menuItem the menuItem DOM-Element
     */
    function open(menuItem) {
        if (openMenu) {
            close(openMenu);
        }
        abortClose();
        $(menuItem).addClass('open');
        openMenu = menuItem;
        openCandidate = null;
        openTimeoutId = null;
    }

    /**
     * Opens a menu with delay.
     * @param {Node} menuItem the menuItem DOM-Element
     */
    function openWithDelay(menuItem) {
        openCandidate = menuItem;
        openTimeoutId = setTimeout(open, delay, menuItem);
    }

    /**
     * Closes a menu.
     * @param {Node} menuItem the menuItem DOM-Element
     */
    function close(menuItem) {
        $(menuItem).removeClass('open');
        openMenu = null;
        closeTimeoutId = null;
    }

    /**
     * Closes a menu with delay.
     * @param {Node} menuItem the menuItem DOM-Element
     */
    function closeWithDelay(menuItem) {
        closeTimeoutId = setTimeout(close, delay, menuItem);
    }

    /**
     * Aborts the delayed closing of {@link openMenu}
     */
    function abortClose() {
        if (closeTimeoutId) {
            clearTimeout(closeTimeoutId);
        }
    }

    /**
     * Aborts the delayed opening of {@link openCandidate}
     */
    function abortOpen() {
        openCandidate = null;
        if (openTimeoutId) {
            clearTimeout(openTimeoutId);
        }
    }

    $('li.main-menu-item > a').click((ev) => {
        if (ev.currentTarget.parentNode === openMenu) {
            close(openMenu);
        } else {
            open(ev.currentTarget.parentNode);
        }
    });

    let menuitems = $('li.main-menu-item');
    menuitems.mouseenter((ev) => {
        if (ev.currentTarget === openMenu) {
            abortClose();
        } else {
            openWithDelay(ev.currentTarget);
        }
    });
    menuitems.mouseleave((ev) => {
        if (openCandidate && ev.currentTarget === openCandidate) {
            abortOpen();
        }
        if (openMenu && ev.currentTarget === openMenu) {
            closeWithDelay(ev.currentTarget);
        }
    });

    $('li.sub-menu-item > a').click((ev) => {
        $(ev.currentTarget.parentNode).toggleClass('open');
    });

    $('li.sub-sub-menu-item > a').click((ev) => {
        $(ev.currentTarget.parentNode).toggleClass('open');
    });
}