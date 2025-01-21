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
 * Upgrade sequence
 *
 * @package     local_userequipment
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2016 Valery Fremaux (www.activeprolearn.com)
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Standard upgrade handler.
 * @param int $oldversion
 */
function xmldb_local_userequipment_upgrade($oldversion = 0) {
    global $CFG, $THEME, $DB;

    $result = true;

    $dbman = $DB->get_manager();

    if ($oldversion < 2016092100) {

        // Define table local_userequipment to be created.
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

    if ($oldversion < 2016121500) {
        // Define table to be updated.
        $table = new xmldb_table('local_userequipment_tpl');

        // Define field to add.
        $field = new xmldb_field('isdefault');
        $field->set_attributes(XMLDB_TYPE_INTEGER, 1, null, null, null, 0, 'usercanchoose');

        // Launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field to add.
        $field = new xmldb_field('associatedsystemrole');
        $field->set_attributes(XMLDB_TYPE_INTEGER, 4, null, null, null, 0, 'isdefault');

        // Launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field to add.
        $field = new xmldb_field('releaseroleon');
        $field->set_attributes(XMLDB_TYPE_INTEGER, 4, null, null, null, 0, 'associatedsystemrole');

        // Launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2016121500, 'local', 'userequipment');
    }

    if ($oldversion < 2020101501) {

        // Define table local_userequipment_cat to be created.
        $table = new xmldb_table('local_userequipment_cat');

        // Adding fields to table local_userequipment_cat.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '64', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('description', XMLDB_TYPE_TEXT, 'medium', null, null, null, null);
        $table->add_field('descriptionformat', XMLDB_TYPE_INTEGER, 4, null, XMLDB_NOTNULL, null, 0);
        $table->add_field('colour', XMLDB_TYPE_CHAR, '8', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_userequipment_cat.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for local_userequipment_cat.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Userequipment savepoint reached.
        upgrade_plugin_savepoint(true, 2020101501, 'local', 'userequipment');
    }

    if ($oldversion < 2020110200) {

        // Define field id to be added to local_userequipment_cat_png.
        $table = new xmldb_table('local_userequipment_cat_png');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('categoryid', XMLDB_TYPE_INTEGER, 11, null, XMLDB_NOTNULL, null, null);
        $table->add_field('plugintype', XMLDB_TYPE_CHAR, '16', null, XMLDB_NOTNULL, null, null);
        $table->add_field('pluginname', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL, null, null);
        $table->add_field('plugindescription', XMLDB_TYPE_TEXT, 'medium', null, XMLDB_NOTNULL, null, null);
        $table->add_field('plugindescriptionformat', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, 0);
        $table->add_field('sortorder', XMLDB_TYPE_INTEGER, 4, null, XMLDB_NOTNULL, null, 0);

        // Adding keys to table local_userequipment_cat_png.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_index('ix-category', XMLDB_INDEX_NOTUNIQUE, ['categoryid']);

        // Conditionally launch add field id.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Userequipment savepoint reached.
        upgrade_plugin_savepoint(true, 2020110200, 'local', 'userequipment');
    }

    if ($oldversion < 2022042500) {
        // Define field id to be added to local_userequipment_png.
        $table = new xmldb_table('local_userequipment_png');
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('plugintype', XMLDB_TYPE_CHAR, '16', null, XMLDB_NOTNULL, null, null);
        $table->add_field('pluginname', XMLDB_TYPE_CHAR, '32', null, XMLDB_NOTNULL, null, null);
        $table->add_field('plugindescription', XMLDB_TYPE_TEXT, 'medium', null, XMLDB_NOTNULL, null, null);
        $table->add_field('plugindescriptionformat', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, 0);

        // Adding keys to table local_userequipment_png.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_index('ix_uniq_plugin', XMLDB_INDEX_UNIQUE, ['plugintype, pluginname']);

        // Conditionally launch add field id.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_userequipment_cat_png');
        // delete fields.
        $field = new xmldb_field('plugindescription');
        $field->set_attributes(XMLDB_TYPE_TEXT, 'medium', null, null, null, null);

        // Launch add field.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        $field = new xmldb_field('plugindescriptionformat');
        $field->set_attributes(XMLDB_TYPE_INTEGER, 4, null, XMLDB_NOTNULL, null, 0);

        // Launch add field.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Userequipment savepoint reached.
        upgrade_plugin_savepoint(true, 2022042500, 'local', 'userequipment');
    }

    if ($oldversion < 2022062604) {

        // Define table to be updated.
        $table = new xmldb_table('local_userequipment_tpl');

        // Define field to add.
        $field = new xmldb_field('applyoncoursecompletion');
        $field->set_attributes(XMLDB_TYPE_INTEGER, 1, null, XMLDB_NOTNULL, null, 0, 'releaseroleon');

        // Launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field to add.
        $field = new xmldb_field('completedcourse');
        $field->set_attributes(XMLDB_TYPE_INTEGER, 10, null, XMLDB_NOTNULL, null, 0, 'applyoncoursecompletion');

        // Launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Userequipment savepoint reached.
        upgrade_plugin_savepoint(true, 2022062604, 'local', 'userequipment');
    }

    if ($oldversion < 2022062605) {

        // Define table to be updated.
        $table = new xmldb_table('local_userequipment_tpl');

        // Define field to add.
        $field = new xmldb_field('applyonprofile');
        $field->set_attributes(XMLDB_TYPE_CHAR, 255, null, null, null, null, 'completedcourse');

        // Launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field to add.
        $field = new xmldb_field('applywhencohortmember');
        $field->set_attributes(XMLDB_TYPE_INTEGER, 10, null, XMLDB_NOTNULL, null, 0, 'applyonprofile');

        // Launch add field.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Userequipment savepoint reached.
        upgrade_plugin_savepoint(true, 2022062605, 'local', 'userequipment');
    }

    return $result;
}