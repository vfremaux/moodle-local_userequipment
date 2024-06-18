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

class UserEquipmentForm extends moodleform {

    public function definition() {
        global $CFG, $DB, $USER, $OUTPUT, $PAGE;

        $mform = $this->_form;

        $context = context_system::instance();
        $maxbytes = $CFG->maxbytes;
        $maxfiles = 100;
        $this->editoroptions = array('trusttext' => true,
                                     'subdirs' => false,
                                     'maxfiles' => $maxfiles,
                                     'maxbytes' => $maxbytes,
                                     'context' => $context);

        $mform->addElement('html', $OUTPUT->render_from_template('local_userequipment/editcatpng', []));
        $renderer = $PAGE->get_renderer('local_userequipment');
        $mform->addElement('html', $renderer->render_modchooser());

        $mform->addElement('header', 'theader', get_string('template', 'local_userequipment'));

        $mform->addElement('hidden', 'istemplate');
        $mform->setType('istemplate', PARAM_BOOL);

        $mform->addElement('hidden', 'template');
        $mform->setType('template', PARAM_INT);

        $mform->addElement('text', 'name', get_string('templatename', 'local_userequipment'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('editor', 'description_editor', get_string('description'), null, $this->editoroptions);

        $mform->addElement('advcheckbox', 'usercanchoose', get_string('usercanchoose', 'local_userequipment'), '', 0);

        $mform->addElement('advcheckbox', 'isdefault', get_string('isdefault', 'local_userequipment'), '', 0);

        if (local_userequipment_supports_feature('application/coursecompletion')) {
            include ($CFG->dirroot.'/local/userequipment/pro/lib.php');
            local_userequipment_add_profile_extensions($mform);
        }

        $this->add_action_buttons(true);
    }

    /**
     * Useless.
     *
     */
    public function set_data($defaults) {

        $context = $this->editoroptions['context'];

        $descdraftideditor = file_get_submitted_draft_itemid('description_editor');
        $currenttext = file_prepare_draft_area($descdraftideditor, $context->id, 'local_userequipment',
                                               'description_editor', @$defaults->id, array('subdirs' => true),
                                               @$defaults->description);
        $defaults = file_prepare_standard_editor($defaults, 'description', $this->editoroptions, $context,
                                                 'local_userequipment', 'templatedesc', @$defaults->id);
        $defaults->description = array('text' => $currenttext,
                                       'format' => $defaults->descriptionformat,
                                       'itemid' => $descdraftideditor);

        parent::set_data($defaults);
    }
}