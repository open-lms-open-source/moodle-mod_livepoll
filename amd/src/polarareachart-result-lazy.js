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
 * Live poll Polar Area chart result for poll rendering.
 *
 * @package mod_livepoll
 * @copyright Copyright (c) 2019 Open LMS
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(["jquery", "core/log", "mod_livepoll/barchart-result-lazy"],
    function($, Log, BarChartResult) {

        /**
         * Text result constructor.
         * @returns {BarChartResult}
         * @constructor
         */
        function PolarAreaChartResult() {
            BarChartResult.call(this);
            this._initialized = false;
            this._chartType = "polarArea";
            this._options = {};
            return (this);
        }

        // Prototype extension.
        PolarAreaChartResult.prototype = Object.create(BarChartResult.prototype);

        return (PolarAreaChartResult);
    }
);