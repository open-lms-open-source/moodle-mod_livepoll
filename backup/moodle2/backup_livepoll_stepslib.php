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
 * Backup steps for mod_livepoll are defined here.
 *
 * @package     mod_livepoll
 * @category    backup
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete structure for backup, with file and id annotations.
 *
 * @package     mod_livepoll
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_livepoll_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the structure of the resulting xml file.
     *
     * @return backup_nested_element The structure wrapped by the common 'activity' element.
     */
    protected function define_structure() {
        // The Live Poll module stores no user info.

        // Define each element separated.
        $livepoll = new backup_nested_element('livepoll', ['id'], [
            'name', 'intro', 'introformat', 'timemodified',
            'optiona', 'optionb', 'optionc', 'optiond', 'correctoption']);

        // Build the tree.
        // Nothing here for Live Polls.

        // Define sources.
        $livepoll->set_source_table('livepoll', ['id' => backup::VAR_ACTIVITYID]);

        // Define id annotations.
        // Module has no id annotations.

        // Define file annotations.
        $livepoll->annotate_files('mod_livepoll', 'intro', null); // This file area hasn't itemid.

        // Return the root element (livepoll), wrapped into standard activity structure.
        return $this->prepare_activity_structure($livepoll);
    }
}
