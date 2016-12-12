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
 * @author    Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Standard upgrade handler.
 * @param int $oldversion
 */
function xmldb_local_userequipment_upgrade($oldversion = 0) {
    global $CFG, $THEME, $DB;

    $result = true;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016092100) {

        // Define table local_shop to be created.
        $table = new xmldb_table('local_userequipment');

        // Adding fields to table local_userequipment.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, null, null);
        $table->add_field('plugintype', XMLDB_TYPE_CHAR, '32', null, null, null, null);
        $table->add_field('plugin', XMLDB_TYPE_CHAR, '32', null, null, null, null);
        $table->add_field('available', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('template', XMLDB_TYPE_INTEGER, '10', XMLDB_NOTNULL, null, null, null);

        // Adding keys to table local_userequipment.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_index('ix_unique_plugin', XMLDB_INDEX_UNIQUE, array('plugintype', 'plugin'));
        $table->add_index('ix_userid', XMLDB_INDEX_NOTUNIQUE, array('userid'));
        $table->add_index('ix_template', XMLDB_INDEX_NOTUNIQUE, array('template'));

        // Conditionally launch create table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_userequipment_tpl');

        // Adding fields to table local_userequipment_tpl.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '11', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '64', null, null, null, null);

        // Adding keys to table local_userequipment_tpl.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_index('ix_name', XMLDB_INDEX_UNIQUE, array('name'));

        // Conditionally launch create table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2016092100, 'local', 'userequipment');
    }

    if ($oldversion < 2016111300) {

        // Define table to be updated.
        $table = new xmldb_table('local_userequipment_tpl');

        // Define field to add.
        $field = new xmldb_field('usercanchoose');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'name');

        // Launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2016111300, 'local', 'userequipment');
    }

    if ($oldversion < 2016112600) {

        // Define table to be updated.
        $table = new xmldb_table('local_userequipment_tpl');

        // Define field to add.
        $field = new xmldb_field('description');
        $field->set_attributes(XMLDB_TYPE_TEXT, 'medium', null, null, null, null, 'name');

        // Launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field to add.
        $field = new xmldb_field('descriptionformat');
        $field->set_attributes(XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, 0, 'description');

        // Launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Play installer to load default templates.
        require_once($CFG->dirroot.'/local/userequipment/db/install.php');
        xmldb_local_userequipment_install();

        upgrade_plugin_savepoint(true, 2016112600, 'local', 'userequipment');
    }

    return $result;
}