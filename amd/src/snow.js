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
 * @module     theme_wwu2019/snow
 * @copyright  2020 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import $ from 'jquery';
import Notification from 'core/notification';
import {setUserPreference} from 'core_user/repository';

let canvas;

let desiredFlakes = 150;

/**
 * @type {[{x: number, y: number, r: number, d: number}]}
 */
let flakes = [];

let intervalId = null;

const pixurl = M.cfg.wwwroot + '/theme/wwu2019/pix/';

let noise = exportNoise();

/**
 * Updates the snow preference.
 * @param {boolean} snow
 */
function updateSnowPreferenceAjax(snow) {
    setUserPreference('theme_wwu2019_snow', snow ? 1 : 0)
        .catch(Notification.exception);
}

/**
 * Init function
 * @param {int} snowValue
 */
export function init(snowValue) {
    let enableSnow = !!snowValue;
    if (localStorage.getItem('theme_wwu2019/enable-snow') !== null) {
        enableSnow = localStorage.getItem('theme_wwu2019/enable-snow') !== 'false';
        updateSnowPreferenceAjax(enableSnow);
        localStorage.removeItem('theme_wwu2019/enable-snow');
    }

    $('<div class="mr-3" id="snow-toggle">' +
        '<img style="margin-top: -6px; cursor: pointer" width="35">' +
        '</div>').insertAfter('#main-menu-right .main-menu-additions-item');

    canvas = document.createElement('canvas');
    canvas.height = window.innerHeight;
    canvas.width = window.innerWidth;
    canvas.classList.add('snow');
    canvas.style.position = 'fixed';
    canvas.style.top = '0';
    canvas.style.left = '0';
    canvas.style.zIndex = '1010';
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
        updateSnowPreferenceAjax(intervalId !== null);
    });

    $('body').append(canvas);

    noise.seed(Math.random());
}

// Animate the flakes
let angle = 0;

/**
 * Move the flakes.
 */
