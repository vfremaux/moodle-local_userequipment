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
require_once($CFG->dirroot.'/local/userequipment/classes/manager.php');

use local_userequipment\userequipment_manager;

class local_userequipment_event_observer {

    /**
     * Triggered when a user is created
     * initialize the standard user equipement list
     */
    public static function on_user_created(\core\event\user_created $event) {
        global $DB, $CFG;

        $config = get_config('local_userequipment');

        if (empty($config->auto_setup_new_users)) {
            return;
        }

        // If there is a default defined. Apply it to any created user.
        if ($defaulttemplate = $DB->get_record('local_userequipment_tpl', array('isdefault' => 1))) {

            $DB->delete_records('local_userequipment', array('userid' => $event->userid));

            $uemanager = userequipment_manager::instance();
            $uemanager->apply_template($defaulttemplate->id, $event->userid, true);
        }

        if (local_userequipment_supports_feature('application/profilerule')) {
            include ($CFG->dirroot.'/local/userequipment/pro/observers.php');
            return \local_userequipment_extra_event_observer::on_user_created($event);
        }
    }

    public static function on_user_loggedin(\core\event\user_loggedin $event) {
        global $DB;

        $config = get_config('local_userequipment');

        if (empty($config->ask_users_to_profile)) {
            return;
        }

        $uemanager = userequipment_manager::instance();
        if ($uemanager->is_marked_cleaned($event->userid)) {
            return;
        }

        $userpref = $DB->get_record('user_preferences', ['userid' => $event->userid, 'name' => 'moodleuserlevel']);
        $hasuserequipment = $DB->count_records('local_userequipment', ['userid' => $event->userid]);
        if (!$userpref && !$hasuserequipment) {
            if (local_ue_has_capability_somewhere('local/userequipment:selfequip', false, false, false)) {
                redirect(new moodle_url('/local/userequipment/profile_init.php', array('id' => $event->userid)));
            }
        }
    }

    /** 
     * When a course is completed and associated to some equipment profile, apply the
     * additional equipment. Pass to pro zone.
     */
    public static function on_course_completed(\core\event\course_completed $event) {
        global $CFG;

        if (local_userequipment_supports_feature('application/coursecompletion')) {
            include ($CFG->dirroot.'/local/userequipment/pro/observers.php');
            return \local_userequipment_extra_event_observer::on_course_completed($event);
        }
        return false;
    }

    /** 
     * When a user is added to some cohort. Pass to Pro.
     */
    public static function on_cohort_member_added(\core\event\cohort_member_added $event) {
        global $CFG;

        if (local_userequipment_supports_feature('application/coursecompletion')) {
            include ($CFG->dirroot.'/local/userequipment/pro/observers.php');
            return \local_userequipment_extra_event_observer::on_cohort_member_added($event);
        }
        return false;
    }
}
