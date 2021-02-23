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
 * File containing tests for poll_handler.
 *
 * @package     mod_livepoll
 * @category    test
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * The poll_handler test class.
 *
 * @package    mod_livepoll
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_livepoll_poll_handler_testcase extends advanced_testcase {
    public function test_strategy() {
        $this->resetAfterTest(true);
        // Performing a rendering strategy.
        $strategyies = [
            'text_only' => [
                'text'
            ],
            'barchart_text' => [
                'barchart',
                'text'
            ],
        ];
        foreach ($strategyies as $strategyid => $elements) {
            $strategyclass = '\\mod_livepoll\\result\\' . $strategyid . '_strategy';

            /** @var \mod_livepoll\result\rendering_strategy $strategy */
            $strategy = new $strategyclass();
            $relements = $strategy->get_results_to_render();

            $this->assertInstanceOf(\mod_livepoll\result\rendering_strategy::class, $strategy);
            $this->assertEquals($elements, $relements);
        }
    }
}
