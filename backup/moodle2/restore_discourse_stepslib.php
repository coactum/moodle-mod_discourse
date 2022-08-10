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
 * All the steps to restore mod_discourse are defined here.
 *
 * @package     mod_discourse
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Defines the structure step to restore one mod_discourse activity.
 */
class restore_discourse_activity_structure_step extends restore_activity_structure_step {

    /** @var includeparticipantsandsubmissions Store whether submissions and participants should be included.
     * Will not be included if groups/grouping information or user infos are not included in the backup.
     */
    protected $includeparticipantsandsubmissions = false;

    /** @var newgroupingid Store the new grouping id. */
    protected $newgroupingid = false;

    /** @var newdiscourseid Store id of new discourse. */
    protected $newdiscourseid = false;

    /**
     * Defines the structure to be restored.
     *
     * @return restore_path_element[].
     */
    protected function define_structure() {
        $paths = array();

        $userinfo = $this->get_setting_value('userinfo');
        $groupinfo = $this->get_setting_value('groups');

        $paths[] = new restore_path_element('discourse', '/activity/discourse');

        if ($userinfo && $groupinfo) {
            $paths[] = new restore_path_element('discourse_participant', '/activity/discourse/participants/participant');
            $paths[] = new restore_path_element('discourse_submission', '/activity/discourse/submissions/submission');
        }

        return $this->prepare_activity_structure($paths);
    }

    /**
     * Restore discourse.
     *
     * @param object $data data.
     */
    protected function process_discourse($data) {
        global $DB;

        $userinfo = $this->get_setting_value('userinfo');
        $groupinfo = $this->get_setting_value('groups');

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();

        // Any changes to the list of dates that needs to be rolled should be same during course restore and course reset.
        // See MDL-9367.
        if (!isset($data->deadlinephaseone)) {
            $data->deadlinephaseone = 0;
        }
        $data->deadlinephaseone = $this->apply_date_offset($data->deadlinephaseone);

        if (!isset($data->deadlinephasetwo)) {
            $data->deadlinephasetwo = 0;
        }
        $data->deadlinephasetwo = $this->apply_date_offset($data->deadlinephasetwo);

        if (!isset($data->deadlinephasethree)) {
            $data->deadlinephasethree = 0;
        }
        $data->deadlinephasethree = $this->apply_date_offset($data->deadlinephasethree);

        if (!isset($data->deadlinephasefour)) {
            $data->deadlinephasefour = 0;
        }
        $data->deadlinephasefour = $this->apply_date_offset($data->deadlinephasefour);

        if ($userinfo && $groupinfo) {
            $this->includeparticipantsandsubmissions = true;
        }

        if ($groupinfo) {
            $this->newgroupingid = $this->get_mappingid('grouping', $data->groupingid);

            if ($this->newgroupingid != $data->groupingid) {
                $data->groupingid = $this->newgroupingid;
            } else {
                $this->newgroupingid = false;
                $data->groupingid = 0;
            }

            if ($this->newgroupingid && $DB->record_exists('discourse', array('groupingid' => $this->newgroupingid))) {
                $this->newgroupingid = false;
                $data->groupingid = 0;
            }
        } else {
            $this->newgroupingid = false;
            $data->groupingid = 0;
        }

        $newitemid = $DB->insert_record('discourse', $data);
        $this->apply_activity_instance($newitemid);
        $this->newdiscourseid = $newitemid;

        if ($groupinfo && $this->newgroupingid && $data->groupingid != 0) {
            $groups = groups_get_all_groups($data->course, 0, $data->groupingid);
            // Change discourse id in the new groups to the new discourse id.
            foreach ($groups as $group) {
                $group->idnumber = preg_replace('/discourse_[0-9]+_/', 'discourse_' . $this->newdiscourseid . '_', $group->idnumber);
                $group->enablemessaging = 1;
                groups_update_group($group);
            }
        }
    }

    /**
     * Restore discourse participant.
     *
     * @param object $data data.
     */
    protected function process_discourse_participant($data) {

        if (!$this->includeparticipantsandsubmissions || !$this->newgroupingid) {
            return;
        }

        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->discourse = $this->get_new_parentid('discourse');
        $data->userid = $this->get_mappingid('user', $data->userid);

        // Update groupids for participants.
        $newusergroups = groups_get_user_groups($this->get_courseid(), $data->userid);
        if (isset($newusergroups[$this->newgroupingid])) {
            $data->groupids = json_encode(array_keys($newusergroups[$this->newgroupingid]));
        } else {
            $data->groupids = json_encode(array());
        }

        $newitemid = $DB->insert_record('discourse_participants', $data);
        $this->set_mapping('discourse_participant', $oldid, $newitemid);
    }

    /**
     * Restore discourse submission.
     *
     * @param object $data data.
     */
    protected function process_discourse_submission($data) {

        if (!$this->includeparticipantsandsubmissions || !$this->newgroupingid) {
            return;
        }

        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        $data->discourse = $this->get_new_parentid('discourse');
        $data->groupid = $this->get_mappingid('group', $data->groupid);

        $newitemid = $DB->insert_record('discourse_submissions', $data);
        $this->set_mapping('discourse_submission', $oldid, $newitemid, true);
    }

    /**
     * Defines post-execution actions.
     */
    protected function after_execute() {
        // Add discourse related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_discourse', 'intro', null);
    }
}
