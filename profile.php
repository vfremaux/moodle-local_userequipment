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
require_once($CFG->dirroot.'/local/userequipment/classes/profile.controller.php');
require_once($CFG->dirroot.'/lib/blocklib.php');

$PAGE->requires->js_call_amd('local_userequipment/bindcatpng', 'init');
$PAGE->requires->js_call_amd('local_userequipment/equipmentform', 'init');

use local_userequipment\userequipment_manager;

$templateid = required_param('templateid', PARAM_INT);

if ($templateid == 0) {
    print_error("Cannot edit template id = 0");
}

$context = context_system::instance();
$PAGE->set_context($context);

// Security.

require_login();
require_capability('moodle/site:config', $context);

$url = new moodle_url('/local/userequipment/template.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');

// Default configuration.

$config = get_config('local_userequipment');

// defaultequipment designates a template that is the default.
if (!empty($config->defaultequipment)) {
    // Initialise first time with the defaut set.
}

$struserequipment = get_string('pluginname', 'local_userequipment');
$PAGE->navbar->add(fullname($USER), new moodle_url('/user/view.php', array('id' => $user->id)));
$PAGE->navbar->add($struserequipment);
$PAGE->set_heading($SITE->fullname);

$course = $DB->get_record('course', array('id' => SITEID));
$profile = $DB->get_record('local_userequipment_tpl', ['id' => $templateid]);
$manager = core_plugin_manager::instance();
$uemanager = userequipment_manager::instance();
$renderer = $PAGE->get_renderer('local_userequipment');

if (optional_param('submitbutton', false, PARAM_RAW)) {
    $what = 'submit';
}
if (optional_param('cancel', false, PARAM_RAW)) {
    $what = 'cancel';
}

// $form = new UserEquipmentForm();

if (!empty($what)) {
    $controller = new local_userequipment\profile_controller($uemanager, $url);
    $controller->receive($what);
    $returnurl = $controller->process($what);
    if (!empty($returnurl)) {
        redirect($returnurl);
    }
}

if (empty($data)) {
    $data = $uemanager->fetch_equipement(null, $templateid);
    if (empty($data)) {
        // If this profile has never been initialized, get the default one and take its values as default.
        $data = $uemanager->fetch_default_equipement();
    }
}

echo $OUTPUT->header();

if ($what == 'submit') {
    echo $OUTPUT->notification(get_string('profileupdated', 'local_userequipment'));
}

echo $OUTPUT->heading(get_string('editprofile', 'local_userequipment', format_string($profile->name)));

echo $renderer->render_equipmentform($uemanager, $data, true);

echo $OUTPUT->footer();
