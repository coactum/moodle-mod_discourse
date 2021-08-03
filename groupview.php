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
 * Prints the group view of mod_discourse.
 *
 * @package     mod_discourse
 * @copyright   2021 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_discourse\output\discourse_groupview;
use core\output\notification;

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once($CFG->dirroot . '/mod/discourse/locallib.php');
require_once($CFG->dirroot . '/mod/discourse/submit_form.php');

// Course_module ID.
$id = optional_param('id', null, PARAM_INT);

// Module instance ID as alternative.
$d  = optional_param('d', null, PARAM_INT);

// ID of the group to be viewed.
$groupid  = required_param('group', PARAM_INT);

$discourse = discourse::get_discourse_instance($id, $d);

$moduleinstance = $discourse->get_module_instance();
$course = $discourse->get_course();
$context = $discourse->get_context();
$cm = $discourse->get_course_module();

require_login($course, true, $cm);

$PAGE->set_url('/mod/discourse/groupview.php', array('id' => $cm->id, 'group' => $groupid));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$PAGE->requires->js_call_amd('mod_discourse/groupview', 'init');

$navbar = $PAGE->navbar->add(get_string('groupview', 'mod_discourse'), $PAGE->url);

$group = $discourse->get_group($groupid);

if (!$group) {
    redirect(new moodle_url('/mod/discourse/view.php', array('id' => $id)), get_string('groupinvalid', 'mod_discourse'), null, notification::NOTIFY_ERROR);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('modulename', 'mod_discourse').': ' . format_string($moduleinstance->name), 3);

// Instantiate form.
$mform = new submit_form();

if ($fromform = $mform->get_data()) {
    // In this case you process validated data. $mform->get_data() returns data posted in form.

    global $DB;

    if (isset($fromform->submissionid) && $fromform->submissionid === 0) {

        $submission = new stdClass();
        $submission->discourse = (int) $moduleinstance->id;
        $submission->groupid = $fromform->group;
        $submission->submission = $fromform->submission['text'];
        $submission->editlock = 0;
        $submission->currentversion = 1;
        $submission->format = (int) $fromform->submission['format'];
        $submission->timecreated = time();
        $submission->timemodified = null;

        $DB->insert_record('discourse_submissions', $submission);

    } else if (isset($fromform->submissionid)) {
        $submission = $DB->get_record('discourse_submissions', array('discourse' => $moduleinstance->id, 'groupid' => $fromform->group, 'id' => $fromform->submissionid));
        $submission->submission = $fromform->submission['text'];
        $submission->editlock = 0;
        $submission->currentversion += 1;
        $submission->format = (int) $fromform->submission['format'];
        $submission->timemodified = time();

        $DB->update_record('discourse_submissions', $submission);
    }

    redirect(new moodle_url('/mod/discourse/groupview.php', array('id' => $id, 'group' => $fromform->group)));

} else {
    // This branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    if (isset($group->formersubmissions) && $group->formersubmissions) {
        $formersubmission = new stdClass();
        $formersubmission->text = '';
        $formersubmission->format = 1;

        foreach ($group->formersubmissions as $submission) {

            if (isset($submission) && $submission) {
                $groupname = groups_get_group_name($submission->groupid);

                $formersubmission->text .= '<strong>' . $groupname . '</strong><br>' . $submission->submission . '<br>';
                $formersubmission->format = $submission->format;
            }

        }
    }

    // Set default data.
    if (isset($group->submission) && $group->submission) { // Default data if group has made submission.
        $mform->set_data(array('id' => $id, 'group' => $groupid, 'submissionid' => $group->submission->id,
        'submission' => ['text' => $group->submission->submission, 'format' => $group->submission->format]));
    } else if (isset($formersubmission)) { // Default data if group has merged submissions from former groups of the participants.
        $mform->set_data(array('id' => $id, 'group' => $groupid, 'submissionid' => 0,
        'submission' => ['text' => $formersubmission->text, 'format' => $formersubmission->format]));
    } else {
        $mform->set_data(array('id' => $id, 'group' => $groupid, 'submissionid' => 0));
    }

    if (has_capability('mod/discourse:editsubmission', $context) && $moduleinstance->activephase == $group->phase) {
        $caneditsubmission = true;
    } else {
        $caneditsubmission = false;
    }

    if ($caneditsubmission) {
        $form = $mform->render();
    } else {
        $form = '';
    }

    $canviewgroupparticipants = has_capability('mod/discourse:viewgroupparticipants', $context);

    $page = new discourse_groupview($cm->id, $group, $form, $caneditsubmission, $canviewgroupparticipants);

    // Render page and display the form.
    echo $OUTPUT->render($page);
}

echo $OUTPUT->footer();