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
 * @package   local_userequipment
 * @category  local
 * @copyright 2016 Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');

// Hack formlibs factory default.

class CategoriesForm extends moodleform {

    public function definition() {
        global $CFG, $DB, $USER;

        $mform = $this->_form;

        $context = context_system::instance();
        $maxbytes = $CFG->maxbytes;
        $maxfiles = 100;
        $this->editoroptions = array('trusttext' => true,
                                     'subdirs' => false,
                                     'maxfiles' => $maxfiles,
                                     'maxbytes' => $maxbytes,
                                     'context' => $context);
                                     
        $manager = core_plugin_manager::instance();
        $sm = get_string_manager();
        $categories = $manager->get_plugins_of_type('categories');
        
        $mform->addElement('header', 'cheader', get_string('categories', 'local_userequipment'));
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $cname = $category->name;
                $formatname = get_string("pluginname", "format_$cname");
                if ($formatname == "[[format$cname]]") {
                    $formatname = get_string("format$cname");
                }
                $mform->addElement('checkbox', 'format_'.$cname, $formatname);
                $allplugins[] = 'format_'.$cname;
            }
        }else{
            $mform->addElement('', get_string('emptycat', 'local_userequipment'));
        }
        
        $mform->addElement('header', 'echeader', get_string('addcategories', 'local_userequipment'));
        
            $mform->addElement('text', 'catname', get_string('catName', 'local_userequipment'));
            $mform->setType('text', PARAM_NOTAGS);
            $mform->setDefault('text', get_string('placeholder_catname', 'local_userequipment'));
            
            $mform->addElement('text', 'catdesc', get_string('catDesc', 'local_userequipment'));
            $mform->setType('text', PARAM_NOTAGS);
            $mform->setDefault('text', get_string('placeholder_catdesc', 'local_userequipment'));
            
            $mform->addElement('text', 'cathexa', get_string('catColours', 'local_userequipment'));
            $mform->setType('text', PARAM_NOTAGS);
            $mform->setDefault('text', get_string('placeholder_catcolours', 'local_userequipment'));
        
        $this->add_action_buttons(true);
    }

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