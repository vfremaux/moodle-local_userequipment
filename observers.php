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

defined('MOODLE_INTERNAL') || die();

/**
 * @package   local_userequipment
 * @category  local
 * @copyright 2016 Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class local_userequipment_event_observer {

    /**
     * Triggered when a user is created
     * initialize the standard user equipement list
     */
    public static function on_user_created($e) {
        global $DB;

        $config = get_config('local_userequipment');

        $DB->delete_records('local_user_equipment', array('user' => $e->userid));

        // Transfer default equipement to user equipement.
        if (!empty($config->defaultequipment)) {
            foreach ($config->defaultequipment as $key => $available) {
                $record = new StdClass();
                $record->user = $e->id;
                $parts = explode('_', $key);
                $record->plugintype = array_shift($parts);
                $record->plugin = implde('_', $parts);
                $record->available = $available;
                $DB->insert_record('local_user_equipment', $record);
            }
        }
    }

    public static function on_user_loggedin($e) {
        global $DB;

        $userpref = $DB->get_record('user_preferences', array('userid' => $e->userid, 'name' => 'moodleuserlevel'));
        if (!$userpref) {
            if (local_has_capability_somewhere('moodle/course:manageactivities')) {
                redirect(new moodle_url('/local/userequipment/profile_init.php', array('id' => $e->userid)));
            }
        }
    }
}
