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
 * mod_book data generator.
 *
 * @package    mod_book
 * @category   test
 * @copyright  2013 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * local_userequipment data generator class.
 *
 * @package    local_userequipment
 * @category   test
 * @copyright  2022 Valery Fremaux
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_userequipment_generator extends testing_module_generator {

    static $profiles;

    /**
     * Initialize profiles with a set of test records
     */
    public function __construct() {

        if (is_null(self::$profiles)) {

            self::$profiles[0] = new StdClass;
            self::$profiles[0]->id = 1;
            self::$profiles[0]->name = "Default";
            self::$profiles[0]->description = "Default profile applicable on user creation";
            self::$profiles[0]->descriptionformat = FORMAT_MOODLE;
            self::$profiles[0]->usercanchoose = 0;
            self::$profiles[0]->isdefault = 1;
            self::$profiles[0]->associatedsystemrole = 0;
            self::$profiles[0]->releaseroleon = 0;
            self::$profiles[0]->applyoncoursecompletion = 0;
            self::$profiles[0]->completedcourse = null;
            self::$profiles[0]->applyonprofile = 0;
            self::$profiles[0]->applywhencohortmember = 0;

            self::$profiles[1] = new StdClass;
            self::$profiles[1]->id = 2;
            self::$profiles[1]->name = "Basic instructor";
            self::$profiles[1]->description = "Basic profile with few features for basic instructors";
            self::$profiles[1]->descriptionformat = FORMAT_MOODLE;
            self::$profiles[1]->usercanchoose = 1;
            self::$profiles[1]->isdefault = 0;
            self::$profiles[1]->associatedsystemrole = 0;
            self::$profiles[1]->releaseroleon = 0;
            self::$profiles[1]->applyoncoursecompletion = 0;
            self::$profiles[1]->completedcourse = null;
            self::$profiles[1]->applyonprofile = 0;
            self::$profiles[1]->applywhencohortmember = 0;

            self::$profiles[2] = new StdClass;
            self::$profiles[2]->id = 3;
            self::$profiles[2]->name = "Advanced instructor";
            self::$profiles[2]->description = "Advanced profile with more features for trained instructors";
            self::$profiles[2]->descriptionformat = FORMAT_MOODLE;
            self::$profiles[2]->usercanchoose = 1;
            self::$profiles[2]->isdefault = 0;
            self::$profiles[2]->associatedsystemrole = 0;
            self::$profiles[2]->releaseroleon = 0;
            self::$profiles[2]->applyoncoursecompletion = 0;
            self::$profiles[2]->completedcourse = null;
            self::$profiles[2]->applyonprofile = 0;
            self::$profiles[2]->applywhencohortmember = 0;

        }

    }

    /**
     * Creates a profile.
     * @param object $record a local_userequipment_tpl record. If null, will iterate in hardcoded test profiles.
     */
    public function create_profile($record = null, $plugins = []) {
        static $lastix = 0;
        global $DB;

        if (is_null($record)) {
            if ($lastix > count(self::$profiles) - 1) {
                throw new coding_exception("More required profile creation than available in hardcoded set. Please review Generator.");
            }
            $this->create_profile(self::$profiles[$lastix], self::$profilepngs[$lastix]);
            $lastix++;
        }

        if ($DB->record_exists('local_userequipment_tpl', ['id' => $record->id])) {
            throw new coding_exception("Record with id {$record->id} is in the way. Please review test structure.");
        } else {
            $DB->insert_record('local_userequipment_tpl', $record);
        }
  }

}
