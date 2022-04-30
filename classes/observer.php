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
 * Event observers used in discourse.
 *
 * @package    mod_discourse
 * @copyright  2022 coactum GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Event observer for mod_discourse.
 */
class mod_discourse_observer {

    /**
     * Observer for \core\event\course_module_created event.
     *
     * @param \core\event\course_module_created $event
     * @return void
     */
    public static function course_module_created(\core\event\course_module_created $event) {
        global $CFG;

        if ($event->other['modulename'] === 'discourse') {
            // Include the discourse libraries to make use of the discourse_instance_created function.
            require_once($CFG->dirroot . '/mod/discourse/locallib.php');
            require_once($CFG->dirroot . '/mod/discourse/lib.php');

            $discourse = discourse::get_discourse_instance(null, $event->other['instanceid']);

            discourse_instance_created($event->get_context(), $discourse);
        }
    }
}
