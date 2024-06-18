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
 * Tests the equipment controls
 *
 * @package    local_userequipment
 * @category   test
 * @copyright  2013 Valery Fremaux
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once($CFG->dirroot.'/local/userequipment/classes/manager.php');

/**
 *  tests class for local_userequipment.
 */
class local_userequipment_equipment_testcase extends advanced_testcase {

    public function test_manager() {
        global $DB;

        $this->resetAfterTest();

        // Setup moodle content environment.

        $category1 = $this->getDataGenerator()->create_category();
        $category2 = $this->getDataGenerator()->create_category();

        $params = array('name' => 'Moodlescript test course 2', 'shortname' => 'PFTEST2', 'category' => $category1->id, 'idnumber' => 'PFTEST2');
        $course2 = $this->getDataGenerator()->create_course($params);
        $contextid2 = context_course::instance($course2->id)->id;

        $user1 = $this->getDataGenerator()->create_user(array('email'=>'user1@example.com', 'username'=>'user1'));
        $user2 = $this->getDataGenerator()->create_user(array('email'=>'user2@example.com', 'username'=>'user2'));

        $this->setAdminUser();

        $this->assertTrue(empty($enrolled));

        $this->assertTrue(true == $DB->get_record('block_instances', array('blockname' => 'html', 'parentcontextid' => $contextid1)));

    }

}