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
        // $mform->addElement('html', $OUTPUT->render_from_template('local_userequipment/activitieschooser', []));
        $renderer = $PAGE->get_renderer('local_userequipment');
        $mform->addElement('html', $renderer->render_modchooser());


        if ($this->_customdata['istemplate']) {
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

            $assignableroles = get_assignable_roles(context_system::instance());
            $roleoptions = array('' => get_string('none', 'local_userequipment'));
            foreach ($assignableroles as $rid => $name) {
                $roleoptions[$rid] = $name;
            }
            $label = get_string('associatedsystemrole', 'local_userequipment');
            $mform->addElement('select', 'associatedsystemrole', $label, $roleoptions, '');
            $mform->setAdvanced('associatedsystemrole');

            $options = array(0 => get_string('releasenever', 'local_userequipment'),
                             1 => get_string('releaseonnewprofile', 'local_userequipment'),
                             2 => get_string('releaseoncleanup', 'local_userequipment'));
            $mform->addElement('select', 'releaseroleon', get_string('releaseroleon', 'local_userequipment'), $options, 0);
            $mform->setAdvanced('releaseroleon');
        } else {
            // End user informative about user profile.
            $mform->addElement('header', 'theader', get_string('userequipment', 'local_userequipment'));

            $mform->addElement('html', get_string('ueinfo_tpl', 'local_userequipment'));

            if (has_capability('local/userequipment:selfequip', $context)) {
                if ($selfusabletemplates = $DB->get_records('local_userequipment_tpl', array('usercanchoose' => true))) {
                    $mform->addElement('html', get_string('ueselfinfo_tpl', 'local_userequipment'));
                    foreach ($selfusabletemplates as $tpl) {
                        $group = array();
                        $label = get_string('applytemplate', 'local_userequipment', $tpl->name);
                        $group[] = $mform->createElement('submit', 'applytpl'.$tpl->id, $label);
                        $descstr = format_text($tpl->description, $tpl->descriptionformat);
                        $desc = '<div class="pull-right userequipment-half-column">'.$descstr.'</div>';
                        $group[] = $mform->createElement('static', 'chk'.$tpl->id);
                        $mform->addGroup($group, 'group'.$tpl->id, '', array($desc), false, false);
                    }
                }

                $group = array();
                $marks = $DB->count_records('local_userequipment', array('userid' => $USER->id, 'available' => 1));
                if ($marks) {
                    $descstr = get_string('marksinfo', 'local_userequipment', $marks);
                    $desc = '<div class="pull-left userequipment-half-column">'.$descstr.'</div>';
                    $group[] = $mform->createElement('static', 'chkcleanup');
                    $group[] = $mform->createElement('submit', 'cleanup', get_string('cleanup', 'local_userequipment'));
                    $mform->addGroup($group, 'groupcleanup', '', array($desc), false, false);
                }
            }

        }

        $manager = core_plugin_manager::instance();
        $sm = get_string_manager();
        $blocks = $manager->get_plugins_of_type('block');

        $modules = $manager->get_plugins_of_type('mod');

        $qtypes = $manager->get_plugins_of_type('qtype');

        $formats = $manager->get_plugins_of_type('format');
        
        $categories = $manager->get_plugins_of_type('categorie');

        $allplugins = array();

        // Course formats.
        $mform->addElement('header', 'fheader', get_string('courseformats'));
        if (!empty($formats)) {
            foreach ($formats as $format) {
                $fname = $format->name;
                $formatname = get_string("pluginname", "format_$fname");
                if ($formatname == "[[format$fname]]") {
                    $formatname = get_string("format$fname");
                }
                $mform->addElement('checkbox', 'format_'.$fname, $formatname);
                /*
                $args = [
                    'data-plugintype' => 'format',
                    'data-pluginname' => $fname,
                    'class' => 'assignment_button',
                    'data-toggle' => 'modal',
                    'data-target' => '#catpng-inner-form'
                ];
                $mform->addElement('button', 'openmodal_'.$fname, get_string('categorize', 'local_userequipment'), $args);
                */
                $allplugins[] = 'format_'.$fname;
            }
        }

        $mform->addElement('header', 'bheader', get_string('blocks'));
        if (!empty($blocks)) {
            // Standard implementation, give the block list flat and uncategorized.
            foreach ($blocks as $block) {
                if ($bck = $DB->get_record('block', array('name' => $block->name))) {
                    if (!$bck->visible || !is_dir($CFG->dirroot.'/blocks/'.$block->name)) {
                        continue;
                    }
                    $blockobject = block_instance($block->name);
                    $blockname = @$blockobject->title;
                    if (empty($blockname)) {
                        $blockname = get_string('blockname', 'block_'.$block->name);
                    }
                    $blockdesc = '';
                    if ($sm->string_exists('plugdesc_block_'.$block->name, 'local_userequipment')) {
                        $blockdesc = get_string('plugdesc_block_'.$block->name, 'local_userequipment');
                    } else if ($sm->string_exists('plugdesc_block_'.$block->name, 'block_'.$block->name)) {
                        $blockdesc = get_string('plugdesc_block_'.$block->name, 'block_'.$block->name);
                    }

                    $mform->addElement('checkbox', 'block_'.$block->name, $blockname, $blockdesc);
                    $args = [
                        'data-plugintype' => 'block',
                        'data-pluginname' => $block->name,
                        'class' => 'assignment_button',
                        'data-toggle' => 'modal',
                        'data-target' => '#catpng-inner-form'
                    ];
                    $mform->addElement('button', 'openmodal_'.$block->name, get_string('categorize', 'local_userequipment'), $args);
                    $allplugins[] = 'block_'.$block->name;
                }
            }
        }

        $mform->addElement('header', 'mheader', get_string('activities'));
        if (!empty($modules)) {
            $allcats = $DB->get_records('local_userequipment_cat', []);
            foreach ($modules as $mod) {
                $mname = $mod->name;
                $module = $DB->get_record('modules', array('name' => $mname));
                $plugincats = $DB->get_records('local_userequipment_cat_png', array('plugintype' => 'mod', 'pluginname' => $mname));
                $catdivs = '';
                if (!empty($plugincats)) {
                    foreach ($plugincats as $cat) {
                        $catdivs .= '<div alt="'.$allcats[$cat->categoryid]->name.'" class="userequipment-plugin-category" data-id="'.$allcats[$cat->categoryid]->id.'" style="background-color: '.$allcats[$cat->categoryid]->colour.'"></div>';
                    }
                }
                $args = [
                    'data-plugintype' => 'mod',
                    'data-pluginname' => $mname,
                    'class' => 'assignment_button',
                    'data-toggle' => 'modal',
                    'data-target' => '#catpng-inner-form',
                    'title' => get_string('categorize', 'local_userequipment'),
                    'id' => 'openmodal_'.$mname
                ];
                $catdivs .= html_writer::tag('button', $OUTPUT->pix_icon('t/edit', get_string('categorize', 'local_userequipment')), $args);

                if (empty($module) || !$module->visible || !is_dir($CFG->dirroot.'/mod/'.$mname)) {
                    continue;
                }
                $checklabel = get_string('pluginname', $mname);
                if (!empty($catdivs)) {
                    $checklabel .= '<br/>'.$catdivs;
                }
                $mform->addElement('checkbox', 'mod_'.$mname, $checklabel);
                $allplugins[] = 'mod_'.$mname;
            }
        }

        $mform->addElement('header', 'qheader', get_string('pluginname', 'quiz'));
        if (!empty($qtypes)) {
            $group = array();
            foreach ($qtypes as $qtype) {
                if (is_dir($CFG->dirroot.'/question/type/'.$qtype->name)) {
                    $qtypevisiblename = $qtype->displayname;
                    $group[] = $mform->createElement('checkbox', 'qtype_'.$qtype->name, '', $qtypevisiblename);
                    $allplugins[] = 'qtype_'.$qtype->name;
                }
            }
            $mform->addGroup($group, 'groupquiztype', get_string('questiontype', 'question'), array(''), false);
        }

        $this->add_action_buttons(true);
    }

    /**
     * Useless.
     *
     */
    public function set_data($defaults) {
        /*
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

        */
        parent::set_data($defaults);
    }
}