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
 * Prints an instance of mod_livepoll.
 *
 * @package     mod_livepoll
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

// Course_module ID, or...
$id = optional_param('id', 0, PARAM_INT);

// ... module instance id.
$l  = optional_param('l', 0, PARAM_INT);

if ($id) {
    $cm             = get_coursemodule_from_id('livepoll', $id, 0, false, MUST_EXIST);
    $course         = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('livepoll', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($l) {
    $moduleinstance = $DB->get_record('livepoll', array('id' => $n), '*', MUST_EXIST);
    $course         = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm             = get_coursemodule_from_instance('livepoll', $moduleinstance->id, $course->id, false, MUST_EXIST);
} else {
    print_error(get_string('missingidandcmid', mod_livepoll));
}

require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);

$event = \mod_livepoll\event\course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $modulecontext
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('livepoll', $moduleinstance);
$event->trigger();

$PAGE->set_url('/mod/livepoll/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

echo $OUTPUT->header();

$userkey = sha1($course->id.'_'.$moduleinstance->id.'_'.$USER->id);
$pollkey = sha1($course->id.'_'.$moduleinstance->id);

$optkeys = ['a', 'b', 'c', 'd'];
$pollopts = [];
$templateopts = [];
foreach ($optkeys as $optkey) {
    $optionid = 'option' . $optkey;
    $optiontxt = get_string('optionx', 'mod_livepoll', strtoupper($optkey));
    if (empty($moduleinstance->{$optionid})) {
        continue;
    }

    $pollopts[$optionid] = $moduleinstance->{$optionid};
    $templateopts[] = (object) [
        'title' => $optiontxt,
        'optionid' => $optionid,
        'value' => $moduleinstance->{$optionid},
    ];
}

$canvote = has_capability('mod/livepoll:vote', $modulecontext);
$cancontrol = has_capability('mod/livepoll:addinstance', $modulecontext);

echo $OUTPUT->render_from_template('mod_livepoll/header',
    (object) [
        'name' => $moduleinstance->name,
        'intro' => $moduleinstance->intro,
    ]);

// Performing a rendering strategy.
$strategyid = $moduleinstance->resultrendering;
$strategyclass = '\\mod_livepoll\\result\\' . $strategyid . '_strategy';

/** @var \mod_livepoll\result\rendering_strategy $strategy */
$strategy = new $strategyclass();
$elements = $strategy->get_results_to_render();
foreach ($elements as $elem) {
    echo $OUTPUT->render_from_template('mod_livepoll/' . $elem . '_result',
        (object) [
            'options' => $templateopts
        ]);
}

if ($canvote) {
    echo $OUTPUT->render_from_template('mod_livepoll/voting_buttons',
        (object) [
            'options' => $templateopts
        ]);
}

if ($cancontrol) {
    $controls = [
        'closevoting',
        'highlightanswer',
    ];
    $controlopts = [];
    foreach ($controls as $control) {
        $controlopts[] = (object) [
            'title' => get_string('control:' . $control, 'mod_livepoll', strtoupper($optkey)),
            'optionid' => 'livepoll_' . $control,
        ];
    }

    echo $OUTPUT->render_from_template('mod_livepoll/control_buttons',
        (object) [
            'options' => $controlopts
        ]);
}

echo $OUTPUT->render_from_template('mod_livepoll/firebase_libraries', (object) []);
$PAGE->requires->js_call_amd('mod_livepoll/livepoll-lazy', 'init', [
    'apiKey' => get_config('livepoll', 'firebaseapikey'),
    'authDomain' => get_config('livepoll', 'firebaseauthdomain'),
    'databaseURL' => get_config('livepoll', 'firebasedatabaseurl'),
    'projectID' => get_config('livepoll', 'firebaseprojectid'),
    'pollKey' => $pollkey,
    'userKey' => $userkey,
    'options' => $pollopts,
    'correctOption' => $moduleinstance->correctoption,
    'resultsToRender' => $elements,
]);

echo $OUTPUT->footer();
