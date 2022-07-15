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
 * Backup steps for mod_discourse are defined here.
 *
 * @package     mod_discourse
 * @category    backup
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define the complete structure for backup, with file and id annotations.
 */
class backup_discourse_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the structure of the resulting xml file.
     *
     * @return backup_nested_element The structure wrapped by the common 'activity' element.
     */
    protected function define_structure() {
        $userinfo = $this->get_setting_value('userinfo');
        $groupinfo = $this->get_setting_value('groups');

        // Replace with the attributes and final elements that the element will handle.
        $discourse = new backup_nested_element('discourse', array('id'), array(
            'name', 'intro', 'introformat', 'timecreated', 'timemodified',
            'autoswitch', 'activephase', 'hintphaseone', 'hintphasetwo',
            'hintphasethree', 'hintphasefour', 'deadlinephaseone', 'deadlinephasetwo',
            'deadlinephasethree', 'deadlinephasefour', 'groupingid'));

        $participants = new backup_nested_element('participants');

        $participant = new backup_nested_element('participant', array('id'), array('userid', 'groupids'));

        $submissions = new backup_nested_element('submissions');

        $submission = new backup_nested_element('submission', array('id'), array(
            'groupid', 'submission', 'currentversion', 'format', 'timecreated', 'timemodified'));

        // Build the tree with these elements with $discourse as the root of the backup tree.

        $discourse->add_child($participants);
        $participants->add_child($participant);

        $discourse->add_child($submissions);
        $submissions->add_child($submission);

        // Define the source tables for the elements.

        $discourse->set_source_table('discourse', array('id' => backup::VAR_ACTIVITYID));

        if ($userinfo && $groupinfo) {
            $participant->set_source_table('discourse_participants', array('discourse' => backup::VAR_PARENTID));

            $submission->set_source_table('discourse_submissions', array('discourse' => backup::VAR_PARENTID));
        }

        // Define id annotations.

        $discourse->annotate_ids('grouping', 'groupingid');

        if ($userinfo && $groupinfo) {
            $participant->annotate_ids('user', 'userid');
            $submission->annotate_ids('group', 'groupid');
        }

        // Define file annotations.

        $discourse->annotate_files('mod_discourse', 'intro', null); // This file area has no itemid.

        return $this->prepare_activity_structure($discourse);
    }
}
