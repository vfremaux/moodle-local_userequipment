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

namespace local_userequipment\controllers;
defined('MOODLE_INTERNAL') || die();

/**
 * @package   local_userequipment
 * @category  local
 * @copyright 2016 Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class template_controller {

    public function process($action) {
        global $DB;

        if ($action == 'delete') {
            $tid = required_param('template', PARAM_INT);
            if ($tid) {
                $DB->delete_records('local_userequipment', array('template' => $tid));
                $DB->delete_records('local_userequipment_tpl', array('id' => $tid));
            }
        }
    }
}