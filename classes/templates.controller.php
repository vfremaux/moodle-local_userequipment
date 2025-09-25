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
 * MVC Controller for editing applicable equipment templates.
 *
 * @package     local_userequipment
 * @author      Valery Fremaux (valery.fremaux@gmail.com)
 * @copyright   2017 Valery Fremaux <valery.fremaux@gmail.com> (activeprolearn.com)
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_userequipment\controllers;

/**
 * MVC controller
 * @todo harmonicize to other controller structure.
 */
class template_controller {

    /**
     * Process command.
     */
    public function process($cmd) {
        global $DB;

        if ($cmd == 'delete') {
            $tid = required_param('templateid', PARAM_INT);
            if ($tid) {
                $DB->delete_records('local_userequipment', ['template' => $tid, 'userid' => 0]);
                $DB->delete_records('local_userequipment_tpl', ['id' => $tid]);
            }
        }
    }
}
