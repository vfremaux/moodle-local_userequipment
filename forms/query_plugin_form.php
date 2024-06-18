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
 * OBSOLETE. Take control of form by direct templates. => Just keep the profile attributes form.
 * @package   local_userequipment
 * @category  local
 * @copyright 2016 Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');

// Hack formlibs factory default.

class QueryPluginForm extends moodleform {

    public function definition() {

        $mform = $this->_form;

        $mform->addElement('text', 'queryplugin', get_string('queryplugin', 'local_userequipment'));
        $mform->addHelpButton('queryplugin', 'queryplugin', 'local_userequipment');
        $mform->setType('queryplugin', PARAM_TEXT);

        $this->add_action_buttons(true);
    }
}
