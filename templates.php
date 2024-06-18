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
require('../../config.php');
require_once($CFG->dirroot.'/local/userequipment/userequip_form.php');
require_once($CFG->dirroot.'/local/userequipment/lib.php');
require_once($CFG->dirroot.'/lib/blocklib.php');

$context = context_system::instance();
$PAGE->set_context($context);

// Security.
require_login();
require_capability('moodle/site:config', $context);

$url = new moodle_url('/local/userequipment/templates.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');

// Default configuration.

$config = get_config('local_userequipment');

$struserequipment = get_string('templates', 'local_userequipment');
$PAGE->navbar->add($struserequipment);
$PAGE->set_heading($SITE->fullname);

$action = optional_param('what', '', PARAM_ALPHA);
if (!empty($action)) {
    include_once($CFG->dirroot.'/local/userequipment/classes/templates.controller.php');
    $controller = new \local_userequipment\controllers\template_controller();
    $controller->process($action);
}

$templates = $DB->get_records('local_userequipment_tpl');

echo $OUTPUT->header();

echo $OUTPUT->heading($struserequipment);

if (empty($templates)) {
    echo $OUTPUT->notification(get_string('notemplates', 'local_userequipment'));
} else {
    $namestr = get_string('name');
    $countstr = get_string('plugins', 'local_userequipment');
    $canchoosestr = get_string('usercanchoose', 'local_userequipment');
    $table = new html_table();
    $table->head = array($namestr, '' /* description */, $countstr, $canchoosestr, '', '');
    $table->align = array('left', 'left', 'left', 'center', 'left', 'right');
    $table->size = array('20%', '40%', '10%', '10%', '10%', '10%');
    $table->width = '98%';

    foreach ($templates as $t) {

        $description = shorten_text(format_text($t->description, FORMAT_MOODLE), 250);

        $isdefault = ($t->isdefault) ? get_string('default', 'local_userequipment') : '';

        $count = $DB->count_records('local_userequipment', array('template' => $t->id, 'available' => 1));

        $editurl = new moodle_url('/local/userequipment/template.php', array('template' => $t->id, 'id' => 0));
        $cmds = '<a href="'.$editurl.'" alt="'.get_string('update').'" title="'.get_string('update').'"><img src="'.$OUTPUT->image_url('t/edit').'"></a>';

        $params = array('what' => 'delete', 'templateid' => $t->id, 'sesskey' => sesskey());
        $deleteurl = new moodle_url('/local/userequipment/templates.php', $params);
        $cmds .= '&nbsp;&nbsp;<a href="'.$deleteurl.'" alt="'.get_string('delete').'" title="'.get_string('delete').'"><img src="'.$OUTPUT->image_url('t/delete').'"></a>';

        $params = array('templateid' => $t->id, 'sesskey' => sesskey());
        $profileurl = new moodle_url('/local/userequipment/profile.php', $params);
        $cmds .= '&nbsp;&nbsp;<a href="'.$profileurl.'" alt="'.get_string('profile', 'local_userequipment').'" title="'.get_string('profile', 'local_userequipment').'"><img src="'.$OUTPUT->image_url('a/view_list_active').'"></a>';

        $applyurl = new moodle_url('/local/userequipment/apply.php', array('templateid' => $t->id));
        $applybutton = $OUTPUT->single_button($applyurl, get_string('applytemplatebtn', 'local_userequipment'));

        if (local_userequipment_supports_feature('application/bycsv')) {
            $applyurl = new moodle_url('/local/userequipment/pro/applybycsv.php', array('templateid' => $t->id));
            $applybutton .= $OUTPUT->single_button($applyurl, get_string('applybycsvbtn', 'local_userequipment'));
        }

        if (local_userequipment_supports_feature('application/refresh')) {
            include_once($CFG->dirroot.'/local/userequipment/pro/lib.php');
            if (local_userequipment_template_has_rules($t)) {
                // We only propose to refresh application if there are rules that restrict the amount of targetted users.
                $applyurl = new moodle_url('/local/userequipment/pro/refresh.php', array('templateid' => $t->id));
                $applybutton .= $OUTPUT->single_button($applyurl, get_string('refreshbtn', 'local_userequipment'));
            }
        }

        $canchoose = ($t->usercanchoose) ? get_string('yes') : get_string('no');

        $table->data[] = array(format_string($t->name).$isdefault, $description, (0 + @$count), $canchoose, $applybutton, $cmds);
    }

    echo html_writer::table($table);
}

$addurl = new moodle_url('/local/userequipment/template.php');
echo $OUTPUT->single_button($addurl, get_string('addtemplate', 'local_userequipment'));

echo $OUTPUT->footer();