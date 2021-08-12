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
 * Plugin internal classes, functions and constants are defined here.
 *
 * @package     mod_discourse
 * @copyright   2021 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Base class for mod_discourse.
 *
 * @package   mod_discourse
 * @copyright 2021 coactum GmbH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class discourse {

    /** @var context the context of the course module for this discourse instance */
    private $context;

    /** @var stdClass the course this discourse instance belongs to */
    private $course;

    /** @var cm_info the course module for this discourse instance */
    private $cm;

    /** @var stdClass the discourse record that contains the global settings for this discourse instance */
    private $instance;

    /** @var string modulename prevents excessive calls to get_string */
    private $modulename;

    /** @var array cached list of participants for this discourse. */
    private $participants = array();

    /** @var array cached list of user groups used in the discourse. */
    private $groups;

    /** @var array Array of error messages encountered during the execution of discourse related operations. */
    private $errors = array();

    /**
     * Constructor for the base discourse class.
     *
     * @param int $id int the course module id of the discourse
     * @param int $d int the instance id of the discourse
     */
    public function __construct($id, $d) {

        global $DB;

        if (isset($id)) {
            list ($course, $cm) = get_course_and_cm_from_cmid($id, 'discourse');
            $context = context_module::instance($cm->id);
        } else if (isset($d)) {
            list ($course, $cm) = get_course_and_cm_from_instance($d, 'discourse');
            $context = context_module::instance($cm->id);
        } else {
            throw new moodle_exception('missingparameter');
        }

        $this->context = $context;

        $this->course = $course;

        $this->cm = cm_info::create($cm);

        $this->instance = $DB->get_record('discourse', array('id' => $this->cm->instance));

        $this->modulename = get_string('modulename', 'mod_discourse');

        $this->participants = $DB->get_records('discourse_participants', array('discourse' => $this->cm->instance), '', 'userid, discourse, groupids');

        $groups = groups_get_activity_allowed_groups($this->cm);

        /**
         * Helper callback function to compare group objects and sort them by name.
         *
         * @param object $a group object to compare
         * @param object $b second group object
         */
        function compare($a, $b) {
            return strnatcmp($a->name, $b->name);
        }

        usort($groups, "compare");

        $this->groups = new stdClass();
        $this->groups->phaseone = array();
        $this->groups->phasetwo = array();
        $this->groups->phasethree = array();
        $this->groups->phasefour = array();

        foreach ($groups as $group) {

            // For retrieving former submissions.
            $participantids = array();
            $formergroupids = array();

            $groupurl = new moodle_url('/group/index.php', array('id' => $group->id[0], 'courseid' => $this->course->id));
            $group->profilelink = '<strong><a href="'.$groupurl.'">'.$group->name.'</a></strong>';

            $group->participants = array();

            foreach (groups_get_members($group->id) as $participant) {
                $profileurl = new moodle_url('/user/view.php', array('id' => $participant->id, 'course' => $this->course->id));

                $participant->profilelink = '<a href="'.$profileurl.'">'.$participant->firstname.' '.$participant->lastname.'</a>';

                array_push($group->participants, $participant);

                array_push($participantids, $participant->id);
            }

            $group->submission = $DB->get_record('discourse_submissions', array('groupid' => $group->id));

            // Former submissions of all participants groups from previous phases.
            if ($this->participants && groups_get_grouping($this->instance->groupingid)) {
                foreach ($participantids as $id) {
                    $groupids = json_decode($this->participants[$id]->groupids);

                    if (isset($groupids) && $this->instance->activephase != 1 && !in_array($groupids[$this->instance->activephase - 2], $formergroupids)) {
                        array_push($formergroupids, $groupids[$this->instance->activephase - 2]);
                    }
                }

                $formersubmissions = array();

                foreach ($formergroupids as $groupid) {
                    array_push($formersubmissions, $DB->get_record('discourse_submissions', array('discourse' => $this->instance->id, 'groupid' => $groupid)));
                }

                $group->formersubmissions = $formersubmissions;
            } else {
                $group->formersubmissions = false;
            }

            if (stripos($group->idnumber, 'phase_1')) {
                $group->phase = 1;
                $group->formersubmissions = false;
                array_push($this->groups->phaseone, $group);
            } else if (stripos($group->idnumber, 'phase_2')) {
                $group->phase = 2;
                array_push($this->groups->phasetwo, $group);
            } else if (stripos($group->idnumber, 'phase_3')) {
                $group->phase = 3;
                array_push($this->groups->phasethree, $group);
            } else if (stripos($group->idnumber, 'phase_4')) {
                $group->phase = 4;
                array_push($this->groups->phasefour, $group);
            }

        }
    }

    /**
     * Singleton getter for discourse instance.
     *
     * @param int $id int the course module id of discourse
     * @param int $d int the instance id of the discourse
     * @return string action
     */
    public static function get_discourse_instance($id, $d = null) {

        static $inst = null;
        if ($inst === null) {
            $inst = new discourse($id, $d);
        }
        return $inst;
    }

    /**
     * Returns the context of the discourse.
     *
     * @return string action
     */
    public function get_context() {
        return $this->context;
    }

    /**
     * Returns the course of the discourse.
     *
     * @return string action
     */
    public function get_course() {
        return $this->course;
    }

    /**
     * Returns the course module of the discourse.
     *
     * @return string action
     */
    public function get_course_module() {
        return $this->cm;
    }

    /**
     * Returns the module instance record from the table discourse.
     *
     * @return string action
     */
    public function get_module_instance() {
        return $this->instance;
    }

    /**
     * Returns the discourse participants from the table discourse_participants.
     *
     * @return string action
     */
    public function get_participants() {
        return $this->participants;
    }

    /**
     * Returns the discourse groups the user can view.
     *
     * @return string action
     */
    public function get_groups() {
        return $this->groups;
    }

    /**
     * Returns the discourse group.
     * @param int $id int id of the group
     * @return string action
     */
    public function get_group($id) {
        $groupviewed = false;
        foreach ($this->groups as $phase) {
            foreach ($phase as $group) {
                if ($group->id == $id) {
                    $groupviewed = $group;
                }
            }
        }
        return $groupviewed;
    }

    /**
     * Subscribe users to discourse and store them in table discourse_participants.
     *
     * @param array $users array with participants objects that should be added to the discourse
     * @return array participants
     */
    public function subscribe_users($users) {
        global $DB;

        $participants = array();
        foreach ($users as $user) {
            $participant = new stdClass();
            $participant->userid = $user->id;
            $participant->discourse = $this->instance->id;
            $participant->groupids = json_encode($user->groupids);
            array_push($participants, $participant);
        }

        $DB->insert_records('discourse_participants', $participants);

        return $participants;

    }

    /**
     * Create grouping and groups for discourse.
     *
     * @param array $users array with participants objects that should be added to the discourse
     */
    public function create_groups_and_grouping($users) {

        global $DB, $CFG;

        require_once("$CFG->dirroot/group/lib.php");

        // Create grouping for the discourse.
        $grouping = new stdClass();
        $grouping->courseid = $this->course->id;
        $grouping->name = $this->instance->name;
        $grouping->description = get_string('groupingdescription', 'mod_discourse', $this->instance->name);
        $grouping->descriptionformat = FORMAT_HTML;

        $groupingid = groups_create_grouping($grouping);

        $DB->set_field('discourse', 'groupingid', $groupingid, array('id' => $this->instance->id));

        // Create groups for the discourse.
        $groupids = array();

        $groupdata = new StdClass();
        $groupdata->courseid = $this->course->id;
        $groupdata->descriptionformat = FORMAT_HTML;

        $i = 1;

        // Group for solo phase.
        foreach ($users as $user) {
            $groupdata->name = get_string('phaseone', 'mod_discourse') . ' ' . $this->instance->name . ' ' . get_string('group', 'mod_discourse') . ' ' . $i;
            $groupdata->description = get_string('groupfor', 'mod_discourse', get_string('phaseone', 'mod_discourse'));
            $groupdata->idnumber = 'discourse_' . $this->instance->id . '_phase_' . 1 . '_group_' . $i;

            $groupid = groups_create_group($groupdata);
            groups_assign_grouping($groupingid, $groupid);

            // Assign group members and set group ids for participants for solo phase.
            groups_add_member($groupid, $user);
            $user->groupids = array($groupid);

            $i += 1;

        }

        // Groups for 1st group phase.
        for ($i = 1; $i <= 4; $i ++) {
            $groupdata->name = get_string('phasetwo', 'mod_discourse') . ' ' . $this->instance->name . ' ' . get_string('group', 'mod_discourse') . ' ' . $i;
            $groupdata->description = get_string('groupfor', 'mod_discourse', get_string('phasetwo', 'mod_discourse'));
            $groupdata->enablemessaging = true;
            $groupdata->idnumber = 'discourse_' . $this->instance->id . '_phase_' . 2 . '_group_' . $i;

            $groupid = groups_create_group($groupdata);
            groups_assign_grouping($groupingid, $groupid);

            $groupids[$i] = $groupid;
        }

        // Groups for 2nd group phase.
        for ($i = 5; $i <= 6; $i ++) {
            $groupdata->name = get_string('phasethree', 'mod_discourse') . ' ' . $this->instance->name . ' ' . get_string('group', 'mod_discourse') . ' ' . ($i - 4);
            $groupdata->description = get_string('groupfor', 'mod_discourse', get_string('phasethree', 'mod_discourse'));
            $groupdata->enablemessaging = true;
            $groupdata->idnumber = 'discourse_' . $this->instance->id . '_phase_' . 3 . '_group_' . ($i - 4);

            $groupid = groups_create_group($groupdata);
            groups_assign_grouping($groupingid, $groupid);

            $groupids[$i] = $groupid;
        }

        // Assign users to groups from 1st and 2nd group phase.
        shuffle($users);
        $i = 1;

        foreach ($users as $user) {
            // Assign group members and set group ids for participants for 1st group phase.
            groups_add_member($groupids[$i % 5], $user);
            array_push($user->groupids, $groupids[$i % 5]);

            // Assign group members and set group ids for participants for 2nd group phase.
            if ($i <= 2) {
                groups_add_member($groupids[5], $user->id);
                array_push($user->groupids, $groupids[5]);
            } else {
                groups_add_member($groupids[6], $user->id);
                array_push($user->groupids, $groupids[6]);
            }

            if ($i < 4) {
                $i += 1;
            } else {
                $i = 1;
            }
        }

        // Group for collaborative phase.
        $groupdata->name = get_string('phasefour', 'mod_discourse') . ' ' . $this->instance->name . ' ' . get_string('group', 'mod_discourse') . ' ' . 1;
        $groupdata->description = get_string('groupfor', 'mod_discourse', get_string('phasefour', 'mod_discourse'));
        $groupdata->enablemessaging = true;
        $groupdata->idnumber = 'discourse_' . $this->instance->id . '_phase_' . 4 . '_group_' . 1;

        $groupid = groups_create_group($groupdata);
        groups_assign_grouping($groupingid, $groupid);

        foreach ($users as $user) {
            // Assign group members and set group ids for participants for collaborative phase.
            groups_add_member($groupid, $user);
            array_push($user->groupids, $groupid);
        }

        // Assign grouping to module.
        $DB->set_field('course_modules', 'groupingid', $groupingid, array(
            'id' => $this->cm->id,
            'course' => $this->course->id,
            'instance' => $this->instance->id
        ));

        // Set group mode for module.
        if ($DB->get_field('course_modules', 'groupmode', array('id' => $this->cm->id, 'course' => $this->course->id, 'instance' => $this->instance->id)) === 0) {
            $DB->set_field('course_modules', 'groupmode', 1, array(
                'id' => $this->cm->id,
                'course' => $this->course->id,
                'instance' => $this->instance->id
            ));
        }

        // Bind visibility of course module to grouping membership.
        $restriction = \core_availability\tree::get_root_json([
            \availability_grouping\condition::get_json($groupingid)
        ]);

        $DB->set_field('course_modules', 'availability', json_encode($restriction), array(
            'id' => $this->cm->id,
            'course' => $this->course->id,
            'instance' => $this->instance->id
        ));

        // Subscribe users as participants.
        $participants = $this->subscribe_users($users);

    }
}
