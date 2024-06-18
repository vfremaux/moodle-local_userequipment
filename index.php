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
require_once($CFG->dirroot.'/local/userequipment/classes/index.controller.php');
require_once($CFG->dirroot.'/lib/blocklib.php');

$PAGE->requires->js_call_amd('local_userequipment/bindcatpng', 'init');
$PAGE->requires->js_call_amd('local_userequipment/equipmentform', 'init');

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
    if (!local_ue_has_capability_somewhere('local/userequipment:selfequip', false, false, true)) {
        print_error('errornoselfequipementallowed', 'local_userequipment');
    }
}

// Default configuration.

$config = get_config('local_userequipment');

// User may hav asked to "unequip" (i.e. get the full standard offer). In this case, user should not
// be reinitialized to default equipment. If never equiped, we start with default ewuipement if there is some.
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
$renderer = $PAGE->get_renderer('local_userequipment');

if ($uemanager->is_enabled_for_user($USER)) {

    $what = '';
    if (optional_param('submitbutton', false, PARAM_RAW)) {
        $what = 'submit';
    }
    if (optional_param('cancel', false, PARAM_RAW)) {
        $what = 'cancel';
    }

    $needsapply = preg_grep('/^applytpl/', array_keys($_POST));
    if (!empty($needsapply)) {
        $what = 'apply';
    }

    if (optional_param('cleanup', false, PARAM_BOOL)) {
        $what = 'cleanup';
    }

    $controller = new local_userequipment\index_controller($uemanager, $url);
    if (!empty($what)) {
        $controller->receive($what);
        $returnurl = $controller->process($what);
        if (!empty($returnurl)) {
            redirect($returnurl);
        }
    }

    if (empty($data)) {
        $data = $uemanager->fetch_equipement();
        if (empty($data)) {
            $data = @$config->defaultequipment;
        }
    }

    echo $OUTPUT->header();

    if (optional_param('cleanedup', false, PARAM_BOOL)) {
        echo $OUTPUT->notification(get_string('equipmentcleaned', 'local_userequipment'));
    }

    if ($loadedtplid = optional_param('templated', false, PARAM_INT)) {
        $loaded = $DB->get_field('local_userequipment_tpl', 'name', ['id' => $loadedtplid]);
        $a = clone($user);
        $a->templatename = format_string($loaded);
        echo $OUTPUT->notification(get_string('templateapplied', 'local_userequipment', $a));
    }

    echo $renderer->render_equipmentform($uemanager, $data, false);

} else {
    echo $OUTPUT->notification(get_string('disabledforuser', 'local_userequipment'));
}

$buttonurl = $CFG->wwwroot.'/my';
echo '<br/>';
echo $OUTPUT->single_button($buttonurl, get_string('backtodashboard', 'local_userequipment'));

echo $OUTPUT->footer();
