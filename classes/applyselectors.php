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
 * User selector for applicaton of equipment profiles.
 *
 * @package     local_userequipment
 * @author      Valery Fremaux (valery.fremaux@gmail.com)
 * @copyright   2017 Valery Fremaux <valery.fremaux@gmail.com> (activeprolearn.com)
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_userequipment\selectors;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/user/selector/lib.php');
require_once($CFG->dirroot.'/group/lib.php');

/**
 * Base class to avoid duplicating code.
 */
abstract class ue_application_users_selector_base extends \user_selector_base {

    /**
     * @param string $name control name
     * @param array $options should have two elements with keys pageid and courseid.
     */
    public function __construct($name, $options = array()) {
        $options['accesscontext'] = \context_system::instance();
        parent::__construct($name, $options);
    }

    /**
     * Get selector options
     */
    protected function get_options() {
        $options = parent::get_options();
        return $options;
    }
}

/**
 * Never populated at load time
 */
class ue_application_users_selector extends ue_application_users_selector_base {

    /**
     * @param string $name control name
     * @param array $options should have two elements with keys pageid and courseid.
     */
    public function __construct($name, $options = array()) {
        $options['accesscontext'] = \context_system::instance();
        parent::__construct($name, $options);
    }

    /**
     * Get selector options.
     */
    protected function get_options() {
        $options = parent::get_options();
        return $options;
    }

    /**
     * Find part of users to select.
     * @param string $search
     */
    public function find_users($search) {
        global $SESSION;

        $selected = (empty($SESSION->ue_selection)) ? array() : $SESSION->ue_selection;

        return array($selected);
    }
}

/**
 * User selector subclass for the list of users who are not in a certain group.
 * Used on the add group members page.
 */
class ue_all_users_selector extends ue_application_users_selector_base {
    const MAX_USERS_PER_PAGE = 100;

    /**
     * Returns the user selector JavaScript module
     * @return array
     */
    public function get_js_module() {
        return self::$jsmodule;
    }

    /**
     * Find part of users to select.
     * @param string $search
     */
    public function find_users($search) {
        global $DB;

        // Get the search condition.
        list($searchcondition, $searchparams) = $this->search_sql($search, 'u');

        $sql = "
            SELECT
                u.id AS userid,
                ".$this->required_fields_sql('u')."
            FROM
                {user} u
            WHERE
                u.deleted = 0
                AND $searchcondition
            ORDER BY
                u.lastname,
                u.firstname
        ";
        $users = $DB->get_records_sql($sql, $searchparams);

        return array($users);
    }
}
