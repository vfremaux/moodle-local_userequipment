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
 * @package     local_userequipment
 * @category    local
 * @copyright   2016 Valery Fremaux (valery.fremaux@gmail.com)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

// Settings default init.

if (is_dir($CFG->dirroot.'/local/adminsettings')) {
    // Integration driven code.
    require_once($CFG->dirroot.'/local/adminsettings/lib.php');
    list($hasconfig, $hassiteconfig, $capability) = local_adminsettings_access();
} else {
    // Standard Moodle code.
    $capability = 'moodle/site:config';
    $hasconfig = $hassiteconfig = has_capability($capability, context_system::instance());
}

if ($hassiteconfig) {
    $settings = new admin_settingpage('userequipment', get_string('pluginname', 'local_userequipment'));

    $ADMIN->add('users', $settings);

    $key = 'local_userequipment/enabled';
    $label = get_string('enableuserequipment', 'local_userequipment');
    $desc = get_string('enableuserequipment_desc', 'local_userequipment');
    $settings->add(new admin_setting_configcheckbox($key, $label, $desc, ''));

    $templatesurl = new moodle_url('/local/userequipment/templates.php');
    $managetemplatesstr = get_string('managetemplates', 'local_userequipment');

    $options = array();
    $options['0'] = get_string('allusers', 'local_userequipment');
    $options['capability'] = get_string('capabilitycontrol', 'local_userequipment');
    $options['profilefield'] = get_string('profilefieldcontrol', 'local_userequipment');
    $key = 'local_userequipment/disable_control';
    $label = get_string('configdisablecontrol', 'local_userequipment');
    $desc = get_string('configdisablecontrol_desc', 'local_userequipment');
    $settings->add(new admin_setting_configselect($key, $label, $desc, 'capability', $options));

    $key = 'local_userequipment/disable_control_value';
    $label = get_string('configdisablecontrolvalue', 'local_userequipment');
    $desc = get_string('configdisablecontrolvalue_desc', 'local_userequipment');
    $settings->add(new admin_setting_configtext($key, $label, $desc, 'local/userequipment:isdisabled', PARAM_TEXT));

    $label = get_string('templates', 'local_userequipment');
    $settings->add(new admin_setting_heading('templates', $label, '<a href="'.$templatesurl.'">'.$managetemplatesstr.'</a>'));

}

