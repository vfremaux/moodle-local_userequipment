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
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/local/userequipment/lib.php');

/**
 * checks if a plugin of some plugintype is in user's equipment.
 *
 * User equipment has special behaviour for course format whose individual application
 * to users is NOT available in standard moodle. For course formats, we will take into account
 * an individual application facility given by the course format (@see /course/format/page/db/access.php)
 * special capabilities format/page:canchoose and format/page:canchangeformat
 *
 * This will wait a moodle evolution to let better control on who can use which course format.
 *
 * @param string $plugintype the plugin type (mod, format, block, etc...)
 * @param string $plugin the plugin name
 * @param $userid the user the check needs to be done for. Defaults to $USER->id
 * @param object $currentcontext the current context of the form. May be a course context if updating or a category context
 * for a new course
 * @param object $courseform a representation of the course form data.
 * @return false if not allowed, true if allowed
 */
function check_user_equipment($plugintype, $plugin, $userid = 0, $currentcontext = null, $courseform = null) {
    global $USER, $COURSE;

    $config = get_config('local_userequipment');

    /**
     * Special "force disable for use" feature. Works even if user_equipment plans are disabled.
     */
     if (!empty($config->forcedisableforuse)) {
        $maskedplugins = preg_split('/[\s,]+/', $config->forcedisableforuse);
        if (in_array($plugintype.'_'.$plugin, $maskedplugins)) {
            return false;
        }
     }

    /**
     * Set default user as current.
     */
    if (!$userid) {
        $userid = $USER->id;
    }

    if ($plugintype == 'format') {
        if (is_null($currentcontext)) {
            $currentcontext = context_course::instance($COURSE->id);
        }
        if (!has_capability('format/page:canchangeformat', $currentcontext)) {
            debug_trace("User cannot change format", TRACE_DEBUG);
            if ($currentcontext instanceof context_course) {
                // If we are updating. 
                debug_trace("block ? {$courseform->format} vs. ".$plugin, TRACE_DEBUG);
                if ($courseform->format == $plugin) {
                    return true;
                } else {
                    debug_trace("block any other ".$plugin, TRACE_DEBUG);
                    return false;
                }
            }
            // Else let pass to choose the first course format.
        }

        // Always allow the current format !
        if ($currentcontext instanceof course_context) {
            // We are updating.
            if ($courseform->format == $plugin) {
                // Let pass ! this is the current format of the course, even if user us format disabled !
                return true;
            }
        }

        // Process page/format:canchoose capability.
        /**
         * This will eliminate any possibility to use the page format to
         * users having NOT this special capability in the surrounding category or inherited.
         */
        if ($plugin == 'page') {
            if (has_capability('format/page:canchoose', $currentcontext)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Now common behaviour for all range of plugins.
     */

    if (empty($config->enabled)) {
        return true; // Everything allowed.
    }

    $manager = get_ue_manager(); // Calls the manager singleton.
    return $manager->check_user_equipment($plugintype, $plugin, $userid);
}
