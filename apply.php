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
require_once($CFG->dirroot.'/local/userequipment/classes/applyselectors.php');
require_once($CFG->dirroot.'/lib/blocklib.php');

use local_userequipment\userequipment_manager;
use local_userequipment\selectors\ue_application_users_selector;
use local_userequipment\selectors\ue_all_users_selector;

$templateid = optional_param('template', 0, PARAM_INT);

if (!$template = $DB->get_record('local_userequipment_tpl', array('id' => $templateid))) {
    print_error('badtemplateid', 'local_userequipment');
}

$context = context_system::instance();
$PAGE->set_context($context);
$url = new moodle_url('/local/userequipment/apply.php');
$PAGE->set_url($url);

$renderer = $PAGE->get_renderer('local_user_equipment');

// Security.

require_login();
require_capability('moodle/site:config', $context);

$toapplyselector = new ue_application_users_selector('removeselect', array('template' => $templateid));
$potentialmembersselector = new ue_all_users_selector('addselect', array('template' => $templateid));

if (optional_param('add', false, PARAM_BOOL) && confirm_sesskey()) {
    $userstoadd = $potentialmembersselector->get_selected_users();
    if (!empty($userstoadd)) {
        foreach ($userstoadd as $user) {
            $SESSION->ue_selection[$user->id] = $user;
            $toapplyselector->invalidate_selected_users();
            $potentialmembersselector->invalidate_selected_users();
        }
    }
}

if (optional_param('remove', false, PARAM_BOOL) && confirm_sesskey()) {
    $userstoremove = $toapplyselector->get_selected_users();
    if (!empty($userstoremove)) {
        foreach ($userstoremove as $user) {
            unset($SESSION->ue_selection[$user->id]);
            $toapplyselector->invalidate_selected_users();
            $potentialmembersselector->invalidate_selected_users();
        }
    }
}

$message = '';
if (optional_param('apply', false, PARAM_BOOL) && confirm_sesskey()) {
    $userstoapply = @$SESSION->ue_selection;
    $manager = get_ue_manager();
    if (!empty($userstoapply)) {
        foreach ($userstoapply as $u) {
            $manager->apply_template($templateid, $u->id);
        }
        $message = get_string('usersupdated', 'local_userequipment');
    }
    unset($SESSION->ue_selection);
    redirect(new moodle_url('/local/userequipment/apply.php', array('template' => $templateid, 'message' => $message)));
}

echo $OUTPUT->header();

$message = optional_param('message', '', PARAM_TEXT);
if (!empty($message)) {
    echo $OUTPUT->notification($message, 'notifysuccess');
}

echo $OUTPUT->heading(get_string('applytemplate', 'local_userequipment', $template->name));

echo $renderer->addmembersform($templateid, $toapplyselector, $potentialmembersselector);

echo $OUTPUT->footer();