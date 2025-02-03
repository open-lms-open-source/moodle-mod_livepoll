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
 * Live poll text result for poll rendering.
 *
 * @copyright Copyright (c) 2018 Open LMS
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(["jquery", "mod_livepoll/text-result-lazy"],
    function($, TextResult) {
        /**
         * Decorated Text result constructor.
         * @param {TextResult} decoratedResult
         * @returns {DecoratedTextResult}
         * @constructor
         */
        function DecoratedTextResult(decoratedResult) {
            TextResult.call(this);
            this._decoratedResult = decoratedResult;
            return (this);
        }

        // Prototype extension.
        DecoratedTextResult.prototype = Object.create(TextResult.prototype);

        /**
         * Renders the text result.
         * @param {array} options
         * @param {array} votes
         */
        DecoratedTextResult.prototype.renderResult = function(options, votes) {
            this._decoratedResult.renderResult(options, votes);
        };

        return (DecoratedTextResult);
    }
);