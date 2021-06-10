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
 * @copyright   2021 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Indicates API features that the discourse supports.
 *
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_SHOW_DESCRIPTION
 * @uses FEATURE_BACKUP_MOODLE2
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @param string $feature
 * @return mixed True if yes (some features may use other values)
 */
function discourse_supports($feature) {
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
    global $DB;

    $discourse->timemodified = time();
    $discourse->id = $discourse->instance;

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
    global $DB;

    $exists = $DB->get_record('discourse', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('discourse', array('id' => $id));

    return true;
}

/**
 * Called by course/reset.php
 *
 * @param $mform form passed by reference.
 */
function discourse_reset_course_form_definition(&$mform) {
    $mform->addElement('header', 'discourseheader', get_string('modulenameplural', 'mod_discourse'));

    $mform->addElement('checkbox', 'reset_discourse_all', get_string('deletealluserdata', 'mod_discourse'));
}

/**
 * Course reset form defaults.
 *
 * @param $course course object.
 * @return array
 */
function discourse_reset_course_form_defaults($course) {
    return array('reset_discourse_all' => 1);
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * This function will remove all userdata from the specified discourse.
 *
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function discourse_reset_userdata($data) {

    $componentstr = get_string('modulenameplural', 'discourse');
    $status = array();

    $params = array($data->courseid);

    // Updating dates - shift may be negative too.
    if ($data->timeshift) {
        // Any changes to the list of dates that needs to be rolled should be same during course restore and course reset.
        shift_course_mod_dates('discourse', array('available', 'deadline'), $data->timeshift, $data->courseid);
        $status[] = array('component' => $componentstr, 'item' => get_string('datechanged'), 'error' => false);
    }

    return $status;
}

/**
 * Returns a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @param $course the course object.
 * @param $user the user object.
 * @param $mod the modulename.
 * @param $discourse the plugin instance.
 * @return object A standard object with 2 variables: info and time (last modified)
 */
function discourse_user_outline($course, $user, $mod, $discourse) {
    $return = new stdClass();
    $return->time = time();
    $return->info = '';
    return $return;
}

/**
 * Prints a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @param stdClass $course
 *            the current course record
 * @param stdClass $user
 *            the record of the user we are generating report for
 * @param cm_info $mod
 *            course module info
 * @param stdClass $discourse
 *            the module instance record
 * @return void, is supposed to echp directly
 */
function discourse_user_complete($course, $user, $mod, $discourse) {
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in discourse activities and print it out.
 * Return true if there was output, or false is there was none.
 * @param object $course
 * @param bool $viewfullnames capability
 * @param int $timestart
 * @return boolean
 */
function discourse_print_recent_activity($course, $viewfullnames, $timestart) {
    return false; // True if anything was printed, otherwise false.
}

/**
 * Prepares the recent activity data
 *
 * This callback function is supposed to populate the passed array with
 * custom activity records. These records are then rendered into HTML via
 * discourse_print_recent_mod_activity().
 *
 * @param array $activities
 *            sequentially indexed array of objects with the 'cmid' property
 * @param int $index
 *            the index in the $activities to use for the next record
 * @param int $timestart
 *            append activity since this time
 * @param int $courseid
 *            the id of the course we produce the report for
 * @param int $cmid
 *            course module id
 * @param int $userid
 *            check for a particular user's activity only, defaults to 0 (all users)
 * @param int $groupid
 *            check for a particular group's activity only, defaults to 0 (all groups)
 * @return void adds items into $activities and increases $index
 */
function discourse_get_recent_mod_activity(&$activities, &$index, $timestart, $courseid, $cmid, $userid = 0, $groupid = 0) {
}

/**
 * Prints single activity item prepared by {@see discourse_get_recent_mod_activity()}
 *
 * @param object $activity      the activity object the discourse resides in
 * @param int    $courseid      the id of the course the discourse resides in
 * @param bool   $detail        not used, but required for compatibilty with other modules
 * @param int    $modnames      not used, but required for compatibilty with other modules
 * @param bool   $viewfullnames not used, but required for compatibilty with other modules
 */
function discourse_print_recent_mod_activity($activity, $courseid, $detail, $modnames, $viewfullnames) {
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
 * @param stdClass $course.
 * @param stdClass $cm.
 * @param stdClass $context.
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
 * @param file_browser $browser.
 * @param array $areas.
 * @param stdClass $course.
 * @param stdClass $cm.
 * @param stdClass $context.
 * @param string $filearea.
 * @param int $itemid.
 * @param string $filepath.
 * @param string $filename.
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

/**
 * Extends the global navigation tree by adding mod_discourse nodes if there is a relevant content.
 *
 * This can be called by an AJAX request so do not rely on $PAGE as it might not be set up properly.
 *
 * @param navigation_node $discoursenode An object representing the navigation tree node.
 * @param  stdClass $course Course object
 * @param  context_course $coursecontext Course context
 */
function discourse_extend_navigation_course($discoursenode, $course, $coursecontext) {
    $modinfo = get_fast_modinfo($course); // Get mod_fast_modinfo from $course.
    $index = 1; // Set index.
    foreach ($modinfo->get_cms() as $cmid => $cm) { // Search existing course modules for this course.
        if ($cm->modname == "discourse" && $cm->uservisible && $cm->available) { // Look if module exists, is uservisible and available.
            $url = new moodle_url("/mod/" . $cm->modname . "/view.php", array("id" => $cmid)); // Set url for the link in the navigation node.
            $node = navigation_node::create($cm->name.' ('.get_string('modulename', 'discourse').')', $url, navigation_node::TYPE_CUSTOM, null , null , null);
            $discoursenode->add_node($node);
        }
        $index++;
    }
}

/**
 * Extends the settings navigation with the mod_discourse settings.
 *
 * This function is called when the context for the page is a mod_discourse module.
 * This is not called by AJAX so it is safe to rely on the $PAGE.
 *
 * @param settings_navigation $settingsnav
 * @param navigation_node $discoursenode
 */
function discourse_extend_settings_navigation($settingsnav, $discoursenode = null) {
}