function moveFlakes() {
    angle += 0.01;
    let basemovement = noise.simplex2(angle * 0.1, 0) * 2.5;
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

/**
 * Generate X Coordinate
 * @returns {number} the x coordinate
 */
function generateXCoord() {
    return Math.random() * (canvas.width + 400) - 200;
}

/**
 * Redraw everything
 * @param {CanvasRenderingContext2D} ctx
 */
function redraw(ctx) {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "#cbebfa";
    ctx.beginPath();
    for (let f of flakes) {
        ctx.moveTo(f.x, f.y);
        ctx.arc(f.x, f.y, f.r, 0, Math.PI * 2, true);
    }
    ctx.fill();
    moveFlakes();
}

/*
 * A speed-improved perlin and simplex noise algorithms for 2D.
 *
 * Based on example code by Stefan Gustavson (stegu@itn.liu.se).
 * Optimisations by Peter Eastman (peastman@drizzle.stanford.edu).
 * Better rank ordering method by Stefan Gustavson in 2012.
 * Converted to Javascript by Joseph Gentle.
 *
 * Version 2012-03-09
 *
 * This code was placed in the public domain by its original author,
 * Stefan Gustavson. You may use it as you see fit, but
 * attribution is appreciated.
 *
 */
// eslint-disable-next-line
function exportNoise() {
    /* eslint-disable */
    var module = {};

    function Grad(x, y, z) {
        this.x = x;
        this.y = y;
        this.z = z;
    }

    Grad.prototype.dot2 = function (x, y) {
        return this.x * x + this.y * y;
    };

    Grad.prototype.dot3 = function (x, y, z) {
        return this.x * x + this.y * y + this.z * z;
    };

    var grad3 = [new Grad(1, 1, 0), new Grad(-1, 1, 0), new Grad(1, -1, 0), new Grad(-1, -1, 0),
        new Grad(1, 0, 1), new Grad(-1, 0, 1), new Grad(1, 0, -1), new Grad(-1, 0, -1),
        new Grad(0, 1, 1), new Grad(0, -1, 1), new Grad(0, 1, -1), new Grad(0, -1, -1)];

    var p = [151, 160, 137, 91, 90, 15,
        131, 13, 201, 95, 96, 53, 194, 233, 7, 225, 140, 36, 103, 30, 69, 142, 8, 99, 37, 240, 21, 10, 23,
        190, 6, 148, 247, 120, 234, 75, 0, 26, 197, 62, 94, 252, 219, 203, 117, 35, 11, 32, 57, 177, 33,
        88, 237, 149, 56, 87, 174, 20, 125, 136, 171, 168, 68, 175, 74, 165, 71, 134, 139, 48, 27, 166,
        77, 146, 158, 231, 83, 111, 229, 122, 60, 211, 133, 230, 220, 105, 92, 41, 55, 46, 245, 40, 244,
        102, 143, 54, 65, 25, 63, 161, 1, 216, 80, 73, 209, 76, 132, 187, 208, 89, 18, 169, 200, 196,
        135, 130, 116, 188, 159, 86, 164, 100, 109, 198, 173, 186, 3, 64, 52, 217, 226, 250, 124, 123,
        5, 202, 38, 147, 118, 126, 255, 82, 85, 212, 207, 206, 59, 227, 47, 16, 58, 17, 182, 189, 28, 42,
        223, 183, 170, 213, 119, 248, 152, 2, 44, 154, 163, 70, 221, 153, 101, 155, 167, 43, 172, 9,
        129, 22, 39, 253, 19, 98, 108, 110, 79, 113, 224, 232, 178, 185, 112, 104, 218, 246, 97, 228,
        251, 34, 242, 193, 238, 210, 144, 12, 191, 179, 162, 241, 81, 51, 145, 235, 249, 14, 239, 107,
        49, 192, 214, 31, 181, 199, 106, 157, 184, 84, 204, 176, 115, 121, 50, 45, 127, 4, 150, 254,
        138, 236, 205, 93, 222, 114, 67, 29, 24, 72, 243, 141, 128, 195, 78, 66, 215, 61, 156, 180];
    // To remove the need for index wrapping, double the permutation table length
    var perm = new Array(512);
    var gradP = new Array(512);

    // This isn't a very good seeding function, but it works ok. It supports 2^16
    // different seed values. Write something better if you need more seeds.
    module.seed = function (seed) {
        if (seed > 0 && seed < 1) {
            // Scale the seed out
            seed *= 65536;
        }

        seed = Math.floor(seed);
        if (seed < 256) {
            seed |= seed << 8;
        }

        for (var i = 0; i < 256; i++) {
            var v;
            if (i & 1) {
                v = p[i] ^ (seed & 255);
            } else {
                v = p[i] ^ ((seed >> 8) & 255);
            }

            perm[i] = perm[i + 256] = v;
            gradP[i] = gradP[i + 256] = grad3[v % 12];
        }
    };

    module.seed(0);

    // Skewing and unskewing factors for 2, 3, and 4 dimensions
    var F2 = 0.5 * (Math.sqrt(3) - 1);
    var G2 = (3 - Math.sqrt(3)) / 6;

    // 2D simplex noise
    module.simplex2 = function(xin, yin) {
        var n0, n1, n2; // Noise contributions from the three corners
        // Skew the input space to determine which simplex cell we're in
        var s = (xin + yin) * F2; // Hairy factor for 2D
        var i = Math.floor(xin + s);
        var j = Math.floor(yin + s);
        var t = (i + j) * G2;
        var x0 = xin - i + t; // The x,y distances from the cell origin, unskewed.
        var y0 = yin - j + t;
        // For the 2D case, the simplex shape is an equilateral triangle.
        // Determine which simplex we are in.
        var i1, j1; // Offsets for second (middle) corner of simplex in (i,j) coords
        if (x0 > y0) { // Lower triangle, XY order: (0,0)->(1,0)->(1,1)
            i1 = 1;
            j1 = 0;
        } else {    // Upper triangle, YX order: (0,0)->(0,1)->(1,1)
            i1 = 0;
            j1 = 1;
        }
        // A step of (1,0) in (i,j) means a step of (1-c,-c) in (x,y), and
        // a step of (0,1) in (i,j) means a step of (-c,1-c) in (x,y), where
        // c = (3-sqrt(3))/6
        var x1 = x0 - i1 + G2; // Offsets for middle corner in (x,y) unskewed coords
        var y1 = y0 - j1 + G2;
        var x2 = x0 - 1 + 2 * G2; // Offsets for last corner in (x,y) unskewed coords
        var y2 = y0 - 1 + 2 * G2;
        // Work out the hashed gradient indices of the three simplex corners
        i &= 255;
        j &= 255;
        var gi0 = gradP[i + perm[j]];
        var gi1 = gradP[i + i1 + perm[j + j1]];
        var gi2 = gradP[i + 1 + perm[j + 1]];
        // Calculate the contribution from the three corners
        var t0 = 0.5 - x0 * x0 - y0 * y0;
        if (t0 < 0) {
            n0 = 0;
        } else {
            t0 *= t0;
            n0 = t0 * t0 * gi0.dot2(x0, y0); // (x,y) of grad3 used for 2D gradient
        }
        var t1 = 0.5 - x1 * x1 - y1 * y1;
        if (t1 < 0) {
            n1 = 0;
        } else {
            t1 *= t1;
            n1 = t1 * t1 * gi1.dot2(x1, y1);
        }
        var t2 = 0.5 - x2 * x2 - y2 * y2;
        if (t2 < 0) {
            n2 = 0;
        } else {
            t2 *= t2;
            n2 = t2 * t2 * gi2.dot2(x2, y2);
        }
        // Add contributions from each corner to get the final noise value.
        // The result is scaled to return values in the interval [-1,1].
        return 70 * (n0 + n1 + n2);
    };

    // ##### Perlin noise stuff

    function fade(t) {
        return t * t * t * (t * (t * 6 - 15) + 10);
    }

    function lerp(a, b, t) {
        return (1 - t) * a + t * b;
    }

    // 2D Perlin Noise
    module.perlin2 = function (x, y) {
        // Find unit grid cell containing point
        var X = Math.floor(x), Y = Math.floor(y);
        // Get relative xy coordinates of point within that cell
        x = x - X;
        y = y - Y;
        // Wrap the integer cells at 255 (smaller integer period can be introduced here)
        X = X & 255;
        Y = Y & 255;

        // Calculate noise contributions from each of the four corners
        var n00 = gradP[X + perm[Y]].dot2(x, y);
        var n01 = gradP[X + perm[Y + 1]].dot2(x, y - 1);
        var n10 = gradP[X + 1 + perm[Y]].dot2(x - 1, y);
        var n11 = gradP[X + 1 + perm[Y + 1]].dot2(x - 1, y - 1);

        // Compute the fade curve value for x
        var u = fade(x);

        // Interpolate the four results
        return lerp(
            lerp(n00, n10, u),
            lerp(n01, n11, u),
            fade(y));
    };

    return module;
}
