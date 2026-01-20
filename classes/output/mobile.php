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

/**
 * Mobile output class for Live poll.
 *
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mobile {

    public static function mobile_init(array $args): array|null
    {
        global $CFG;

        $js = file_get_contents($CFG->dirroot . '/mod/livepoll/mobile/js/lib/firebase-app.js');
        $js .= file_get_contents($CFG->dirroot . '/mod/livepoll/mobile/js/lib/firebase-auth.js');
        $js .= file_get_contents($CFG->dirroot . '/mod/livepoll/mobile/js/lib/firebase-database.js');
        $js .= file_get_contents($CFG->dirroot . '/mod/livepoll/mobile/js/ChartCtrl.min.js');
        $js .= file_get_contents($CFG->dirroot . '/mod/livepoll/mobile/js/OptionCtrl.min.js');
        $js .= file_get_contents($CFG->dirroot . '/mod/livepoll/mobile/js/init.min.js');

    return [
            'javascript' => $js,
        ];
    }

    public static function mobile_course_view($args) {
        global $CFG, $DB, $OUTPUT, $USER;


        $context = \context_module::instance($args['cmid']);
        $course = $context->get_course_context();
        $cm = get_coursemodule_from_id('livepoll', $args['cmid'], 0, false, MUST_EXIST);
        $moduleinstance = $DB->get_record('livepoll', ['id' => $cm->instance], '*', MUST_EXIST);

        $isLecturer = has_capability('mod/livepoll:addinstance', $context);

        $userkey = sha1($course->instanceid.'_'.$moduleinstance->id.'_'.$USER->id);
        $pollkey = sha1($course->instanceid.'_'.$moduleinstance->id);

        $optkeys = ['a', 'b', 'c', 'd'];
        $pollopts = [];
        foreach ($optkeys as $optkey) {
            $optionid = 'option' . $optkey;
            $optiontxt = get_string('optionx', 'mod_livepoll', strtoupper($optkey));
            if (empty($moduleinstance->{$optionid})) {
                continue;
            }

            $pollopts[$optionid] = (object) [
                'title' => $optiontxt,
                'optionid' => $optionid,
                'value' => $moduleinstance->{$optionid},
            ];
        }

        // Performing a rendering strategy.
        $strategyid = $moduleinstance->resultrendering;
        $strategyclass = '\\mod_livepoll\\result\\' . $strategyid . '_strategy';

        /** @var \mod_livepoll\result\rendering_strategy $strategy */
        $strategy = new $strategyclass();
        $elements = $strategy->get_results_to_render();

        $pollConfig = new \stdClass();
        $pollConfig->apiKey = get_config('livepoll', 'firebaseapikey');
        $pollConfig->authDomain = get_config('livepoll', 'firebaseauthdomain');
        $pollConfig->databaseURL = get_config('livepoll', 'firebasedatabaseurl');
        $pollConfig->projectID = get_config('livepoll', 'firebaseprojectid');
        $pollConfig->pollKey = $pollkey;
        $pollConfig->userKey = $userkey;
        $pollConfig->options = $pollopts;
        $pollConfig->correctOption = $moduleinstance->correctoption;
        $pollConfig->resultsToRender = $elements;
        $pollConfig->intro =  $moduleinstance->intro;


        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('mod_livepoll/mobile/latest/mobile_view_page', ['isLecturer' => $isLecturer]),
                ],
            ],
            'javascript' => file_get_contents($CFG->dirroot . '/mod/livepoll/mobile/js/page.min.js'),
            'otherdata' => json_encode($pollConfig)
        ];
    }
}