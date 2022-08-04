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
 * Set the slides to all be the height.
 *
 * @module     theme_wwu2019/slideshow
 * @copyright  2021 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

/**
 * Init function
 */
export function init() {
    const items = $('#wwuCarousel .carousel-item');

    /**
     * A function that handles window resizing to set the correct height for the carousel slides.
     */
    function onResize() {
        let maxheight = 0;
        items.each(function() {
            $(this).css('height', '');
            maxheight = Math.max(maxheight, $(this).height());
        });
        items.each(function() {
            $(this).css('height', maxheight + 'px');
        });
    }

    window.onresize = onResize;
    onResize();
}