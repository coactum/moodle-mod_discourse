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
 * The main mod_discourse configuration form.
 *
 * @package     mod_discourse
 * @copyright   2021 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    // It must be included from a Moodle page.
}

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot . '/mod/discourse/locallib.php');

/**
 * Module instance settings form.
 *
 * @package    mod_discourse
 * @copyright  2021 coactum GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_discourse_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG, $PAGE;

        $id = optional_param('update', null, PARAM_INT);

        if (isset($id) && $id !== 0) {
            $discourse = discourse::get_discourse_instance($id);
        }

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('modulename', 'mod_discourse'), array('size' => '64'));

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }

        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'modulename', 'mod_discourse');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // Adding section for phase completion.
        $mform->addElement('header', 'phasecompletion', get_string('phasecompletion', 'mod_discourse'));

        $mform->addElement('advcheckbox', 'autoswitch', get_string('mode_autoswitch', 'mod_discourse'), get_string('autoswitch', 'mod_discourse'));

        if (isset($id) && $id !== 0 && isset($discourse->get_course_module_instance()->autoswitch)) {
            $mform->setDefault('autoswitch', 1);
        } else {
            $mform->setDefault('autoswitch', 0);
        }

        $mform->addElement('date_time_selector', 'deadlinephaseone', get_string('deadlinephaseone', 'mod_discourse'));
        $mform->setDefault('deadlinephaseone', time() + (7 * 24 * 60 * 60));
        $mform->hideIf('deadlinephaseone', 'autoswitch', 'eq', 0);

        $mform->addElement('date_time_selector', 'deadlinephasetwo', get_string('deadlinephasetwo', 'mod_discourse'));
        $mform->setDefault('deadlinephasetwo', time() + (14 * 24 * 60 * 60));
        $mform->hideIf('deadlinephasetwo', 'autoswitch', 'eq', 0);

        $mform->addElement('date_time_selector', 'deadlinephasethree', get_string('deadlinephasethree', 'mod_discourse'));
        $mform->setDefault('deadlinephasethree', time() + (21 * 24 * 60 * 60));
        $mform->hideIf('deadlinephasethree', 'autoswitch', 'eq', 0);

        $mform->addElement('date_time_selector', 'deadlinephasefour', get_string('deadlinephasefour', 'mod_discourse'));
        $mform->setDefault('deadlinephasefour', time() + (28 * 24 * 60 * 60));
        $mform->hideIf('deadlinephasefour', 'autoswitch', 'eq', 0);

        // Adding section for phase hints.
        $mform->addElement('header', 'phaseshints', get_string('phaseshints', 'mod_discourse'));

        $mform->addElement('textarea', 'hintphaseone', get_string('hintphaseone', 'mod_discourse'), 'wrap="virtual" rows="2" cols="150"');
        $mform->setType('hintphaseone', PARAM_TEXT);

        $mform->addElement('textarea', 'hintphasetwo', get_string('hintphasetwo', 'mod_discourse'), 'wrap="virtual" rows="2" cols="150"');
        $mform->setType('hintphasetwo', PARAM_TEXT);

        $mform->addElement('textarea', 'hintphasethree', get_string('hintphasethree', 'mod_discourse'), 'wrap="virtual" rows="2" cols="150"');
        $mform->setType('hintphasethree', PARAM_TEXT);

        $mform->addElement('textarea', 'hintphasefour', get_string('hintphasefour', 'mod_discourse'), 'wrap="virtual" rows="2" cols="150"');
        $mform->setType('hintphasefour', PARAM_TEXT);

        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }
}
