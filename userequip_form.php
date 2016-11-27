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

require_once($CFG->dirroot.'/mod/resource/lib.php');
require_once($CFG->dirroot.'/lib/questionlib.php');

class UserEquipmentForm extends moodleform {

    public function definition() {
        global $CFG, $DB;

        $mform = $this->_form;

        $context = context_system::instance();
        $maxbytes = $CFG->maxbytes;
        $maxfiles = 100;
        $this->editoroptions = array('trusttext' => true,
                                     'subdirs' => false,
                                     'maxfiles' => $maxfiles,
                                     'maxbytes' => $maxbytes,
                                     'context' => $context);

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
        } else {
            // End user informative about user profile.
            $mform->addElement('header', 'theader', get_string('userequipment', 'local_userequipment'));

            $mform->addElement('html', get_string('ueinfo_tpl', 'local_userequipment'));

            if ($selfusabletemplates = $DB->get_records('local_userequipment_tpl', array('usercanchoose' => true))) {
                $mform->addElement('html', get_string('ueselfinfo_tpl', 'local_userequipment'));
                foreach($selfusabletemplates as $tpl) {
                    $group = array();
                    $group[] = $mform->createElement('submit', 'applytpl'.$tpl->id, get_string('applytemplate', 'local_userequipment', $tpl->name));
                    $desc = '<div class="pull-right userequipment-half-column">'.format_text($tpl->description, $tpl->descriptionformat).'</div>';
                    $group[] = $mform->createElement('static', 'chk'.$tpl->id);
                    $mform->addGroup($group, 'group'.$tpl->id, '', array($desc), false, false);
                }
            }

            $mform->addElement('submit', 'cleanup', get_string('cleanup', 'local_userequipment'));

        }

        $manager = core_plugin_manager::instance();
        $sm = get_string_manager();
        $blocks = $manager->get_plugins_of_type('block');

        $modules = $manager->get_plugins_of_type('mod');

        $qtypes = $manager->get_plugins_of_type('qtype');

        $formats = $manager->get_plugins_of_type('format');

        $allplugins = array();

        $mform->addElement('header', 'fheader', get_string('courseformats'));
        if (!empty($formats)) {
            foreach ($formats as $format) {
                $fname = $format->name;
                $formatname = get_string("pluginname", "format_$fname");
                if ($formatname == "[[format$fname]]") {
                    $formatname = get_string("format$fname");
                }
                $mform->addElement('checkbox', 'format_'.$fname, $formatname);
                $allplugins[] = 'format_'.$fname;
            }
        }

        $mform->addElement('header', 'bheader', get_string('blocks'));
        if (!empty($blocks)) {
            if (!file_exists($CFG->dirroot.'/course/format/page')) {
                // Standard implementation, give the block list flat and uncategorized.
                foreach ($blocks as $block) {
                    if ($bck = $DB->get_record('block', array('name' => $block->name))) {
                        if (!$bck->visible) {
                            continue;
                        }
                        $blockobject = block_instance($block->name);
                        $blockname = @$blockobject->title;
                        if (empty($blockname)) {
                            $blockname = get_string('blockname', 'block_'.$block->name);
                        }
                        $blockdesc = get_string('blockdesc_block_'.$block->name, 'local_userequipment');

                        $mform->addElement('checkbox', 'block_'.$block->name, $blockname, $blockdesc);
                        $allplugins[] = 'block_'.$block->name;
                    }
                }
            } else {
                // Page enhanced implementation, get categorization.

                $blockcats = $DB->get_records('format_page_pfamily', array('type' => 'block'), 'shortname', 'shortname,id,name');
                $blockcategories = array();

                foreach ($blocks as $blockid => $block) {

                    if (!$DB->get_field('block', 'visible', array('name' => $block->name))) {
                        continue;
                    }

                    // Deleted blocks.
                    if (!is_dir($CFG->dirroot.'/blocks/'.$block->name)) {
                        continue;
                    }

                    $blockshort = str_replace('block_', '', $block->name);
                    $pageplugin = $DB->get_record('format_page_plugins', array('type' => 'block', 'plugin' => $blockshort));

                    $blockcategories[@$blockcats[$pageplugin->familyname]->shortname][] = $block;
                }

                ksort($blockcategories);

                foreach ($blockcategories as $catshort => $catblocks) {

                    $group = array();
                    if (!array_key_exists($catshort, $blockcats)) {
                        $catname = get_string('other', 'local_userequipment');
                    } else {
                        $catname = format_string($blockcats[$catshort]->name);
                    }

                    foreach ($catblocks as $block) {
                        $blockobject = block_instance($block->name);
                        $blockname = @$blockobject->title;
                        if (empty($blockname)) {
                            if ($sm->string_exists('blockname', 'block_'.$block->name)) {
                                $blockname = get_string('blockname', 'block_'.$block->name);
                            } else {
                                // Last try.
                                $blockname = get_string('pluginname', 'block_'.$block->name);
                            }
                        }
                        if ($sm->string_exists('plugdesc_block_'.$block->name, 'local_userequipment')) {
                            $blockdesc = get_string('plugdesc_block_'.$block->name, 'local_userequipment');
                        } else {
                            $blockdesc = '';
                        }
                        $blocknamespan = '<span data-tooltip="'.$blockdesc.'" data-tooltip-position="bottom">';
                        $blocknamespan .= $blockname.' </span>';
                        $group[] = $mform->createElement('checkbox', 'block_'.$block->name, '', $blocknamespan);
                        $allplugins[] = 'block_'.$block->name;
                    }
                    $mform->addGroup($group, 'groupcat'.$catshort, $catname, '', false);
                }
            }
        }

