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
 * @module     theme_wwu2019/menu
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';
import Notification from 'core/notification';
import {setUserPreference} from "core_user/repository";

/**
 * Init function
 */
export function init() {
    openMenu();
    initThemeChooser();
}


/**
 * Initializes the theme chooser.
 */
function initThemeChooser() {
    const html = $('html');
    const uselighttheme = $('#user-menu .wwu-uselighttheme');
    const useostheme = $('#user-menu .wwu-useostheme');
    const usedarktheme = $('#user-menu .wwu-usedarktheme');
    const darkThemeClass = 'dark-theme';
    const lightThemeClass = 'light-theme';

    let selected;
    if (html.hasClass(darkThemeClass)) {
        selected = usedarktheme;
    } else if (html.hasClass(lightThemeClass)) {
        selected = uselighttheme;
    } else {
        selected = useostheme;
    }
    selected.addClass('selectedtheme');

    uselighttheme.click(function() {
        selected.removeClass('selectedtheme');
        html.removeClass(darkThemeClass);
        html.addClass(lightThemeClass);
        updateThemePreferenceAjax(1);
        selected = uselighttheme;
        selected.addClass('selectedtheme');
    });
    useostheme.click(function() {
        selected.removeClass('selectedtheme');
        html.removeClass(darkThemeClass);
        html.removeClass(lightThemeClass);
        updateThemePreferenceAjax(null);
        selected = useostheme;
        selected.addClass('selectedtheme');
    });
    usedarktheme.click(function() {
        selected.removeClass('selectedtheme');
        html.addClass(darkThemeClass);
        html.removeClass(lightThemeClass);
        updateThemePreferenceAjax(2);
        selected = usedarktheme;
        selected.addClass('selectedtheme');
    });
}

/**
 * Updates the theme preference.
 * @param {int} theme
 */
function updateThemePreferenceAjax(theme) {
    setUserPreference('theme_wwu2019_theme', theme)
        .catch(Notification.exception);
}

const onecolumnbreakpoint = 767;
const hamburgerbreakpoint = 1080;


/**
 * Opens submenu when hovering
 */
function openMenu() {
    const openDelay = 100;
    const closeDelay = 300;
    const closeCooldown = 300;

    let openMenu = null;
    let openCandidate = null;
    let openTimeoutId = null;
    let closeTimeoutId = null;
    /** @type {null|Date} Date when menu was opened by hovering, to prevent immediately closing it by clicking again. */
    let openTime = null;

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
        $(menuItem).children('a').attr('aria-expanded', 'true');
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
        openTimeoutId = setTimeout(() => {
            open(menuItem);
            openTime = new Date();
        }, openDelay);
    }

    /**
     * Closes a menu.
     * @param {Node} menuItem the menuItem DOM-Element
     */
    function close(menuItem) {
        $(menuItem).removeClass('open');
        $(menuItem).children('a').attr('aria-expanded', 'false');
        openMenu = null;
        abortClose();
        openTime = null;
    }

    /**
     * Closes a menu with delay.
     * @param {Node} menuItem the menuItem DOM-Element
     */
    function closeWithDelay(menuItem) {
        closeTimeoutId = setTimeout(close, closeDelay, menuItem);
    }

    /**
     * Aborts the delayed closing of {@link openMenu}
     */
    function abortClose() {
        if (closeTimeoutId) {
            clearTimeout(closeTimeoutId);
            closeTimeoutId = null;
        }
    }

    /**
     * Aborts the delayed opening of {@link openCandidate}
     */
    function abortOpen() {
        openCandidate = null;
        if (openTimeoutId) {
            clearTimeout(openTimeoutId);
            openTimeoutId = null;
        }
    }

    /**
     * Closes the open menu and resets timeouts;
     */
    function reset() {
        abortClose();
        abortOpen();
        if (openMenu) {
            close(openMenu);
        }
    }

    $('li.main-menu-item > a[aria-haspopup="true"], li#user-menu > a[aria-haspopup="true"]').click((ev) => {
        if (ev.currentTarget.parentNode === openMenu) {
            if (new Date() - openTime > closeCooldown) {
                close(openMenu);
            }
        } else {
            open(ev.currentTarget.parentNode);
        }
    });

    let menuitems = $('li.main-menu-item > a[aria-haspopup="true"], li#user-menu > a[aria-haspopup="true"]').parent();
    menuitems.mouseenter((ev) => {
        let width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
        if (width > onecolumnbreakpoint) {
            if (ev.currentTarget === openMenu) {
                abortClose();
            } else {
                openWithDelay(ev.currentTarget);
            }
        }
    });
    menuitems.mouseleave((ev) => {
        let width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
        if (width > onecolumnbreakpoint) {
            if (openCandidate && ev.currentTarget === openCandidate) {
                abortOpen();
            }
            if (openMenu && ev.currentTarget === openMenu) {
                closeWithDelay(ev.currentTarget);
            }
        }
    });

    $('li.sub-menu-item > a[aria-haspopup="true"], li.sub-sub-menu-item > a[aria-haspopup="true"]').click((ev) => {
        let link = $(ev.currentTarget);
        let item = link.parent();
        item.toggleClass('open');
        link.attr('aria-expanded', item.hasClass('open'));
    });

    let hamburgertoggle = $('#main-menu-hamburger > a');
    let hamburgerparent = hamburgertoggle.parent();
    hamburgertoggle.click(() => {
        hamburgerparent.toggleClass('open');
        hamburgertoggle.attr('aria-expanded', hamburgerparent.hasClass('open'));
    });

    let oneColLayoutBefore = false;
    let hamburgerLayoutBefore = false;

    /**
     * Updates max-height of submenus on page-init and window resize.
     */
    function updateMaxMenuHeight() {
        const menuButtom = $('#menu-bottom');
        let botPos = menuButtom.offset().top + 16;
        let width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
        let oneColLayout = width <= onecolumnbreakpoint;
        if (oneColLayout) {
            $('.sub-menu-scroll-container').css('max-height', '');
            $('#main-menu-left').css('max-height', ($(window).height() - botPos) + 'px');
        } else {
            // If Menu is expanded down, add the height of the #main-menu-left container
            if (width <= hamburgerbreakpoint) {
                botPos += 50;
            }
            $('.sub-menu-scroll-container').css('max-height', ($(window).height() - botPos) + 'px');
            $('#main-menu-left').css('max-height', '');
        }
        if (oneColLayout !== oneColLayoutBefore) {
            reset();
            oneColLayoutBefore = oneColLayout;
        }

        // Switch hamburger toggle role between button and none, based on whether it is hidden.
        let hamburgerLayout = width <= hamburgerbreakpoint;
        if (hamburgerLayout !== hamburgerLayoutBefore) {
            hamburgertoggle.attr('role', hamburgerLayout ? 'button' : 'none');
            hamburgerLayoutBefore = hamburgerLayout;
        }
    }

    updateMaxMenuHeight();
    $(window).resize(updateMaxMenuHeight);
}
