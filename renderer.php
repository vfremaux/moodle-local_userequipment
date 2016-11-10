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
defined('MOODLE_INTERNAL') || die;

class local_user_equipement_renderer extends plugin_renderer_base {

    public function addmembersform($templateid, &$toapplyselector, &$potentialmembersselector) {
        $str = '';
        $str .= '<div id="addmembersform">';
        $formurl = new moodle_url('/local/userequipment/apply.php');
        $str .= '<form id="assignform" method="post" action="'.$formurl.'">';
        $str .= '<div>';
        $str .= '<input type="hidden" name="template" value="'.$templateid.'" />';
        $str .= '<input type="hidden" name="sesskey" value="'.sesskey().'" />';
        $str .= '<table class="generaltable generalbox pagemanagementtable boxaligncenter">';
        $str .= '<tr>';
        $str .= '<td id="existingcell">';
        $str .= '<p>';
        $str .= '<label for="removeselect">'.get_string('target', 'local_userequipment').'</label>';
        $str .= '</p>';
        $str .= $toapplyselector->display();
        $str .= '</td>';
        $str .= '<td id="buttonscell">';
        $str .= '<p class="arrow_button">';
        $str .= '<input name="add"
                        id="add"
                        type="submit"
                        value="'.$OUTPUT->larrow().'&nbsp;'.get_string('add').'"
                        title="'.get_string('add').'" /><br />';
        $str .= '<input name="remove"
                        id="remove"
                        type="submit"
                        value="'.get_string('remove').'&nbsp;'.$OUTPUT->rarrow().'"
                        title="'.get_string('remove').'" />';
        $str .= '</p>';
        $str .= '</td>';
        $str .= '<td id="potentialcell">';
        $str .= '<p>';
        $str .= '<label for="addselect">'.get_string('potentialmembers', 'local_userequipment').'</label>';
        $str .= '</p>';
        $str .= $potentialmembersselector->display();
        $str .= '</td>';
        $str .= '</tr>';
        $str .= '</table>';
        $str .= '</div>';
        $str .= '<center>';
        $str .= '<input type="checkbox" name="strict" value="1" /> '.get_string('applystrict', 'local_userequipment').'<br/>';
        $str .= '<input type="submit" name="apply" value="'.get_string('applytoselection', 'local_userequipment').'" />';
        $returl = new moodle_url('/local/userequipment/templates.php');
        $str .= '<a href="'.$returl.'">';
        $str .= '<input type="button" name="cancel" value="'.get_string('cancel', 'local_userequipment').'"  />';
        $str .= '</a>';
        $str .= '</center>';
        $str .= '</form>';
        $str .= '</div>';

        return $str;
    }

}