        $mform->addElement('header', 'mheader', get_string('activities'));
        if (!file_exists($CFG->dirroot.'/course/format/page')) {
            if (!empty($modules)) {
                foreach ($modules as $mod) {
                    $mname = $mod->name;
                    $module = $DB->get_record('modules', array('name' => $mname));
                    if (empty($module) || !$module->visible) {
                        continue;
                    }
                    $mform->addElement('checkbox', 'mod_'.$mname, get_string('pluginname', $mname));
                    $allplugins[] = 'mod_'.$mname;
                }
            }
        } else {
            if (!empty($modules)) {
                // Page enhanced implementation, get categorization.

                $modcats = $DB->get_records('format_page_pfamily', array('type' => 'mod'), 'shortname', 'shortname,id,name');
                $modcategories = array();

                foreach ($modules as $id => $mod) {

                    if (!$DB->get_field('modules', 'visible', array('name' => $mod->name))) {
                        continue;
                    }

                    $modshort = str_replace('block_', '', $mod->name);
                    $pageplugin = $DB->get_record('format_page_plugins', array('type' => 'mod', 'plugin' => $modshort));
                    $modcategories[@$modcats[$pageplugin->familyname]->shortname][] = $mod;
                }

                ksort($modcategories);

                foreach ($modcategories as $catshort => $catmods) {

                    $group = array();
                    if (!array_key_exists($catshort, $modcats)) {
                        $catname = get_string('other', 'local_userequipment');
                    } else {
                        $catname = format_string($modcats[$catshort]->name);
                    }

                    foreach ($catmods as $mod) {
                        if ($sm->string_exists('modulename_help', $mod->name)) {
                            $moddesc = strip_tags(get_string('modulename_help', $mod->name));
                            $modnamespan = '<span data-tooltip="'.$moddesc.'" data-tooltip-position="bottom">';
                            $modnamespan .= get_string('pluginname', $mod->name).'</span>';
                        } else {
                            $modnamespan = get_string('pluginname', $mod->name);
                        }
                        $group[] = $mform->createElement('checkbox', 'mod_'.$mod->name, '', $modnamespan.' ');
                        $allplugins[] = 'mod_'.$mod->name;
                    }
                    $mform->addGroup($group, 'groupmods'.$catshort, $catname, ' ', false);
                }
            }
        }

        $mform->addElement('header', 'qheader', get_string('pluginname', 'quiz'));
        if (!empty($qtypes)) {
            $group = array();
            foreach ($qtypes as $qtype) {
                $qtypevisiblename = $qtype->displayname;
                $group[] = $mform->createElement('checkbox', 'qtype_'.$qtype->name, '', $qtypevisiblename);
                $allplugins[] = 'qtype_'.$qtype->name;
            }
            $mform->addGroup($group, 'groupquiztype', get_string('questiontype', 'question'), '', array(' '));
        }

        $this->add_action_buttons(true);
    }

    public function set_data($defaults) {

        $context = $this->editoroptions['context'];

        $descdraftideditor = file_get_submitted_draft_itemid('description_editor');
        $currenttext = file_prepare_draft_area($descdraftideditor, $context->id, 'local_userequipment', 'description_editor', @$defaults->id, array('subdirs' => true), @$defaults->description);
        $defaults = file_prepare_standard_editor($defaults, 'description', $this->editoroptions, $context, 'local_userequipment', 'templatedesc', @$defaults->id);
        $defaults->description = array('text' => $currenttext, 'format' => $defaults->descriptionformat, 'itemid' => $descdraftideditor);

        parent::set_data($defaults);
    }
}