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
 * Event observers
 *
 * @package     local_userequipment
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2016 Valery Fremaux (www.activeprolearn.com)
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

$observers = array (
    array(
        'eventname'   => '\core\event\user_created',
        'callback'    => 'local_userequipment_event_observer::on_user_created',
        'includefile' => '/local/userequipment/observers.php',
        'internal'    => true,
        'priority'    => 9999,
    ),

    array(
        'eventname'   => '\core\event\user_loggedin',
        'callback'    => 'local_userequipment_event_observer::on_user_loggedin',
        'includefile' => '/local/userequipment/observers.php',
        'internal'    => true,
        'priority'    => 9999,
    ),
);