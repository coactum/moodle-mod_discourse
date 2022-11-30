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
 * @copyright   2022 coactum GmbH
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot . '/mod/discourse/locallib.php');

/**
 * Module instance settings form.
 *
 * @package    mod_discourse
 * @copyright  2022 coactum GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_discourse_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG, $PAGE;

        $id = optional_param('update', null, PARAM_INT);

        $mform = $this->_form;

        if (isset($id) && $id !== 0) {
            $discourse = discourse::get_discourse_instance($id);

            // For updating group names.
            $mform->addElement('hidden', 'cmid', $id);
            $mform->setType('cmid', PARAM_INT);

            $mform->addElement('hidden', 'oldname', $discourse->get_module_instance()->name);
            if (!empty($CFG->formatstringstriptags)) {
                $mform->setType('oldname', PARAM_TEXT);
            } else {
                $mform->setType('oldname', PARAM_CLEANHTML);
            }
        }

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
        $this->standard_intro_elements();

        // Adding section for phase completion.
        $mform->addElement('header', 'phasecompletion', get_string('phasecompletion', 'mod_discourse'));

        $mform->addElement('date_time_selector', 'deadlinephaseone', get_string('deadlinephaseone', 'mod_discourse'));
        $mform->setDefault('deadlinephaseone', strtotime("+7 days noon"));

        $mform->addElement('date_time_selector', 'deadlinephasetwo', get_string('deadlinephasetwo', 'mod_discourse'));
        $mform->setDefault('deadlinephasetwo', strtotime("+14 days noon"));

        $mform->addElement('date_time_selector', 'deadlinephasethree', get_string('deadlinephasethree', 'mod_discourse'));
        $mform->setDefault('deadlinephasethree', strtotime("+21 days noon"));

        $mform->addElement('date_time_selector', 'deadlinephasefour', get_string('deadlinephasefour', 'mod_discourse'));
        $mform->setDefault('deadlinephasefour', strtotime("+28 days noon"));

        $mform->addElement('advcheckbox', 'autoswitch', get_string('modeautoswitch', 'mod_discourse'), get_string('autoswitch', 'mod_discourse'));

        if (isset($id) && $id !== 0 && !isset($discourse->get_module_instance()->autoswitch)) {
            $mform->setDefault('autoswitch', 0);
        } else {
            $mform->setDefault('autoswitch', 1);
        }

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


    /**
     * Custom validation for the form.
     * @param array $data Array with all the form data
     * @param array $files Array with files submitted with form
     * @return array $errors Array with errors
     */
    public function validation($data, $files) {
        $errors = array();

        if ($data['deadlinephaseone'] >= $data['deadlinephasetwo']) {
            $errors['deadlinephaseone'] = get_string('errinvalidphasedeadline', 'mod_discourse');
            $errors['deadlinephasetwo'] = get_string('errinvalidphasedeadline', 'mod_discourse');
        }

        if ($data['deadlinephasetwo'] >= $data['deadlinephasethree']) {
            $errors['deadlinephasetwo'] = get_string('errinvalidphasedeadline', 'mod_discourse');
            $errors['deadlinephasethree'] = get_string('errinvalidphasedeadline', 'mod_discourse');
        }

        if ($data['deadlinephasethree'] >= $data['deadlinephasefour']) {
            $errors['deadlinephasethree'] = get_string('errinvalidphasedeadline', 'mod_discourse');
            $errors['deadlinephasefour'] = get_string('errinvalidphasedeadline', 'mod_discourse');
        }

        return $errors;
    }
}
