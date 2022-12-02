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
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_discourse\output\discourse_view;
use core\output\notification;

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once($CFG->dirroot . '/mod/discourse/locallib.php');

// Course_module ID.
$id = optional_param('id', null, PARAM_INT);

// Module instance ID as alternative.
$d  = optional_param('d', null, PARAM_INT);

// New phase that discourse should switch to.
$newphase  = optional_param('newphase', null, PARAM_INT);

$discourse = discourse::get_discourse_instance($id, $d);

$moduleinstance = $discourse->get_module_instance();
$course = $discourse->get_course();
$context = $discourse->get_context();
$cm = $discourse->get_course_module();

require_login($course, true, $cm);

$canswitchphase = has_capability('mod/discourse:switchphase', $context);

if (isset($newphase) && $canswitchphase) {
    require_sesskey();

    global $DB;
    switch ($newphase) {
        case 1:
            $moduleinstance->activephase = 1;
            $activephaseone = true;
            $activephasetwo = false;
            $activephasethree = false;
            $activephasefour = false;
            break;
        case 2:
            $moduleinstance->activephase = 2;
            $activephaseone = false;
            $activephasetwo = true;
            $activephasethree = false;
            $activephasefour = false;
            break;
        case 3:
            $moduleinstance->activephase = 3;
            $activephaseone = false;
            $activephasetwo = false;
            $activephasethree = true;
            $activephasefour = false;
            break;
        case 4:
            $moduleinstance->activephase = 4;
            $activephaseone = false;
            $activephasetwo = false;
            $activephasethree = false;
            $activephasefour = true;
            break;
        default:
            $activephaseone = true;
            $activephasetwo = false;
            $activephasethree = false;
            $activephasefour = false;
            break;
    }

    $DB->update_record('discourse', $moduleinstance);

    redirect(new moodle_url('/mod/discourse/view.php', array('id' => $id)), get_string('phaseswitched', 'mod_discourse'), null, notification::NOTIFY_SUCCESS);

}

$event = \mod_discourse\event\course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $context
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('discourse', $moduleinstance);
$event->trigger();

$PAGE->set_url('/mod/discourse/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name) . ' - ' . get_string('view', 'mod_discourse'));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

if ($CFG->branch < 41) {
    $PAGE->force_settings_menu();
}

$navbar = $PAGE->navbar->add(get_string('view', 'mod_discourse'), $PAGE->url);

echo $OUTPUT->header();

if ($CFG->branch < 41) {
    echo $OUTPUT->heading(get_string('modulename', 'mod_discourse').': ' . format_string($moduleinstance->name), 3);

    if ($moduleinstance->intro) {
        echo $OUTPUT->box(format_module_intro('discourse', $moduleinstance, $cm->id), 'generalbox mod_introbox', 'newmoduleintro');
    }
}

// Set active phase.
switch ($moduleinstance->activephase) {
    case 1:
        $activephaseone = true;
        $activephasetwo = false;
        $activephasethree = false;
        $activephasefour = false;
        break;
    case 2:
        $activephaseone = false;
        $activephasetwo = true;
        $activephasethree = false;
        $activephasefour = false;
        break;
    case 3:
        $activephaseone = false;
        $activephasetwo = false;
        $activephasethree = true;
        $activephasefour = false;
        break;
    case 4:
        $activephaseone = false;
        $activephasetwo = false;
        $activephasethree = false;
        $activephasefour = true;
        break;
    default:
        $activephaseone = true;
        $activephasetwo = false;
        $activephasethree = false;
        $activephasefour = false;
        break;
}

$caneditphase = has_capability('mod/discourse:editphase', $context);
$canviewgroupparticipants = has_capability('mod/discourse:viewgroupparticipants', $context);

if (has_capability('mod/discourse:viewallgroups', $context) || groups_get_activity_groupmode($cm, $course) == 2) {
    $canviewallgroups = true;
} else {
    $canviewallgroups = false;
}

if (time() > $moduleinstance->deadlinephaseone && $moduleinstance->activephase == 1) {
    $shouldswitchphase = 2;
} else if (time() > $moduleinstance->deadlinephasetwo && $moduleinstance->activephase == 2) {
    $shouldswitchphase = 3;
} else if (time() > $moduleinstance->deadlinephasethree && $moduleinstance->activephase == 3) {
    $shouldswitchphase = 4;
} else {
    $shouldswitchphase = false;
}

if (!groups_get_grouping($moduleinstance->groupingid)) {
    echo $OUTPUT->notification(get_string('groupingmaybedeleted', 'mod_discourse'), notification::NOTIFY_ERROR);
}

if (groups_get_activity_groupmode($cm) == 2 && has_capability('mod/discourse:viewallgroups', $context)) {
    echo $OUTPUT->notification(get_string('groupmodevisiblegroups', 'mod_discourse'), notification::NOTIFY_WARNING);
}

global $USER;
$userid = $USER->id;

$hintphaseoneshortened = (strlen($moduleinstance->hintphaseone) >= 250) ? true : false;
$hintphasetwoshortened = (strlen($moduleinstance->hintphasetwo) >= 250) ? true : false;
$hintphasethreeshortened = (strlen($moduleinstance->hintphasethree) >= 250) ? true : false;
$hintphasefourshortened = (strlen($moduleinstance->hintphasefour) >= 250) ? true : false;

if ($moduleinstance->hintphaseone || $moduleinstance->hintphasetwo
    || $moduleinstance->hintphasethree || $moduleinstance->hintphasefour) {
    $phasehints = true;
} else {
    $phasehints = false;
}

$page = new discourse_view($cm->id, $discourse->get_groups(), $moduleinstance->autoswitch, $activephaseone, $activephasetwo,
    $activephasethree, $activephasefour, $moduleinstance->hintphaseone, $moduleinstance->hintphasetwo, $moduleinstance->hintphasethree,
    $moduleinstance->hintphasefour, $hintphaseoneshortened, $hintphasetwoshortened, $hintphasethreeshortened, $hintphasefourshortened,
    $moduleinstance->deadlinephaseone, $moduleinstance->deadlinephasetwo, $moduleinstance->deadlinephasethree, $moduleinstance->deadlinephasefour,
    $caneditphase, $canswitchphase, $canviewallgroups, $canviewgroupparticipants, $shouldswitchphase, $userid, sesskey(), $phasehints);

echo $OUTPUT->render($page);

echo $OUTPUT->footer();
