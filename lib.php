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
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/local/userequipment/classes/manager.php');

use local_userequipment\userequipment_manager;

/**
 *
 *
 */
function get_all_plugin_classes() {

    $allplugins = array();
    $plugins = core_plugin_manager::get_plugins_of_type('blocks');
    if (!empty($plugins)) {
        foreach ($plugins as $p) {
            $allplugins[] = 'block_'.$p;
        }
    }

    $plugins = core_plugin_manager::get_plugins_of_type('mod');
    if (!empty($plugins)) {
        foreach ($plugins as $p) {
            $allplugins[] = 'mod_'.$p;
        }
    }

    $plugins = core_plugin_manager::get_plugins_of_type('rtype');
    if (!empty($plugins)) {
        foreach ($plugins as $p) {
            $allplugins[] = 'rtype_'.$p;
        }
    }

    $qtypes = question_bank::get_all_qtypes();
    if (!empty($qtypes)) {
        foreach (array_keys($qtypes) as $qtypename) {
            $allplugins[] = 'qtype_'.$qtypename;
        }
    }

    $plugins = core_plugin_manager::get_plugins_of_type('assignsubmission');
    if (!empty($plugins)) {
        foreach ($plugins as $p) {
            $allplugins[] = 'assignsubmission_'.$p;
        }
    }

    $plugins = core_plugin_manager::get_plugins_of_type('assignfeedback');
    if (!empty($plugins)) {
        foreach ($plugins as $p) {
            $allplugins[] = 'assignfeedback_'.$p;
        }
    }

    return $allplugins;
}

/**
 *
 *
 */
function initiate_new_user_equipment_event($user) {
    global $CFG, $DB;

    if (empty($CFG->enable_user_equipement)) {
        return; // Do not mark anything.
    }

    if (isset($CFG->defaultequipment)) {
        foreach ($CFG->defaultequipment as $eqkey => $equipment) {
                $parts = explode('_', $eqkey);
                $eqrec = new StdClass;
                $eqrec->plugintype = array_shift($parts);
                $eqrec->plugin = implode('_', $parts);
                $eqrec->user = $user->id;
                $eqrec->available = $equipment;
                $eqrec->timemodified = time();
                $DB->insert_record('local_userequipment', $eqrec);
        }
    }
}

/**
 * We add to user settings, not in global navigation.
 * @param object $globalnav the global navigation tree
 */
function local_userequipment_extends_navigation($globalnav) {
    global $PAGE;

    $config = get_config('local_userequipment');
    $context = context_system::instance();

    if (!empty($config->enabled) && has_capability('local/userequipment:selfequip', $context)) {
        $nav = @$PAGE->settingsnav;
        if ($nav && $usersettings = $nav->find('usercurrentsettings', navigation_node::TYPE_CONTAINER)) {
            $url = new moodle_url('/local/userequipment/index.php');
            $usersettings->add(get_string('equipme', 'local_userequipment'), $url, navigation_node::TYPE_SETTING);
        }
    }
}

function get_ue_manager() {
    return userequipment_manager::instance();
}

/**
 * checks if a user has a some named capability effective somewhere in a course.
 * @param string $capability;
 * @param bool $excludesystem
 * @param bool $excludesite
 * @param bool $doanything
 * @param string $contextlevels restrict to some contextlevel may speedup the query.
 */
function local_ue_has_capability_somewhere($capability, $excludesystem = true, $excludesite = true, $doanything = false, $contextlevels = '') {
    global $USER, $DB;

    $contextclause = '';

    if ($contextlevels) {
        list($sql, $params) = $DB->get_in_or_equal(explode(',', $contextlevels), SQL_PARAMS_QM);
        $contextclause = "
           AND ctx.contextlevel $sql
        ";
    }
    $params[] = $capability;
    $params[] = $USER->id;

    // this is a a quick rough query that may not handle all role override possibility

    $sql = "
        SELECT
            COUNT(DISTINCT ra.id)
        FROM
            {role_capabilities} rc,
            {role_assignments} ra,
            {context} ctx
        WHERE
            rc.roleid = ra.roleid AND
            ra.contextid = ctx.id AND
            rc.capability = ?
            $contextclause
            AND ra.userid = ? AND
            rc.permission = 1
    ";
    $hassome = $DB->count_records_sql($sql, $params);

    // $hassome = get_user_capability_course($capability, $USER->id, false);
    if ($excludesite && !empty($hassome) && array_key_exists(SITEID, $hassome)) {
        unset($hassome[SITEID]);
    }

    if (!empty($hassome)) {
        return true;
    }

    $systemcontext = context_system::instance();
    if (!$excludesystem && has_capability($capability, $systemcontext, $USER->id, $doanything)) {
        return true;
    }

    return false;
}
