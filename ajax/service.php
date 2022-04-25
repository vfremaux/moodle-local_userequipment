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
 * @package     mod_tracker
 * @category    mod
 * @author      Clifford Tham, Valery Fremaux > 1.8
 *
 * Summary for administrators
 */
define('AJAX_SCRIPT', 1);

require('../../../config.php');
require_once($CFG->dirroot.'/mod/tracker/locallib.php');
require_once($CFG->dirroot.'/mod/tracker/lib.php');

$context = context_system::instance();
$PAGE->set_context($context);
require_login();

$action = required_param('what', PARAM_TEXT);

if ($action == 'getplugincategories') {

    $args = new StdClass;
    $plugintype = required_param('type', PARAM_TEXT);
    $pluginname = required_param('name', PARAM_TEXT);

    $context = context_system::instance();
    $PAGE->set_context($context);

    $renderer = $PAGE->get_renderer('local_userequipment');
    $return = $renderer->list_plugin_bindings_form($plugintype, $pluginname);

    echo $return;
    exit(0);
}

if ($action == 'updateplugincategories') {
    /* Updates categories binding by deleting all previous mapping and replacing records. */
    $type = required_param('type', PARAM_INT);
    $plugin = required_param('name', PARAM_INT);
    $categoryids = required_param_array('categories', PARAM_INT);

    $result = new StdClass;
    $DB->delete_records('local_userequipment_cat_png', array('plugintype' => $type, 'pluginname' => $plugin));

    if (empty($categoryids)) {
        foreach ($categoryids as $catid) {
            $rec = new StdClass;
            $rec->categoryid = $catid;
            $rec->plugintype = $type;
            $rec->pluginname = $plugin;
            // Problème avec les descriptions... pas stockées au bon endroit.
            $DB->insert_record('local_userequipment_cat_png', $rec);
        }
    }

    $result->result = 'success';

    $context = context_system::instance();
    $PAGE->set_context($context);

    // Renders category displays in form.
    $renderer = $PAGE->get_renderer('local_userequipment');
    $args = new StdClass;
    $args->plugintype = $type;
    $args->pluginname = $plugin;
    $result->catset = $renderer->list_plugin_bindings($args);

    echo json_encode($result);
    exit(0);
}

if ($action == 'gethelp') {
    $modname = required_param('modname', PARAM_TEXT);
    $sm = get_string_manager();

    $jsonobject = new StdClass;

    $jsonobject->modname = $modname;
    $help = '';
    if ($sm->string_exists('modulename_help', $modname)) {
        $help = get_string('modulename_help', $modname);
    }
    $jsonobject->help = $help;
    $jsonobject->label = get_string('pluginname', $modname);
    $jsonobject->image = $OUTPUT->pix_icon('icon', '', $modname); 
    echo json_encode($jsonobject);
    exit(0);
}

echo "Unknown action $action ";