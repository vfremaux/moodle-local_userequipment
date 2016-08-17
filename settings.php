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

defined('MOODLE_INTERNAL') || die;

/**
 * @package   local_userequipment
 * @category  local
 * @copyright 2016 Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$hasconfig = false;
$hassiteconfig = false;
if (is_dir($CFG->dirroot.'/local/adminsettings')) {
    require_once($CFG->dirroot.'/local/adminsettings/lib.php');
    list($hasconfig, $hassiteconfig, $capability) = local_adminsettings_access();
} else {
    // Standard Moodle code
    $hassiteconfig = has_capability('moodle/site:config', context_system::instance());
    $hasconfig = true;
    $capability = 'moodle/site:config';
}

if ($hassiteconfig) {
    $settings = new admin_settingpage('userequipment', get_string('pluginname', 'local_userequipment'));

    $ADMIN->add('users', $settings);

    $settings->add(new admin_setting_configcheckbox('local_userequipment/enabled', get_string('enableuserequipment', 'local_userequipment'), get_string('enableuserequipment_desc', 'local_userequipment'), ''));

    $templatesurl = new moodle_url('/local/userequipment/templates.php');
    $managetemplatesstr = get_string('managetemplates', 'local_userequipment');
    $settings->add(new admin_setting_heading('templates', get_string('templates', 'local_userequipment'), '<a href="'.$templatesurl.'">'.$managetemplatesstr.'</a>'));

}

