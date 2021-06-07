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
 * @package     mod_discourse
 * @category    string
 * @copyright   2021 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


// mod_form.php
$string['modulename'] = 'Discourse';
$string['modulename_help'] = 'The discourse activity allows group discussions ... ';
$string['pluginadministration'] = 'Administration of discourse';

// index.php
$string['modulenameplural'] = 'Discourses';
$string['nonewmodules'] = 'No new modules';

// lib.php
$string['deletealluserdata'] = 'Delete all user data';

// capabilities
$string['discourse:addinstance'] = 'Add new discourse';
$string['discourse:viewdiscourseteacher'] = 'View disocurse as teacher';
$string['discourse:viewdiscoursestudent'] = 'View disocurse as student';
$string['discourse:editgrouptext'] = 'Submit or edit group text';
$string['discourse:switchphase'] = 'Toogle discourse phase';

// privacy
$string['privacy:metadata:discourse_participants'] = 'Contains the personal data of all discourse participants.';
$string['privacy:metadata:discourse_submissions'] = 'Contains all data related to discourse submissions.';
$string['privacy:metadata:discourse_participants:discourse'] = 'Id of the discourse activity the participant belongs to';
$string['privacy:metadata:discourse_submissions:discourse'] = 'Id of the discourse activity the submission belongs to';
$string['privacy:metadata:core_message'] = 'The discourse plugin sends messages to users and saves their content in the database.';
