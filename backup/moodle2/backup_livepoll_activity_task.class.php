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
 * The task that provides all the steps to perform a complete backup is defined here.
 *
 * @package     mod_livepoll
 * @category    backup
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/mod/livepoll/backup/moodle2/backup_livepoll_stepslib.php');
require_once($CFG->dirroot.'/mod/livepoll/backup/moodle2/backup_livepoll_settingslib.php');

/**
 * The class provides all the settings and steps to perform one complete backup of mod_livepoll.
 * @package     mod_livepoll
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_livepoll_activity_task extends backup_activity_task {

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
        $this->add_step(new backup_livepoll_activity_structure_step('livepoll_structure', 'livepoll.xml'));
    }

    /**
     * Encodes content links.
     * @param string $content
     * @return string|string[]|null
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, "/");

        // Link to the list of choices.
        $search = "/(" . $base ."\/mod\/livepoll\/index.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@LIVEPOLLINDEX*$2@$', $content);

        // Link to choice view by moduleid.
        $search = "/(" . $base . "\/mod\/livepoll\/view.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@LIVEPOLLVIEWBYID*$2@$', $content);

        return $content;
    }
}
