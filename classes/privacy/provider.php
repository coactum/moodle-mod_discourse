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
 * Privacy subsystem implementation for discourse.
 *
 * @package    mod_discourse
 * @copyright  2022 coactum GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_discourse\privacy;

use \core_privacy\local\request\userlist;
use \core_privacy\local\request\approved_contextlist;
use \core_privacy\local\request\approved_userlist;
use \core_privacy\local\request\writer;
use \core_privacy\local\request\helper;
use \core_privacy\local\metadata\collection;
use \core_privacy\local\request\transform;
use core_privacy\local\request\contextlist;

/**
 * Implementation of the privacy subsystem plugin provider for the discourse activity module.
 *
 * @copyright  2022 coactum GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    // This plugin has data.
    \core_privacy\local\metadata\provider,

    // This plugin currently implements the original plugin\provider interface.
    \core_privacy\local\request\plugin\provider,

    // This plugin is capable of determining which users have data within it.
    \core_privacy\local\request\core_userlist_provider {

    /**
     * Returns meta data about this system.
     *
     * @param   collection     $items The initialised collection to add items to.
     * @return  collection     A listing of user data stored through this system.
     */
    public static function get_metadata(collection $items) : collection {

        // The table 'discourse_participants' stores all discourse participants and information about their groups.
        $items->add_database_table('discourse_participants', [
            'userid' => 'privacy:metadata:discourse_participants:userid',
            'discourse' => 'privacy:metadata:discourse_participants:discourse',
            'groupids' => 'privacy:metadata:discourse_participants:groupids',
        ], 'privacy:metadata:discourse_participants');

        // The table 'discourse_submissions' stores all group submissions.
        $items->add_database_table('discourse_submissions', [
            'discourse' => 'privacy:metadata:discourse_submissions:discourse',
            'groupid' => 'privacy:metadata:discourse_submissions:groupid',
            'submission' => 'privacy:metadata:discourse_submissions:submission',
            'currentversion' => 'privacy:metadata:discourse_submissions:currentversion',
            'format' => 'privacy:metadata:discourse_submissions:format',
            'timecreated' => 'privacy:metadata:discourse_submissions:timecreated',
            'timemodified' => 'privacy:metadata:discourse_submissions:timemodified',
        ], 'privacy:metadata:discourse_submissions');

        // The discourse uses the groups subsystem that saves personal data.
        $items->add_subsystem_link('core_group', [], 'privacy:metadata:core_group');

        // There are no user preferences in the discourse.

        return $items;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * In this case of all discourses where a user is discourse participant.
     *
     * @param   int         $userid     The user to search.
     * @return  contextlist $contextlist  The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $contextlist = new contextlist();

        $params = [
            'modulename'       => 'discourse',
            'contextlevel'  => CONTEXT_MODULE,
            'userid'        => $userid,
        ];

        // Select discourses of user.

        $sql;
        $sql = "SELECT c.id
                  FROM {context} c
                  JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modulename
                  JOIN {discourse} d ON d.id = cm.instance
                  JOIN {discourse_participants} p ON p.discourse = d.id
                  WHERE p.userid = :userid
        ";

        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Get the list of users within a specific context.
     *
     * @param   userlist    $userlist   The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        if (!is_a($context, \context_module::class)) {
            return;
        }

        $params = [
            'instanceid'    => $context->id,
            'modulename'    => 'discourse',
        ];

        // Get users.
        $sql;
        $sql = "SELECT p.userid
                  FROM {course_modules} cm
                  JOIN {modules} m ON m.id = cm.module AND m.name = :modulename
                  JOIN {discourse} d ON d.id = cm.instance
                  JOIN {discourse_participants} p ON p.discourse = d.id
                 WHERE cm.id = :instanceid
        ";
        $userlist->add_from_sql('userid', $sql, $params);
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param   approved_contextlist    $contextlist    The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist)) {
            return;
        }

        $user = $contextlist->get_user();
        $userid = $user->id;

        list($contextsql, $contextparams) = $DB->get_in_or_equal($contextlist->get_contextids(), SQL_PARAMS_NAMED);

        // DisCourse participants data.
        $sql;
        $sql = "SELECT
                    cm.id AS cmid,
                    d.id AS discourse,
                    d.name,
                    d.timecreated,
                    d.timemodified,
                    p.userid,
                    p.groupids
                  FROM {context} c
                  JOIN {course_modules} cm ON cm.id = c.instanceid
                  JOIN {discourse} d ON d.id = cm.instance
                  JOIN {discourse_participants} p ON p.discourse = d.id
                 WHERE (
                    p.userid = :userid AND
                    c.id {$contextsql}
                )
        ";

        $params = $contextparams;
        $params['userid'] = $userid;

        $discourses = $DB->get_recordset_sql($sql, $params);

        if ($discourses->valid()) {
            foreach ($discourses as $discourse) {
                if ($discourse) {
                    $context = \context_module::instance($discourse->cmid);

                    if ($discourse->timemodified == 0) {
                        $discourse->timemodified = null;
                    } else {
                        $discourse->timemodified = \core_privacy\local\request\transform::datetime($discourse->timemodified);
                    }

                    $discoursedata = [
                        'id'       => $discourse->discourse,
                        'timecreated'   => \core_privacy\local\request\transform::datetime($discourse->timecreated),
                        'timemodified' => $discourse->timemodified,
                    ];

                    $discoursedata['user data:'] = [
                        'userid' => $discourse->userid,
                        'groupids' => $discourse->groupids,
                    ];

                    $groupids = implode(", ", json_decode($discourse->groupids));

                    $sql;
                    $submissions;
                    $sql = "SELECT
                                s.groupid,
                                s.discourse,
                                s.submission,
                                s.currentversion,
                                s.format,
                                s.timecreated,
                                s.timemodified
                            FROM {discourse_submissions} s
                            WHERE (
                                s.groupid IN ($groupids)
                            )
                    ";

                    $params['discourse'] = $discourse->discourse;
                    $submissions = $DB->get_records_sql($sql, $params);

                    if ($submissions) {
                        $submissionsdata = array();
                        unset($discoursedata['submissions:']);

                        foreach ($submissions as $submission) {

                            if (isset($submission->timemodified)) {
                                $timemodified = \core_privacy\local\request\transform::datetime($submission->timemodified);
                            } else {
                                $timemodified = null;
                            }

                            $submissionsdata['group ' . $submission->groupid] = [
                                'discourse' => $submission->discourse,
                                'groupid' => $submission->groupid,
                                'submission' => format_text($submission->submission, $submission->format, array('para' => false)),
                                'currentversion' => $submission->currentversion,
                                'format' => $submission->format,
                                'timecreated' => \core_privacy\local\request\transform::datetime($submission->timecreated),
                                'timemodified' => $timemodified,
                            ];
                        }

                        $discoursedata['submissions:'] = $submissionsdata;
                    }

                    self::export_discourse_data_for_user($discoursedata, $context, [], $user);
                }
            }
        }

        $discourses->close();
    }

    /**
     * Export the supplied personal data for a single discourse activity, along with all generic data for the activity.
     *
     * @param array $discoursedata The personal data to export for the discourse activity.
     * @param \context_module $context The context of the discourse activity.
     * @param array $subcontext The location within the current context that this data belongs.
     * @param \stdClass $user the user record
     */
    protected static function export_discourse_data_for_user(array $discoursedata, \context_module $context, array $subcontext, \stdClass $user) {
        // Fetch the generic module data for the discourse activity.
        $contextdata = helper::get_context_data($context, $user);
        // Merge with discourse data and write it.
        $contextdata = (object)array_merge((array)$contextdata, $discoursedata);
        writer::with_context($context)->export_data($subcontext, $contextdata);
        // Write generic module intro files.
        helper::export_context_files($context, $user);
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param   context                 $context   The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB, $CFG;

        // Check that this is a context_module.
        if (!$context instanceof \context_module) {
            return;
        }

        // Get the course module.
        if (!$cm = get_coursemodule_from_id('discourse', $context->instanceid)) {
            return;
        }

        require_once("$CFG->dirroot/group/lib.php");

        if (!$DB->record_exists('discourse', array('id' => $cm->instance))) {
            return;
        } else {
            $moduleinstance = $DB->get_record('discourse', array('id' => $cm->instance));
        }

        // Delete discourse groups.
        $groups = groups_get_all_groups($moduleinstance->course, 0, $moduleinstance->groupingid);

        foreach ($groups as $group) {
            groups_delete_group($group);
        }

        // Delete discourse grouping.
        groups_delete_grouping($moduleinstance->groupingid);

        // Delete all records.
        if ($DB->record_exists('discourse_participants', ['discourse' => $cm->instance])) {
            $DB->delete_records('discourse_participants', ['discourse' => $cm->instance]);
        }

        if ($DB->record_exists('discourse_submissions', ['discourse' => $cm->instance])) {
            $DB->delete_records('discourse_submissions', ['discourse' => $cm->instance]);
        }
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param   approved_contextlist    $contextlist    The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {

        global $DB;

        $userid = $contextlist->get_user()->id;

        foreach ($contextlist->get_contexts() as $context) {
            // Get the course module.
            $cm = $DB->get_record('course_modules', ['id' => $context->instanceid]);

            if ($DB->record_exists('discourse_participants', ['discourse' => $cm->instance, 'userid' => $userid])) {

                $DB->delete_records('discourse_participants', [
                    'discourse' => $cm->instance,
                    'userid' => $userid,
                ]);
            }
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param   approved_userlist       $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();
        $cm = $DB->get_record('course_modules', ['id' => $context->instanceid]);

        list($userinsql, $userinparams) = $DB->get_in_or_equal($userlist->get_userids(), SQL_PARAMS_NAMED);
        $params = array_merge(['discourseid' => $cm->instance], $userinparams);

        if ($DB->record_exists_select('discourse_participants', "discourse = :discourseid AND userid {$userinsql}", $params)) {
            $DB->delete_records_select('discourse_participants', "discourse = :discourseid AND userid {$userinsql}", $params);
        }
    }
}
