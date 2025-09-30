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
 * Block plugin "Vextur Introduction" - JS code
 *
 * @package    block_vexturintroduction
 * @copyright  UAB "Vextur" <info@vextur.com>
 * @author     2023, Beata Gancevska <beata@vextur.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([], function () {
    "use strict";

    return {
        init: function (imageurl) {
            const block = document.querySelector('.block_vexturintroduction');
            if (block) {
                block.style.backgroundImage = "linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url(" + imageurl + ")";
            }
        },
    };
});
