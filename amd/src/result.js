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
 * Live dummy result handler. Not to be used but to be extended.
 *
 * @package mod_livepoll
 * @copyright Copyright (c) 2018 Open LMS
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(["jquery", "core/log"],
    function($, Log) {

        /**
         * Dummy result constructor.
         * @returns {Result}
         * @constructor
         */
        function Result() {
            return (this);
        }

        /**
         * Promises to update the UI for this result handler.
         * Do not override!
         * @param options
         * @param votes
         */
        Result.prototype.update = function(options, votes) {
            var dfd = $.Deferred();
            this.performUpdate(options, votes, function() {
                dfd.resolve();
            });
            return dfd.promise();
        };

        /**
         * Updates the UI for this result handler and acknowledges using a callback.
         * @param options
         * @param votes
         * @param callback
         */
        Result.prototype.performUpdate = function(options, votes, callback) {
            Log.debug("Results:");
            $.each(options, function(optionid) {
                Log.debug(optionid + " : " + votes[optionid]);
            });
            callback();
        };

        return (Result);
    }
);