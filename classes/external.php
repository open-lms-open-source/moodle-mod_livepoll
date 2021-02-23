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
 * Live poll module external API
 *
 * @package    mod_livepoll
 * @category   external
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/mod/livepoll/lib.php');
/**
 * Live poll module external functions
 *
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_livepoll_external extends external_api {
    /**
     * Describes the parameters for view_livepoll.
     *
     * @return external_function_parameters
     */
    public static function view_livepoll_parameters() {
        return new external_function_parameters(
            array(
                'livepollid' => new external_value(PARAM_INT, 'Live poll instance id')
            )
        );
    }

    /**
     * Trigger the course module viewed event and update the module completion status.
     *
     * @param int $livepollid The choice group id.
     * @return array of warnings and status result
     * @throws moodle_exception
     */
    public static function view_livepoll($livepollid) {
        global $DB;
        $params = array(
            'livepollid' => $livepollid
        );
        $params = self::validate_parameters(self::view_livepoll_parameters(), $params);
        $warnings = array();
        // Request and permission validation.
        $livepoll = $DB->get_record('livepoll', array('id' => $params['livepollid']), '*', MUST_EXIST);
        list($course, $cm) = get_course_and_cm_from_instance($livepoll, 'livepoll');
        $context = context_module::instance($cm->id);
        self::validate_context($context);
        require_capability('mod/livepoll:view', $context);
        $event = \mod_livepoll\event\course_module_viewed::create(array(
            'objectid' => $livepoll->id,
            'context' => $context,
        ));
        $event->add_record_snapshot('course', $course);
        $event->add_record_snapshot('livepoll', $livepoll);
        $event->trigger();
        $result = array();
        $result['status'] = true;
        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     */
    public static function view_livepoll_returns() {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'Status: true if success'),
                'warnings' => new external_warnings()
            )
        );
    }
}
