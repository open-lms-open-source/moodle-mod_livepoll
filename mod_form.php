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
 * The main mod_livepoll configuration form.
 *
 * @package     mod_livepoll
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form.
 *
 * @package    mod_livepoll
 * @copyright  Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_livepoll_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('livepollname', 'mod_livepoll'), array('size' => '64'));

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }

        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'livepollname', 'mod_livepoll');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // Adding the rest of mod_livepoll settings, spreading all them into this fieldset.
        $mform->addElement('header', 'livepollfieldset', get_string('livepollfieldset', 'mod_livepoll'));
        $mform->addHelpButton('livepollfieldset', 'livepollfieldset', 'mod_livepoll');
        // Adding option fields.
        $options = ['a', 'b', 'c', 'd'];
        $required = ['a', 'b'];
        $selectoptions = [];
        foreach ($options as $option) {
            $optionid = 'option' . $option;
            $optiontxt = get_string('optionx', 'mod_livepoll', strtoupper($option));
            $mform->addElement('text', $optionid, $optiontxt, array('size' => '64'));
            $mform->setType($optionid, PARAM_TEXT);
            if (in_array($option, $required)) {
                $mform->addRule($optionid, null, 'required', null, 'client');
            }
            $mform->addRule($optionid, get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
            // Populate options for correct option select.
            $selectoptions[$optionid] = $optiontxt;
        }

        $mform->addElement('select', 'correctoption', get_string('correctoption', 'mod_livepoll'), $selectoptions);
        $mform->addRule('correctoption', null, 'required', null, 'client');

        $strategies = [
            'barchart_text' => get_string('barchart_text_result', 'mod_livepoll'),
            'piechart_text' => get_string('piechart_text_result', 'mod_livepoll'),
            'doughnutchart_text' => get_string('doughnutchart_text_result', 'mod_livepoll'),
            'polarareachart_text' => get_string('polarareachart_text_result', 'mod_livepoll'),
            'radarchart_text' => get_string('radarchart_text_result', 'mod_livepoll'),
            'text_only' => get_string('text_only_result', 'mod_livepoll'),
        ];
        $mform->addElement('select', 'resultrendering', get_string('resultrendering', 'mod_livepoll'), $strategies);
        $mform->addRule('resultrendering', null, 'required', null, 'client');

        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }

    /**
     * Enforce validation rules here
     *
     * @param array $data array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array
     **/
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Check that correct option has been set.
        if (empty($data[$data['correctoption']])) {
            $errors['correctoption'] = get_string('correctoptioninvalid', 'mod_livepoll');
        }
        return $errors;
    }
}
