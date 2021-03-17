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
 * Plugin strings are defined here.
 *
 * @package     mod_livepoll
 * @category    string
 * @copyright   Copyright (c) 2018 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['livepoll:addinstance'] = 'Add poll';
$string['livepoll:view'] = 'View poll';
$string['livepoll:vote'] = 'Vote on poll';
$string['livepollfieldset'] = 'Live poll settings';
$string['livepollname'] = 'Live poll name';
$string['livepollname_help'] = 'Live polls allow live voting of polls.';
$string['livepollsettings'] = 'Settings';
$string['missingidandcmid'] = 'Missing id and cmid';
$string['modulename'] = 'Live poll';
$string['modulename_help'] = 'This is the content of the help tooltip associated with the newlivepollname field';
$string['modulenameplural'] = 'Live polls';
$string['newlivepoll'] = 'New Live poll';
$string['nonewmodules'] = 'No new Live polls';
$string['pluginadministration'] = 'Live poll administration';
$string['pluginname'] = 'Live poll';
$string['privacy:metadata:livepoll_firebase'] = 'Live poll creates a hashes based on course and user data in firebase to identify polls and votes.';
$string['privacy:metadata:livepoll_firebase:userid'] = 'The userid is used when creating the hash that identifies poll votes.';
$string['privacy:metadata:livepoll_firebase:votes'] = 'The selected option made for a poll.';
$string['view'] = 'View';
$string['firebaseapikey'] = 'Firebase API Key';
$string['firebaseapikey_desc'] = 'You can get your API key from your Firebase workspace.';
$string['firebaseprojectid'] = 'Firebase Project ID';
$string['firebaseprojectid_desc'] = 'You can get your Project ID from your Firebase workspace.';
$string['firebaseauthdomain'] = 'Firebase Auth Domain';
$string['firebaseauthdomain_desc'] = 'You can get your Auth Domain from your Firebase workspace.';
$string['firebasedatabaseurl'] = 'Firebase Database URL';
$string['firebasedatabaseurl_desc'] = 'You can get your Database URL from your Firebase workspace.';
$string['optionx'] = 'Option {$a}';
$string['correctoption'] = 'Correct option';
$string['livepollfieldset_help'] = 'Please enter the values for the options, A and B are required. Also, the correct option needs to be set.';
$string['correctoptioninvalid'] = 'The selected correct option must have a value.';
$string['yourvote'] = 'Your vote';
$string['vote'] = 'Vote';
$string['loading'] = 'Loading';
$string['resultrendering'] = 'Result rendering';
$string['resultrenderinginvalid'] = 'The selected result rendering option must have a value.';
$string['barchart_text_result'] = 'Bar chart and text';
$string['text_only_result'] = 'Text only';
$string['piechart_text_result'] = 'Pie chart and text';
$string['doughnutchart_text_result'] = 'Doughnut chart and text';
$string['polarareachart_text_result'] = 'Polar area chart and text';
$string['radarchart_text_result'] = 'Radar chart and text';
$string['totalvotes'] = 'Total number of votes: ';

$string['votecontrol'] = 'Vote control';
$string['control:closevoting'] = 'Close voting';
$string['control:highlightanswer'] = 'Highlight answer';
$string['control:votinghasclosed'] = 'Voting has been closed by moderator.';
$string['control:votinghasclosed:close'] = 'Close message';
