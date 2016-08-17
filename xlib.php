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
require_once($CFG->dirroot.'/local/userequipment/lib.php');

/**
 * checks if a plugin of some plugintype is in user's equipment
 */
function check_user_equipment($plugintype, $plugin, $userid = 0) {
    global $USER;
    global $CFG;
    global $DB;

    if (!$userid) $userid = $USER->id;

    $config = get_config('local_userequipment');

    if (!$config->enabled) return true; // everything allowed

    $manager = get_ue_manager();
    return $manager->check_user_equipment($plugintype, $plugin, $userid);
}
