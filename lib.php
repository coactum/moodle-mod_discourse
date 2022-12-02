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
 * Library of interface functions and constants.
 *
 * @package     mod_discourse
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Indicates API features that the discourse supports.
 *
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_SHOW_DESCRIPTION
 * @uses FEATURE_BACKUP_MOODLE2
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_MOD_PURPOSE
 * @param string $feature
 * @return mixed True if yes (some features may use other values)
 */
function discourse_supports($feature) {
    // Adding support for FEATURE_MOD_PURPOSE (MDL-71457) and providing backward compatibility (pre-v4.0).
    if (defined('FEATURE_MOD_PURPOSE') && $feature === FEATURE_MOD_PURPOSE) {
        return MOD_PURPOSE_COLLABORATION;
    }

    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_GROUPS:
            return true;
        case FEATURE_GROUPINGS:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_discourse into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $discourse An object from the form.
 * @param mod_discourse_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function discourse_add_instance($discourse, mod_discourse_mod_form $mform = null) {
    global $DB;

    $discourse->timecreated = time();

    $discourse->id = $DB->insert_record('discourse', $discourse);

    return $discourse->id;
}

/**
 * Creating initial grouping and groups and assigning participants to them after the creation of a discourse instance.
 * This function is called by the course_module_created observer.
 *
 * @param object $context the discourse context
 * @param stdClass $discourse The discourse object
 * @return void
 */
function discourse_instance_created($context, $discourse) {

    $enrolledusers = get_enrolled_users($context, 'mod/discourse:potentialparticipant');
    $discourse->create_groups_and_grouping($enrolledusers);
}

/**
 * Updates an instance of the mod_discourse in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $discourse An object from the form in mod_form.php.
 * @param mod_discourse_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function discourse_update_instance($discourse, mod_discourse_mod_form $mform = null) {
    global $DB, $CFG;

    $discourse->timemodified = time();
    $discourse->id = $discourse->instance;

    $cmid = $discourse->cmid;
    $oldname = $discourse->oldname;
    unset($discourse->cmid);
    unset($discourse->oldname);

    // Rename groups.
    if (isset($cmid)) {
        if (isset($oldname) && ($discourse->name != $oldname) && $discourse->groupingid) {

            require_once("$CFG->dirroot/group/lib.php");

            list ($course, $cm) = get_course_and_cm_from_cmid($cmid, 'discourse');
            $groups = groups_get_activity_allowed_groups($cm);

            foreach ($groups as $group) {
                $group->name = str_replace($oldname, $discourse->name, $group->name);

                // Needed for groups_update_group.
                if (strpos($group->idnumber, 'phase_1') == false) {
                    $group->enablemessaging = 1;
                } else {
                    $group->enablemessaging = 0;
                }

                groups_update_group($group);
            }

            $grouping = groups_get_grouping($discourse->groupingid);
            $grouping->name = $discourse->name;
            groups_update_grouping($grouping);
        }

    }

    return $DB->update_record('discourse', $discourse);
}

/**
 * Removes an instance of the mod_discourse from the database.
 *
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function discourse_delete_instance($id) {
    global $DB, $CFG;

    require_once("$CFG->dirroot/group/lib.php");

    if (!$DB->record_exists('discourse', array('id' => $id))) {
        return false;
    } else {
        $moduleinstance = $DB->get_record('discourse', array('id' => $id));
    }

    if ($moduleinstance->groupingid != 0) {
        // Delete discourse groups.
        $groups = groups_get_all_groups($moduleinstance->course, 0, $moduleinstance->groupingid);

        foreach ($groups as $group) {
            groups_delete_group($group);
        }

        // Check if grouping is in same course as module instance
        // (should not be neccessary but better be safe then sorry).
        $grouping = groups_get_grouping($moduleinstance->groupingid);
        if (!empty($grouping) && $grouping->courseid == $moduleinstance->course) {
            // Delete discourse grouping.
            groups_delete_grouping($moduleinstance->groupingid);
        }
    }

    // Delete discourse participants.
    if ($DB->record_exists('discourse_participants', array('discourse' => $id))) {
        $DB->delete_records('discourse_participants', array('discourse' => $id));
    }

    // Delete discourse submissions.
    if ($DB->record_exists('discourse_submissions', array('discourse' => $id))) {
        $DB->delete_records('discourse_submissions', array('discourse' => $id));
    }

    // Delete discourse instance.
    $DB->delete_records('discourse', array('id' => $id));

    return true;
}

/**
 * Called by course/reset.php.
 *
 * @param object $mform Form passed by reference.
 */
function discourse_reset_course_form_definition(&$mform) {
    $mform->addElement('header', 'discourseheader', get_string('modulenameplural', 'mod_discourse'));

    $mform->addElement('checkbox', 'reset_discourse_all', get_string('deletealluserdata', 'mod_discourse'));
}

/**
 * Course reset form defaults.
 *
 * @param object $course course object.
 * @return array array Array with the default values.
 */
function discourse_reset_course_form_defaults($course) {
    return array('reset_discourse_all' => 1);
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * This function will remove all userdata from the specified discourse.
 *
 * @param object $data The data submitted from the reset course.
 * @return array $status Status array.
 */
function discourse_reset_userdata($data) {

    $status = array();

    if (!empty($data->reset_discourse_all)) {
        global $DB;

        $componentstr = get_string('modulenameplural', 'discourse');

        $params = array('course' => $data->courseid);

        $rs = $DB->get_recordset('discourse', $params);

        if ($rs->valid()) {

            foreach ($rs as $record) {
                if ($DB->record_exists('discourse_participants', array('discourse' => $record->id))) {
                    $DB->delete_records('discourse_participants', array('discourse' => $record->id));
                }

                if ($DB->record_exists('discourse_submissions', array('discourse' => $record->id))) {
                    $DB->delete_records('discourse_submissions', array('discourse' => $record->id));
                }

                // Delete discourse groups.
                $groups = groups_get_all_groups($record->course, 0, $record->groupingid);

                foreach ($groups as $group) {
                    groups_delete_group($group);
                }

                // Check if grouping is in same course as module instance
                // (should not be neccessary but better be safe then sorry).
                $grouping = groups_get_grouping($record->groupingid);
                if (!empty($grouping) && $grouping->courseid == $record->course) {
                    // Delete discourse grouping.
                    groups_delete_grouping($record->groupingid);
                }
            }

            $rs->close();

            $status[] = array('component' => $componentstr, 'item' => get_string('userdatadeleted', 'discourse'), 'error' => false);
        }
    }

    // Updating dates - shift may be negative too.
    if ($data->timeshift) {
        // Any changes to the list of dates that needs to be rolled should be same during course restore and course reset.
        $shifterror = !shift_course_mod_dates('discourse', array('deadlinephaseone', 'deadlinephasetwo', 'deadlinephasethree', 'deadlinephasefour'),
            $data->timeshift, $data->courseid);
        $status[] = array('component' => $componentstr, 'item' => get_string('datechanged'), 'error' => $shifterror);
    }

    return $status;
}

/**
 * Returns all other caps from other modules or sub systems used in the module
 *
 * @return array array()
 */
function discourse_get_extra_capabilities() {
    return array();
}

/**
 * Returns the lists of all browsable file areas within the given module context.
 *
 * The file area 'intro' for the activity introduction field is added automatically
 * by file_browser::get_file_info_context_module().
 *
 * @package     mod_discourse
 * @category    files
 *
 * @param stdClass $course The course.
 * @param stdClass $cm The Course module.
 * @param stdClass $context The context.
 * @return string[].
 */
function discourse_get_file_areas($course, $cm, $context) {
    return array();
}

/**
 * File browsing support for mod_discourse file areas.
 *
 * @package     mod_discourse
 * @category    files
 *
 * @param file_browser $browser The Browser.
 * @param array $areas Areas.
 * @param stdClass $course The course.
 * @param stdClass $cm The course module.
 * @param stdClass $context The context.
 * @param string $filearea Filearea.
 * @param int $itemid Item id.
 * @param string $filepath File path.
 * @param string $filename File name.
 * @return file_info Instance or null if not found.
 */
function discourse_get_file_info($browser, $areas, $course, $cm, $context, $filearea, $itemid, $filepath, $filename) {
    return null;
}

/**
 * Serves the files from the mod_discourse file areas.
 *
 * @package     mod_discourse
 * @category    files
 *
 * @param stdClass $course The course object.
 * @param stdClass $cm The course module object.
 * @param stdClass $context The mod_discourse's context.
 * @param string $filearea The name of the file area.
 * @param array $args Extra arguments (itemid, path).
 * @param bool $forcedownload Whether or not force download.
 * @param array $options Additional options affecting the file serving.
 */
function discourse_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, $options = array()) {
    global $DB, $CFG;

    if ($context->contextlevel != CONTEXT_MODULE) {
        send_file_not_found();
    }

    require_login($course, true, $cm);
    send_file_not_found();
}
