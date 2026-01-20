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
 * Plugin upgrade steps are defined here.
 *
 * @package     mod_livepoll
 * @category    upgrade
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute mod_livepoll upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_livepoll_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2018071303) {
        // New field for storing the result rendering.
        $table = new xmldb_table('livepoll');

        $field = new xmldb_field(
            'resultrendering',
            XMLDB_TYPE_CHAR,
            '255',
            null,
            XMLDB_NOTNULL,
            null,
            'barchart_text',
            'correctoption');

        // Conditionally launch add field content.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Live poll savepoint reached.
        upgrade_mod_savepoint(true, 2018071303, 'livepoll');
    }

    if ($oldversion < 2018071304) {
        if (!empty($projectid = get_config('livepoll', 'firebaseprojectid'))) {
            // Auto-calculate settings based on project id.
            set_config(
                'firebaseauthdomain',
                "$projectid.firebaseapp.com",
                'livepoll'
            );

            $databaseurl = "https://$projectid.firebaseio.com";
            if (in_array('curl', get_loaded_extensions())) {
                // Going a bit further with the realtime DB if cURL extension is present.
                $handle = curl_init($databaseurl);
                curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

                /* Get the HTML or whatever is linked in $url. */
                $response = curl_exec($handle);

                /* Check for 404 (file not found). */
                $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                if ($httpCode == 404) {
                    // New default realtime database url.
                    // See this: https://firebase.google.com/docs/projects/learn-more#project-id.
                    $databaseurl = "https://$projectid-default-rtdb.firebaseio.com";
                }
                curl_close($handle);
            }

            set_config(
                'firebasedatabaseurl',
                $databaseurl,
                'livepoll'
            );
        }
        // Live poll savepoint reached.
        upgrade_mod_savepoint(true, 2018071304, 'livepoll');
    }

    return true;
}
