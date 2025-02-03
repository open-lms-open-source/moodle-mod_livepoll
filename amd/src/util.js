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
 * livepoll Util component.
 *
 * @copyright Copyright (c) 2018 Open LMS
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(["jquery"],
    function($) {
        var util = {};

        /**
         * Calculates the highest voted options.
         * @param {array} options
         * @param {array} votes
         * @returns {string}
         */
        util.getHighestVotedOptions = function(options, votes) {
            var highestOptions = [], highValue = 0;
            $.each(options, function(optionid) {
                if (votes[optionid] === highValue) {
                    highestOptions.push(optionid);
                } else if (votes[optionid] > highValue) {
                    highestOptions = [optionid];
                    highValue = votes[optionid];
                }
            });

            return highestOptions;
        };

        return (util);
    }
);