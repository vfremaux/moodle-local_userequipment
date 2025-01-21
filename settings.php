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
 * Global settings.
 *
 * @package     local_userequipment
 * @copyright   2016 Valery Fremaux (valery.fremaux@gmail.com)
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/local/userequipment/lib.php');

// Settings default init.

if ($hassiteconfig) {

    $settings = new admin_settingpage('userequipment', get_string('pluginname', 'local_userequipment'));

    $key = 'local_userequipment/auto_setup_new_users';
    $label = get_string('configautosetupnewusers', 'local_userequipment');
    $desc = get_string('configautosetupnewusers_desc', 'local_userequipment');
    $settings->add(new admin_setting_configcheckbox($key, $label, $desc, 0));

    $key = 'local_userequipment/ask_users_to_profile';
    $label = get_string('configaskuserstoprofile', 'local_userequipment');
    $desc = get_string('configaskuserstoprofile_desc', 'local_userequipment');
    $settings->add(new admin_setting_configcheckbox($key, $label, $desc, 0));

    $key = 'local_userequipment/allow_self_tuning';
    $label = get_string('configallowselftuning', 'local_userequipment');
    $desc = get_string('configallowselftuning_desc', 'local_userequipment');
    $default = 1;
    $settings->add(new admin_setting_configcheckbox($key, $label, $desc, $default));

    $templatesurl = new moodle_url('/local/userequipment/templates.php');
    $managetemplatesstr = get_string('managetemplates', 'local_userequipment');
    $label = get_string('templates', 'local_userequipment');
    $settings->add(new admin_setting_heading('templates', $label, '<a href="'.$templatesurl.'">'.$managetemplatesstr.'</a>'));

    if (local_userequipment_supports_feature('emulate/community') == 'pro') {
        include_once($CFG->dirroot.'/local/userequipment/pro/localprolib.php');
        \local_userequipment\local_pro_manager::add_settings($ADMIN, $settings);
    }

    $pluginusersurl = new moodle_url('/local/userequipment/pluginusers.php');
    $pluginusersstr = get_string('pluginusers', 'local_userequipment');
    $userprofileurl = new moodle_url('/local/userequipment/userplugins.php');
    $userprofilestr = get_string('userplugins', 'local_userequipment');
    $label = get_string('tools', 'local_userequipment');
    $html = '<a href="'.$pluginusersurl.'">'.$pluginusersstr.'</a><br/>';
    $html .= '<a href="'.$userprofileurl.'">'.$userprofilestr.'</a>';
    $settings->add(new admin_setting_heading('tools', $label, $html));

    $settingsurl = new moodle_url('/admin/settings.php', ['section' => 'localsettinguserequipment']);
    $managesettingsstr = get_string('gotopluginsettings', 'local_userequipment');
    $label = get_string('pluginsettings', 'local_userequipment');
    $settings->add(new admin_setting_heading('mainsettings', $label, '<a href="'.$settingsurl.'">'.$managesettingsstr.'</a>'));

    $ADMIN->add('users', $settings);

    // Needs this condition or there is error on login page.
    $pluginsettings = new admin_settingpage('localsettinguserequipment', get_string('pluginname', 'local_userequipment'));
    $ADMIN->add('localplugins', $pluginsettings);

    $key = 'local_userequipment/enabled';
    $label = get_string('enableuserequipment', 'local_userequipment');
    $desc = get_string('enableuserequipment_desc', 'local_userequipment');
    $pluginsettings->add(new admin_setting_configcheckbox($key, $label, $desc, ''));

    $options = array();
    $options['0'] = get_string('allusers', 'local_userequipment');
    $options['capability'] = get_string('capabilitycontrol', 'local_userequipment');
    $options['profilefield'] = get_string('profilefieldcontrol', 'local_userequipment');
    $key = 'local_userequipment/disable_control';
    $label = get_string('configdisablecontrol', 'local_userequipment');
    $desc = get_string('configdisablecontrol_desc', 'local_userequipment');
    $pluginsettings->add(new admin_setting_configselect($key, $label, $desc, 'capability', $options));

    $key = 'local_userequipment/disable_control_value';
    $label = get_string('configdisablecontrolvalue', 'local_userequipment');
    $desc = get_string('configdisablecontrolvalue_desc', 'local_userequipment');
    $pluginsettings->add(new admin_setting_configtext($key, $label, $desc, 'local/userequipment:isdisabled', PARAM_TEXT));

    $key = 'local_userequipment/useenhancedmodchooser';
    $label = get_string('configuseenhancedmodchooser', 'local_userequipment');
    $desc = get_string('configuseenhancedmodchooser_desc', 'local_userequipment');
    $default = 0;
    $pluginsettings->add(new admin_setting_configcheckbox($key, $label, $desc, $default));

    $key = 'local_userequipment/forcedisableforuse';
    $label = get_string('configforcedisableforuse', 'local_userequipment');
    $desc = get_string('configforcedisableforuse_desc', 'local_userequipment');
    $default = '';
    $pluginsettings->add(new admin_setting_configtext($key, $label, $desc, $default));

    if (local_userequipment_supports_feature('emulate/community') == 'pro') {
        include_once($CFG->dirroot.'/local/userequipment/pro/prolib.php');
        $promanager = local_userequipment\pro_manager::instance();
        $promanager->add_settings($ADMIN, $pluginsettings);
    } else {
        $label = get_string('plugindist', 'local_userequipment');
        $desc = get_string('plugindist_desc', 'local_userequipment');
        $pluginsettings->add(new admin_setting_heading('plugindisthdr', $label, $desc));
    }
}

