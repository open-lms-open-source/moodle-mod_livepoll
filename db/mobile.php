<?php
// This file is part of the Certificate module for Moodle - http://moodle.org/
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
 * Certificate module capability definition
 *
 * @package    mod_livepoll
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
$addons = [
    "mod_livepoll" => [
        "handlers" => [ // Different places where the add-on will display content.
            'courselivepoll' => [ // Handler unique name (can be anything).
                'displaydata' => [
                    'title' => 'pluginname',
                    'icon' => $CFG->wwwroot . '/mod/livepoll/pix/icon.png',
                    'class' => '',
                ],
                'delegate' => 'CoreCourseModuleDelegate', // Delegate (where to display the link to the add-on).
                'method' => 'mobile_course_view', // Main function in \mod_livepoll\output\mobile.
                'offlinefunctions' => [
                    'mobile_course_view' => [],
                ], // Function needs caching for offline.
            ],
        ],
        'lang' => [
            ['modulename', 'livepoll'],
            ['vote', 'livepoll'],
            ['loading', 'livepoll'],
        ],
    ],
];