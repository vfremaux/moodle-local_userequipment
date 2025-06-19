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
 * MVC controller for index.php.
 *
 * @package     local_userequipment
 * @author      Valery Fremaux (valery.fremaux@gmail.com)
 * @copyright   2017 Valery Fremaux <valery.fremaux@gmail.com> (activeprolearn.com)
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_userequipment;

use moodle_url;
use StdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * an MVC controller
 */
class index_controller {

    /** @var data to process */
    protected $data;

    /** @var tells we have recieved data to proces */
    protected $received;

    /** @var user equipement manager instance */
    protected $uemanager;

    /** @var base url */
    protected $url;

    /**
     * Constructor
     */
    public function __construct(\local_userequipment\userequipment_manager $uemanager, $url) {
        $this->uemanager = $uemanager;
        $this->url = $url;
    }

    /**
     * Receive data.
     * @param string $cmd
     */
    public function receive($cmd) {
        global $USER;

        switch ($cmd) {

            case 'submit': {
                $this->data = new StdClass();
                $this->data->userid = $USER->id;
                $this->data->templateid = 0;
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
                break;
            }

            case 'apply': {
                // Should only be one here. Capture the template id in the key.
                $applykeys = preg_grep('/^applytpl/', array_keys($_POST));
                $key = array_shift($applykeys);
                preg_match('/^applytpl(\d+)/', $key, $matches);
                $this->data = new StdClass;
                $this->data->templateid = $matches[1];
                break;
            }

            case 'cleanup':
            case 'cancel': {
                $this->data = new StdClass;
                break;
            }
        }

        $this->received = true;
    }

    /**
     * Process the command
     * @param string $cmd
     */
    public function process($cmd) {
        global $DB, $USER;

        if (!$this->received) {
            throw new \coding_exception('Data must be received in controller before operation. this is a programming error.');
        }

        if ($cmd == 'submit') {
            // Updates manual changes in equipment, from the individual plugin list.
            if (!empty($this->data)) {
                $this->uemanager->add_update_user($this->data->plugins, $this->data->userid);
                return new moodle_url($this->url);
            }
        } else if ($cmd == 'apply') {
            // Applies a predefined template.
            $this->uemanager->apply_template($this->data->templateid, $USER->id, true); // Apply strictly removing all previous keys.
            return new moodle_url($this->url, array('templated' => $this->data->templateid));
        } else if ($cmd == 'cleanup') {
            // When a user wants to delete all his equipement and access to the whole unfiltered moodle.
            $this->uemanager->delete_user_equipment($USER);
            $this->uemanager->mark_cleaned($USER);
            return new moodle_url($this->url, array('cleanedup' => true));
        }
    }
}
