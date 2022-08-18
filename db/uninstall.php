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
 * Code that is executed before the tables and data are dropped during the plugin uninstallation.
 *
 * @package     mod_discourse
 * @category    upgrade
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Custom uninstallation procedure.
 */
function xmldb_discourse_uninstall() {

    global $DB, $CFG;

    require_once("$CFG->dirroot/group/lib.php");

    // Delete all groupings associated with discourses.
    $discourses = $DB->get_recordset('discourse');

    if ($discourses->valid()) {
        foreach ($discourses as $discourse) {
            if (isset($discourse->groupingid) && $discourse->groupingid != 0) {
                groups_delete_grouping($discourse->groupingid);
            }
        }

        $discourses->close();
    }

    $select = "idnumber LIKE '%discourse%'";
    $select .= "AND idnumber LIKE '%phase%'";
    $select .= "AND idnumber LIKE '%group%'";

    // Deleting groups created by discourse activity with idnumber discourse_X_phase_x_group_X to prevent problems with already existing idsnumbers after reinstallation of plugin.
    $discoursegroups = $DB->get_recordset_select('groups', $select);

    if ($discoursegroups->valid()) {
        foreach ($discoursegroups as $group) {
            if ($group->id) {
                groups_delete_group($group->id);
            }
        }

        $discoursegroups->close();
    }

    return true;
}
