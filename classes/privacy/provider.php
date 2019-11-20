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
 * Defines {@link \mod_livepoll\privacy\provider} class.
 *
 * @package     mod_livepoll
 * @category    privacy
 * @copyright   Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_livepoll\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\metadata\provider as metadataprovider;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy API implementation for the Live poll plugin.
 *
 * @copyright  Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements metadataprovider {

    /**
     * Gets the metadata collection for this plugin.
     * @param collection $collection
     * @return collection
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_external_location_link('livepoll_firebase', [
            'userid' => 'privacy:metadata:livepoll_firebase:userid',
            'votes' => 'privacy:metadata:livepoll_firebase:votes',
        ], 'privacy:metadata:livepoll_firebase');

        return $collection;
    }
}
