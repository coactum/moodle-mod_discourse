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
 * Prints an instance of mod_discourse.
 *
 * @package     mod_discourse
 * @copyright   2021 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once($CFG->dirroot . '/mod/discourse/locallib.php');

// Course_module ID.
$id = optional_param('id', null, PARAM_INT);

// Module instance ID as alternative.
$d  = optional_param('d', null, PARAM_INT);

/*
if ($id) {
    $cm             = get_coursemodule_from_id('discourse', $id, 0, false, MUST_EXIST);
    $course         = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('discourse', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($d) {
    $moduleinstance = $DB->get_record('discourse', array('id' => $d), '*', MUST_EXIST);
    $course         = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm             = get_coursemodule_from_instance('discourse', $moduleinstance->id, $course->id, false, MUST_EXIST);
} else {
    throw new moodle_exception('missingparameter');
} */

$discourse = discourse::get_discourse_instance($id, $d);

$moduleinstance = $discourse->get_module_instance();
$course = $discourse->get_course();
$context = $discourse->get_context();
$cm = $discourse->get_course_module();

require_login($course, true, $cm);

$event = \mod_discourse\event\course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $context
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('discourse', $moduleinstance);
$event->trigger();

$PAGE->set_url('/mod/discourse/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

echo $OUTPUT->header();

echo 'Testinhalt <br>';

global $DB;

var_dump($DB->get_records('discourse_participants', array('discourse' => $moduleinstance->id)));
var_dump('<br>');
var_dump(groups_get_all_groups($course->id, 0, $moduleinstance->groupingid));

echo $OUTPUT->footer();
