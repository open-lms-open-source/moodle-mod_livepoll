<?php
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
 * Mobile output class for Live poll.
 *
 * @package    mod_livepoll
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_livepoll\output;
defined('MOODLE_INTERNAL') || die();

use context_module;

/**
 * Mobile output class for Live poll.
 *
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mobile {

    /**
     * Returns the choice group course view for the mobile app.
     * @param  array $args Arguments from tool_mobile_get_content WS
     *
     * @return array HTML, javascript and otherdata
     */
    public static function mobile_course_view($args) {
        global $OUTPUT, $DB;
        $args = (object)$args;
        $cm = get_coursemodule_from_id('livepoll', $args->cmid);
        // Capabilities check.
        require_login($args->courseid, false, $cm, true, true);
        $context = context_module::instance($cm->id);
        require_capability('mod/livepoll:view', $context);
        $canvote = has_capability('mod/livepoll:vote', $context);
        $livepoll = $DB->get_record('livepoll', array('id' => $cm->instance), '*', MUST_EXIST);

        $optkeys = ['a', 'b', 'c', 'd'];
        $templateopts = [];
        foreach ($optkeys as $optkey) {
            $optionid = 'option' . $optkey;
            $optiontxt = get_string('optionx', 'mod_livepoll', strtoupper($optkey));
            if (empty($livepoll->{$optionid})) {
                continue;
            }

            $templateopts[] = (object) [
                'title' => $optiontxt,
                'optionid' => $optionid,
                'value' => $livepoll->{$optionid},
            ];
        }

        // Format name and intro.
        $livepoll->name = format_string($livepoll->name);
        list($livepoll->intro, $livepoll->introformat) = external_format_text(
            $livepoll->intro,
            $livepoll->introformat,
            $context->id,
            'mod_livepoll',
            'intro'
        );
        $data = [
            'cmurl' => $cm->url,
            'cmid' => $cm->id,
            'courseid' => $args->courseid,
            'livepoll' => $livepoll,
            'canvote' => $canvote,
            'options' => $templateopts,
        ];
        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('mod_livepoll/mobile_view_page', $data),
                ],
            ],
            'javascript' => '',
            'otherdata' => '',
        ];
    }
}