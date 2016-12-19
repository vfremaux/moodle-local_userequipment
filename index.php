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
require('../../config.php');
require_once($CFG->dirroot.'/local/userequipment/userequip_form.php');
require_once($CFG->dirroot.'/local/userequipment/lib.php');
require_once($CFG->dirroot.'/local/userequipment/classes/manager.php');
require_once($CFG->dirroot.'/lib/blocklib.php');

use local_userequipment\userequipment_manager;

$id = optional_param('id', 0, PARAM_INT);

if ($id && !$user = $DB->get_record('user', array('id' => $id))) {
    print_error('invaliduser');
} else {
    $user = $USER;
}
$context = context_user::instance($user->id);
$PAGE->set_context($context);

// Security.
require_login();

$url = new moodle_url('/local/userequipment/index.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');

if (!has_capability('local/userequipment:equip', $context)) {
    // Not authorised users can only self equip.
    // Note that system enabled equip users can equip all users.
    $user = $USER;
}

if ($user->id == $USER->id) {
    if (!local_ue_has_capability_somewhere('local/userequipment:selfequip', false, false, false)) {
        print_error('errornoselfequipementallowed', 'local_userequipment');
    }
}

// Default configuration.

$config = get_config('local_userequipment');

$usercleaned = $DB->get_field('user_preferences', 'value', array('userid' => $USER->id, 'name' => 'noequipment'));
if (!isset($config->defaultequipment) && !$usercleaned) {
     $config->defaultequipment = userequipment_manager::init_defaults();
}

$struserequipment = get_string('pluginname', 'local_userequipment');
$PAGE->navbar->add(fullname($USER), new moodle_url('/user/view.php', array('id' => $user->id)));
$PAGE->navbar->add($struserequipment);
$PAGE->set_heading($SITE->fullname);

$course = $DB->get_record('course', array('id' => SITEID));
$manager = core_plugin_manager::instance();
$uemanager = userequipment_manager::instance();

if ($uemanager->is_enabled_for_user($USER)) {

    $form = new UserEquipmentForm();

    $cleanedup = optional_param('cleanedup', false, PARAM_BOOL);
    $templated = optional_param('updated', false, PARAM_BOOL);

    if (!$form->is_cancelled()) {
        if ($data = $form->get_data()) {
            if (!empty($data->cleanup)) {

                $uemanager->delete_equipment($USER);
                $uemanager->mark_cleaned($USER);

                redirect(new moodle_url($url, array('cleanedup' => true)));
            }

            if ($usertemplates = $DB->get_records('local_userequipment_tpl', array('usercanchoose' => true))) {
                foreach ($usertemplates as $tpl) {
                    $key = 'applytpl'.$tpl->id;
                    if (!empty($data->$key)) {
                        $uemanager->apply_template($tpl->id, $USER->id, true); // Apply strictly removing all previous keys.
                        redirect(new moodle_url($url, array('updated' => true)));
                    }
                }
            }
            $uemanager->add_update_user($data, $user->id);
            redirect(new moodle_url($url, array('cleanedup' => false)));
        }
    }

    if (empty($data)) {
        $data = $uemanager->fetch_equipement($USER);
        if (empty($data)) {
            $data = @$config->defaultequipment;
        }
    }

    echo $OUTPUT->header();

    if ($cleanedup) {
        echo $OUTPUT->notification(get_string('equipmentcleaned', 'local_userequipment'));
    }

    if ($templated) {
        echo $OUTPUT->notification(get_string('templateapplied', 'local_userequipment'));
    }

    $form->set_data((object) $data);
    $form->display();
} else {
    echo $OUTPUT->notification(get_string('disabledforuser', 'local_userequipment'));
}

echo $OUTPUT->footer();
