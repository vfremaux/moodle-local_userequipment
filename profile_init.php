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
$context = context_user::instance($id);
$PAGE->set_context($context);

// Security.
require_login();

$url = new moodle_url('/local/userequipment/index.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);

require_capability('local/userequipment:selfequip', $context);

$profilewizardmanager = new local_userequipement_profile_wizard();

echo $OUTPUT->header();

$userpref = $DB->get_record('user_preferences', array('userid' => $id, 'name' => 'moodlelevel'));
if ($userpref) {
    $step = optional_paral('step', 0, PARAM_INT);
    $profilewizardmanager->receive($step);
    echo $profilewizardmanager->display($step);
}

echo $OUTPUT->footer();