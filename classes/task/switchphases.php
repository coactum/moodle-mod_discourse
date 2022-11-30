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
 * A cron_task class for switching phases in all discourses to be used by Tasks API.
 *
 * @package     mod_discourse
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_discourse\task;

/**
 * A cron_task class for switching phases in all discourses to be used by Tasks API.
 *
 * @package     mod_discourse
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class switchphases extends \core\task\scheduled_task {
    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return get_string('task_switchphases', 'mod_discourse');
    }

    /**
     * Execute the task.
     */
    public function execute() {
        global $DB;

        mtrace('Starting scheduled task ' . get_string('task_switchphases', 'mod_discourse'));

        $select = "autoswitch = 1";
        $select .= " AND activephase != 4";
        $select .= " AND (" . time() . " > deadlinephaseone AND activephase = 1";
        $select .= " OR " . time() . " > deadlinephasetwo AND activephase = 2";
        $select .= " OR " . time() . " > deadlinephasethree AND activephase = 3)";

        $rs = $DB->get_recordset_select('discourse', $select);

        if ($rs->valid()) {

            mtrace('DisCourses found where phases should be switched.');

            $count = 0;

            mtrace('Switching phases ...');

            foreach ($rs as $record) {

                $record->activephase += 1;

                $DB->update_record('discourse', $record);

                $count += 1;
            }

            $rs->close();

            mtrace('Phases for ' . $count . ' discourses successfully switched.');

        } else {
            mtrace('No discourses found where phases should be switched.');
        }

        mtrace('Task finished');
    }
}
