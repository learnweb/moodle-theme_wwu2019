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
 * Manages the very important snow.
 *
 * @package    theme_wwu2019
 * @copyright  2020 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';

let canvas;

let desiredFlakes = 150;

/**
 * @type {[{x: number, y: number, r: number, d: number}]}
 */
let flakes = [];

let intervalId = null;

const pixurl = M.cfg.wwwroot + '/theme/wwu2019/pix/';

/**
 * Init function
 */
export function init() {
    let enableSnow = localStorage.getItem('theme_wwu2019/enable-snow') !== 'false';

    $('<div class="mr-3" id="snow-toggle">' +
        '<img style="margin-top: -6px; cursor: pointer" width="35">' +
        '</div>').insertAfter('#main-menu-right .main-menu-additions-item');

    canvas = document.createElement('canvas');
    canvas.height = window.innerHeight;
    canvas.width = window.innerWidth;
    canvas.style.position = 'fixed';
    canvas.style.top = '0';
    canvas.style.left = '0';
    canvas.style.zIndex = '1000';
    canvas.style.opacity = '0.8';
    canvas.style.pointerEvents = 'none';
    $(window).resize(() => {
        canvas.height = window.innerHeight;
        canvas.width = window.innerWidth;
        desiredFlakes = 50 + canvas.height * canvas.width * 0.0001;
    });

    desiredFlakes = 50 + canvas.height * canvas.width * 0.0001;

    // Loop throught the empty flakes and apply attributes
    for (let i = 0; i < desiredFlakes; i++) {
        flakes.push({
            x: generateXCoord(), // Also spawn left and right of window.
            y: Math.random() * canvas.height,
            r: Math.random() * 5 + 2, // Min of 2px and max of 7px
            d: Math.random() + 1 // Density of the flake
        });
    }
    let ctx = canvas.getContext("2d");

    let snowicon = $('#snow-toggle > img');

    if (enableSnow) {
        intervalId = setInterval(redraw, 25, ctx);
        snowicon.attr('src', pixurl + 'snow-disable.svg');
    } else {
        canvas.hidden = true;
        snowicon.attr('src', pixurl + 'snow-enable.svg');
    }

    $('#snow-toggle').click(() => {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
            snowicon.attr('src', pixurl + 'snow-enable.svg');
        } else {
            intervalId = setInterval(redraw, 25, ctx);
            snowicon.attr('src', pixurl + 'snow-disable.svg');
        }
        canvas.hidden = intervalId === null;
        localStorage.setItem('theme_wwu2019/enable-snow', intervalId !== null ? 'true' : 'false');
    });

    $('body').append(canvas);
}

// Animate the flakes
let angle = 0;

function moveFlakes() {
    angle += 0.01;
    let basemovement = Math.sin(angle / 2) * 2;
    for (let i = 0; i < flakes.length; i++) {
        // Store current flake
        let f = flakes[i];
        // Update X and Y coordinates of each snowflakes
        f.y += Math.pow(f.d, 2) + 1;
        f.x += basemovement + Math.sin(f.d * 8 + angle) * 0.5;

        // If the snowflake reaches the bottom, send a new one to the top
        if (f.y > canvas.height + 10) {
            if (flakes.length > desiredFlakes) {
                flakes.splice(i, 1);
                i--;
            }
            f.x = generateXCoord();
            f.y = -10;
        }
    }
    if (desiredFlakes > flakes.length && Math.random() < 0.2) {
        flakes.push({
            x: generateXCoord(), // Also spawn left and right of window.
            y: -10,
            r: Math.random() * 5 + 2, // Min of 2px and max of 7px
            d: Math.random() + 1 // Density of the flake
        });
    }
}

function generateXCoord() {
    return Math.random() * (canvas.width + 400) - 200;
}

function redraw(ctx) {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    // ctx.fillStyle = "rgba(0, 0, 0, 0.1)";
    // ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "#cbebfa";
    // ctx.fillStyle = "#fff";
    ctx.beginPath();
    for (let f of flakes) {
        ctx.moveTo(f.x, f.y);
        ctx.arc(f.x, f.y, f.r, 0, Math.PI * 2, true);
    }
    ctx.fill();
    moveFlakes();
}