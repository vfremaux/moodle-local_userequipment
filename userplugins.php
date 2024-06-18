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
require_once($CFG->dirroot.'/local/userequipment/forms/query_user_form.php');

$userid = optional_param('userid', '', PARAM_INT);

$context = context_system::instance();
$PAGE->set_context($context);
$url = new moodle_url('/local/userequipment/userplugins.php');
$PAGE->set_url($url);

require_login();
require_capability('moodle/site:config', $context);

$PAGE->set_heading(get_string('userplugins', 'local_userequipment'));
$PAGE->set_pagelayout('admin');

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('userplugins', 'local_userequipment'));

// echo $OUTPUT->notification("Not yet implemented.");
$mform = new QueryUserForm();

if ($data = $mform->get_data()) {
    // Takes a filter and explores. Give back links to a single plugin to match.
    $sql = "
        SELECT DISTINCT
            userid,
            u.firstname,
            u.lastname,
            u.username
        FROM
            {local_userequipment} lue,
            {user} u
        WHERE
            lue.userid > 0 AND
            lue.userid = u.id
        ORDER BY
            u.lastname, u.firstname
    ";
    $equiped = $DB->get_records_sql($sql);
    $queryuser = $data->queryuser;
    $queryuser = str_replace('?', '.', $queryuser);
    $queryuser = str_replace('*', '.*', $queryuser);
    $template = new Stdclass;
    $template->equiped = [];
    if (!empty($equiped)) {
        foreach ($equiped as $u) {
            if (preg_match('/'.$queryuser.'/i', $u->firstname.' '.$u->lastname)) {
                $u->userurl = new moodle_url('/local/userequipment/userplugins.php', ['userid' => $u->userid]);
                $template->equiped[] = $u;
            }
        }
    } else {
            echo $OUTPUT->notification(get_string('noneequiped', 'local_userequipment'));
    }

    echo $OUTPUT->render_from_template('local_userequipment/userlist', $template);

    $mform->display();

} else {
    if (!empty($userid)) {
        $user = $DB->get_record('user', ['id' => $userid]);
        $pluginreqs = $DB->get_records('local_userequipment', ['userid' => $userid]);
        $template = new StdClass;
        $template->username = $user->firstname.' '.$user->lastname.' ('.$user->username.')';
        $template->queried = [];
        $smanager = get_string_manager();
        if ($pluginreqs) {
            foreach ($pluginreqs as $eq) {
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
            echo $OUTPUT->render_from_template('local_userequipment/pluginlist', $template);
        } else {
            echo $OUTPUT->notification(get_string('noneequiped', 'local_userequipment'));
        }

        $mform->display();
    } else {
        $mform->display();
    }
}

echo $OUTPUT->footer();
