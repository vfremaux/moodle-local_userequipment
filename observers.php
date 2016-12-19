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

require_once($CFG->dirroot.'/local/userequipment/lib.php');

class local_userequipment_event_observer {

    /**
     * Triggered when a user is created
     * initialize the standard user equipement list
     */
    public static function on_user_created($e) {
        global $DB;

        $config = get_config('local_userequipment');

        if (empty($config->auto_setup_new_users)) {
            return;
        }

        $defaulttemplate = $DB->get_record('local_userequipment', array('isdefault' => 1));

        $DB->delete_records('local_userequipment', array('user' => $e->userid));

        $uemanager = userequipment_manager::instance();
        $uemanager->apply_template($defaulttemplate->id, $e->userid, true);
    }

    public static function on_user_loggedin($e) {
        global $DB;

        $config = get_config('local_userequipment');

        if (empty($config->ask_users_to_profile)) {
            return;
        }

        $userpref = $DB->get_record('user_preferences', array('userid' => $e->userid, 'name' => 'moodleuserlevel'));
        if (!$userpref) {
            if (local_ue_has_capability_somewhere('local/userequipment:selfequip', false, false, false)) {
                redirect(new moodle_url('/local/userequipment/profile_init.php', array('id' => $e->userid)));
            }
        }
    }
}
