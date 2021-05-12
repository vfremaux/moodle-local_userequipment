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
 * Allows managing categories
 *
 * @package    local_userequipement
 * @category   local
 * @author     Nicolas Maligue
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 * @see        categories.controller.php for associated controller.
 */
defined('MOODLE_INTERNAL') || die();

if ($action) {
    require($CFG->dirroot.'/local/userequipment/classes/categories.controller.php');
}

$categories = userequipment_get_categories($categories->id);
echo $OUTPUT->heading('categories');
echo "<center>";
echo $OUTPUT->box_start();

if (!empty($categories)) {

    $namestr = get_string('name');
    $descriptionstr = get_string('description');
    $colourstr = get_string('colours');

    $table = new html_table();

    $table->head = array(
        "<b>$namestr</b>",
        "<b>$descriptionstr</b>",
        "<b>$colourstr</b>"
    );

    $table->align = array(
        'center',
        'left',
        'left',
        'left',
        'left'
    );

    $table->size = array(
        '5%',
        '15%',
        '30%',
        '30%',
        '15%'
    );

    $table->width = '100%';

    foreach ($categories as $category) {
        $commands = '<div class="categorycommands">';
        $curl = new moodle_url('/local/userequipment/selectorcategories.php', array('id' => $cm->id, 'catid' => $category->id));
        $commands .= '<a href="'.$curl.'">'.$OUTPUT->pix_icon('t/edit', get_string('edit')).'</a>';
        $params = array('id' => $cm->id, 'what' => 'deletecategory', 'catid' => $category->id);
        $cmdurl = new moodle_url('/local/categories/selectorcategories.php', $params);
        $commands .= '&nbsp;<a id="delete" href="'.$curl.'">'.$OUTPUT->pix_icon('t/delete', get_string('delete')).'</a>';

        if ($category->sortorder > 1) {
            $params = array('id' => $cm->id, 'index' => 'categories', 'what' => 'raisecategory', 'catid' => $category->id);
            $curl = new moodle_url('/local/userequipment/selectorcategories.php', $params);
            $commands .= '&nbsp;<a href="'.$curl.'">'.$OUTPUT->pix_icon('t/up', '').'</a>';
        } else {
            $commands .= '&nbsp;'.$OUTPUT->pix_icon('up_shadow', '', 'userequipment');
        }

        if ($category->sortorder < count($categories)) {
            $params = array('id' => $cm->id, 'index' => 'categories', 'what' => 'lowercategory', 'catid' => $category->id);
            $curl = new moodle_url('/local/userequipment/selectorcategories.php', $params);
            $commands .= '&nbsp;<a href="'.$curl.'">'.$OUTPUT->pix_icon('t/down', '').'</a>';
        } else {
            $commands .= '&nbsp;'.$OUTPUT->pix_icon('down_shadow', '', 'userequipment');
        }

        $commands .= '</div>';
        $symbolurl = userequipement_get_symbols_baseurl($userequipment) . $category->symbol;
        $symbolimage = "<img src=\"{$symbolurl}\" />";
        $category->format = 1;

        $table->data[] = array(
            $symbolimage,
            format_string($category->name),
            format_string(format_text($category->description, $category->format)),
            format_string(format_text($category->result, $category->format)),
            $commands
        );
    }

    echo html_writer::table($table);
} else { 
    echo $OUTPUT->notification(get_string('categories', 'userequipment'), 'notifyproblem');
}

echo $OUTPUT->box_end();

$params = array('id' => $cm->id, 'catid' => -1, 'what' => 'add', 'howmany' => 1);

echo '<p>';
echo $OUTPUT->single_button(new moodle_url('/local/userequipment/selectorcategories.php', $params), get_string('addone', 'userequipment'), 'get');
$options['howmany'] = 3;
echo $OUTPUT->single_button(new moodle_url('/local/userequipment/selectorcategories.php', $params), get_string('addthree', 'userequipment'), 'get');
echo '</center>';
echo '</p>';
