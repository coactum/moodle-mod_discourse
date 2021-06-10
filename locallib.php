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
    private $usergroups = array();

    /** @var array Array of error messages encountered during the execution of discourse related operations. */
    private $errors = array();

    /**
     * Constructor for the base discourse class.
     *
     * @param int $id int the course module id of discourse
     */
    public function __construct($id) {

        global $DB;

        list ($course, $cm) = get_course_and_cm_from_cmid($id, 'discourse');
        $context = context_module::instance($cm->id);

        $this->context = $context;

        $this->course = $course;

        $this->cm = cm_info::create($cm);

        $this->instance = $DB->get_record('discourse', array('id' => $this->cm->instance), '*', MUST_EXIST);

        $this->modulename = get_string('modulename', 'mod_discourse');

        // $this->participants = array();

        // $this->usergroups = array();

    }

    /**
     * Singleton getter for disscourse instance.
     *
     * @param int $id int the course module id of discourse
     */
    public static function get_discourse_instance($id) {

        static $inst = null;
        if ($inst === null) {
            $inst = new discourse($id);
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

}
