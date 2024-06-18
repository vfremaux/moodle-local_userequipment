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
 * @package    local_userequipment
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->dirroot.'/local/userequipment/forms/query_plugin_form.php');

$plugin = optional_param('plugin', '', PARAM_TEXT);

$context = context_system::instance();
$PAGE->set_context($context);
$url = new moodle_url('/local/userequipment/pluginusers.php');
$PAGE->set_url($url);

require_login();
require_capability('moodle/site:config', $context);

$PAGE->set_heading(get_string('pluginusers', 'local_userequipment'));
$PAGE->set_pagelayout('admin');

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('pluginusers', 'local_userequipment'));

// echo $OUTPUT->notification("Not yet implemented.");
$mform = new QueryPluginForm();
$smanager = get_string_manager();

if ($data = $mform->get_data()) {
    // Takes a filter and explores. Give back links to a single plugin to match.
    $sql = "
        SELECT DISTINCT
            CONCAT(plugintype, '_', plugin) as pkey,
            plugintype, 
            plugin
        FROM
            {local_userequipment}
        ORDER BY
            plugintype, plugin
    ";
    $equipped = $DB->get_records_sql($sql);
    $queryplugin = $data->queryplugin;
    $queryplugin = str_replace('?', '.', $queryplugin);
    $queryplugin = str_replace('*', '.*', $queryplugin);
    $template = new Stdclass;
    $template->queried = [];
    if (!empty($equipped)) {
        foreach ($equipped as $eq) {
            if (preg_match('/'.$queryplugin.'/', $eq->plugintype.'_'.$eq->plugin)) {
                if ($smanager->string_exists('pluginname', $eq->plugintype.'_'.$eq->plugin)) {
                    $eq->pluginname = get_string('pluginname', $eq->plugintype.'_'.$eq->plugin);
                } else if ($smanager->string_exists('blocknname', $eq->plugintype.'_'.$eq->plugin)) {
                    $eq->pluginname = get_string('blockname', $eq->plugintype.'_'.$eq->plugin);
                } else {
                    $eq->pluginname = $eq->plugintype.'_'.$eq->plugin;
                }
                $eq->pluginurl = new moodle_url('/local/userequipment/pluginusers.php', ['plugin' => $eq->plugintype.'_'.$eq->plugin]);
                $template->queried[] = $eq;
            }
        }
    } else {
            echo $OUTPUT->notification(get_string('noneequiped', 'local_userequipment'));
    }

    echo $OUTPUT->render_from_template('local_userequipment/pluginlist', $template);

    $mform->display();

} else {
    if (!empty($plugin)) {
        $parts = explode('_', $plugin);
        $type = array_shift($parts);
        $pluginname = implode('_', $parts);
        $select = ' plugintype = :plugintype AND plugin = :plugin AND userid > 0 ';
        $userreqs = $DB->get_records_select('local_userequipment', $select, ['plugintype' => $type, 'plugin' => $pluginname]);

        $template = new StdClass;
        if ($smanager->string_exists('pluginname', $type.'_'.$pluginname)) {
            $template->pluginname = get_string('pluginname', $type.'_'.$pluginname);
        } else if ($smanager->string_exists('blocknname', $type.'_'.$pluginname)) {
            $template->pluginname = get_string('blockname', $type.'_'.$pluginname);
        } else {
            $template->pluginname = $plugin;
        }
        $template->equiped = [];
        if ($userreqs) {
            foreach ($userreqs as $ureq) {
                $u = $DB->get_record('user', ['id' => $ureq->userid]);
                $u->userurl = new moodle_url('/local/userequipment/userplugins.php', ['userid' => $u->id]);
                $template->equiped[] = $u;
            }
            echo $OUTPUT->render_from_template('local_userequipment/userlist', $template);
        } else {
            echo $OUTPUT->notification(get_string('noneequiped', 'local_userequipment'));
        }

        $mform->display();
    } else {
        $mform->display();
    }
}

echo $OUTPUT->footer();
