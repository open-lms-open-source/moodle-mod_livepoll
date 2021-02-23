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
 * @package mod_livepoll
 * @copyright Copyright (c) 2018 Open LMS
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(["jquery", "mod_livepoll/result"],
    function($, Result) {
        /**
         * Text result constructor.
         * @returns {TextResult}
         * @constructor
         */
        function TextResult() {
            Result.call(this);
            return (this);
        }

        // Prototype extension.
        TextResult.prototype = Object.create(Result.prototype);

        /**
         * Renders the text result.
         * @param options
         * @param votes
         */
        TextResult.prototype.renderResult = function(options, votes) {
            var totalVotes = 0;
            $.each(options, function(optionid) {
                $("#vote-count-" + optionid).text(votes[optionid]);
                totalVotes += votes[optionid];
            });
            $(".mod-livepoll-totalvotes").text(totalVotes);
        };

        /**
         * {@inheritdoc}
         */
        TextResult.prototype.performUpdate = function(options, votes, callback) {
            this.renderResult(options, votes);
            callback();
        };

        return (TextResult);
    }
);