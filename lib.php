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
 * Library of interface functions and constants.
 *
 * @package     mod_livepoll
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function livepoll_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_livepoll into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 * @param object $livepoll An object from the form.
 * @return bool|int The id of the newly inserted record.
 * @throws dml_exception
 */
function livepoll_add_instance($livepoll) {
    global $DB;

    $livepoll->timecreated = time();
    $id = $DB->insert_record('livepoll', $livepoll);

    return $id;
}

/**
 * Updates an instance of the mod_livepoll in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $livepoll  An object from the form in mod_form.php.
 * @return bool True if successful, false otherwise.
 * @throws dml_exception
 */
function livepoll_update_instance($livepoll) {
    global $DB;

    $livepoll->timemodified = time();
    $livepoll->id = $livepoll->instance;

    return $DB->update_record('livepoll', $livepoll);
}

/**
 * Removes an instance of the mod_livepoll from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function livepoll_delete_instance($id) {
    global $DB;

    $exists = $DB->get_record('livepoll', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('livepoll', array('id' => $id));

    return true;
}
