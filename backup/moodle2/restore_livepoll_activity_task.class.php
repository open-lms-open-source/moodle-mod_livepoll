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
 * The task that provides a complete restore of mod_livepoll is defined here.
 *
 * @package     mod_livepoll
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'//mod/livepoll/backup/moodle2/restore_livepoll_stepslib.php');

/**
 * Restore task for mod_livepoll.
 * @package     mod_livepoll
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_livepoll_activity_task extends restore_activity_task {

    /**
     * {@inheritdoc}
     */
    protected function define_my_settings() {
        return;
    }

    /**
     * {@inheritdoc}
     */
    protected function define_my_steps() {
        $this->add_step(new restore_livepoll_activity_structure_step('livepoll_structure', 'livepoll.xml'));
    }

    /**
     * {@inheritdoc}
     */
    static public function define_decode_contents() {
        $contents = array();

        $contents[] = new restore_decode_content('livepoll', array('intro'), 'livepoll');

        return $contents;
    }

    /**
     * {@inheritdoc}
     */
    static public function define_decode_rules() {
        $rules = array();

        $rules[] = new restore_decode_rule('LIVEPOLLVIEWBYID', '/mod/livepoll/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('LIVEPOLLINDEX', '/mod/livepoll/index.php?id=$1', 'course');

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    static public function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('livepoll', 'add', 'view.php?id={course_module}', '{livepoll}');
        $rules[] = new restore_log_rule('livepoll', 'update', 'view.php?id={course_module}', '{livepoll}');
        $rules[] = new restore_log_rule('livepoll', 'view', 'view.php?id={course_module}', '{livepoll}');

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

        // Fix old wrong uses (missing extension).
        $rules[] = new restore_log_rule('livepoll', 'view all', 'index?id={course}', null,
            null, null, 'index.php?id={course}');
        $rules[] = new restore_log_rule('livepoll', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}
