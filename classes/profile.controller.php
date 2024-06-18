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

namespace local_userequipment;

use moodle_url;
use StdClass;

defined('MOODLE_INTERNAL') || die();

class profile_controller {

    protected $uemanager;

    protected $url;

    public function __construct(\local_userequipment\userequipment_manager $uemanager, $url) {
        $this->uemanager = $uemanager;
        $this->url = $url;
    }

    public function receive($cmd) {
        if ($cmd == 'submit') {
            $this->data = new StdClass;
            $this->data->userid = 0;
            $this->data->templateid = required_param('templateid', PARAM_INT);
            $this->data->plugins = [];

            // Get params from POST input
            $profilekeys = array_keys($_POST);
            foreach ($profilekeys as $k) {
                if (preg_match('/mark_(.*)/', $k)) {
                    $val = clean_param($_POST[$k], PARAM_BOOL);
                    if ($val) {
                        $pluginfdqn = str_replace('mark_', '', $k);
                        $this->data->plugins[$pluginfdqn] = $val;
                    }
                }
            }
            $this->received = 1;
        }

        if ($cmd == 'cancel') {
            $this->received = 1;
        }
    }

    public function process($cmd) {
        global $DB, $USER;

        if (!$this->received) {
            throw new \coding_exception('Data must be received in controller before operation. this is a programming error.');
        }

        if ($cmd == 'cancel') {
            $this->data = new StdClass;
            return new moodle_url('/local/userequipment/templates.php');
        }

        if ($cmd == 'submit') {
            // Clear previous setting for the profile.
            $params = ['template' => $this->data->templateid, 'userid' => 0];
            $DB->delete_records('local_userequipment', $params);

            $time = time();

            // Inject the new map.
            if (!empty($this->data->plugins)) {
                foreach ($this->data->plugins as $pgn => $val) {
                    $parts = explode('_', $pgn);
                    $plugintype = array_shift($parts);
                    $pluginname = implode('_', $parts);

                    $rec = new StdClass;
                    $rec->userid = $this->data->userid;
                    $rec->template = $this->data->templateid;
                    $rec->plugintype = $plugintype;
                    $rec->plugin = $pluginname;
                    $rec->available = $val;
                    $rec->timemodified = $time;
                    $DB->insert_record('local_userequipment', $rec);
                }
            }
        }

    }

}