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
 * All the steps to restore mod_livepoll are defined here.
 *
 * @package     mod_livepoll
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Defines the structure step to restore one mod_livepoll activity.
 *
 * @package     mod_livepoll
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_livepoll_activity_structure_step extends restore_activity_structure_step {

    /**
     * {@inheritdoc}
     */
    protected function define_structure() {
        $paths = array();
        $paths[] = new restore_path_element('livepoll', '/activity/livepoll');

        return $this->prepare_activity_structure($paths);
    }

    /**
     * Process a data element for restoration.
     *
     * @param mixed $data
     * @throws base_step_exception
     * @throws dml_exception
     */
    protected function process_livepoll($data) {
        global $DB;

        $data = (object)$data;
        $data->course = $this->get_courseid();

        // Any changes to the list of dates that needs to be rolled should be same during course restore and course reset.
        // See MDL-9367.
        $data->timeopen = $this->apply_date_offset($data->timeopen);
        $data->timeclose = $this->apply_date_offset($data->timeclose);

        // Insert the livepoll record.
        $newitemid = $DB->insert_record('livepoll', $data);
        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Defines post-execution actions.
     */
    protected function after_execute() {
        // Add choice related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_livepoll', 'intro', null);
    }
}
