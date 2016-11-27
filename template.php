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
require_once($CFG->dirroot.'/lib/blocklib.php');

$templateid = optional_param('template', 0, PARAM_INT);

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
$manager = get_ue_manager();

if (!isset($config->defaultequipment)) {
     $config->defaultequipment = $manager->init_defaults();
}

$struserequipment = get_string('template', 'local_userequipment');
$PAGE->navbar->add($struserequipment);
$PAGE->set_heading($SITE->fullname);

$course = $DB->get_record('course', array('id' => SITEID));

$mform = new UserEquipmentForm($url, array('istemplate' => true, 'template' => $templateid));

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/userequipment/templates.php'));
}

if ($data = $mform->get_data()) {
    $manager->add_update_template($data);
    redirect(new moodle_url('/local/userequipment/templates.php'));
}

if (empty($data)) {
    if (!empty($templateid)) {
        $data = $manager->fetch_equipement(null, $templateid);
        $templaterec = $DB->get_record('local_userequipment_tpl', array('id' => $templateid));
        $data = array_merge($data, (array) $templaterec);
        $data['template'] = $templateid;
    } else {
        $data = @$config->defaultequipment;
    }
}
$data['istemplate'] = 1;
echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('template', 'local_userequipment'));
$mform->set_data((object)$data);
$mform->display();

echo $OUTPUT->footer();
