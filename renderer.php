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

require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/local/userequipment/lib.php');

class local_userequipment_renderer extends plugin_renderer_base {

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
        $str .= $toapplyselector->display(true);
        $str .= '</td>';

        $str .= '<td id="buttonscell">';
        $str .= '<p class="arrow_button">';
        $str .= '<input name="add"
                        id="add"
                        type="submit"
                        value="'.$this->output->larrow().'&nbsp;'.get_string('add').'"
                        title="'.get_string('add').'" /><br />';
        $str .= '<input name="remove"
                        id="remove"
                        type="submit"
                        value="'.get_string('remove').'&nbsp;'.$this->output->rarrow().'"
                        title="'.get_string('remove').'" />';
        $str .= '</p>';
        $str .= '</td>';

        $str .= '<td id="potentialcell">';
        $str .= '<p>';
        $str .= '<label for="addselect">'.get_string('potentialmembers', 'local_userequipment').'</label>';
        $str .= '</p>';
        $str .= $potentialmembersselector->display(true);
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

    /**
     * Renders all divs that show plugin categories
     */
    public function list_plugin_bindings($plugintype, $pluginname) {
        global $DB;
        static $categories;

        $template = new StdClass;

        if (is_null($categories)) {
            $categories = $DB->get_records('local_userequipment_cat');
        }

        $pbindings = $DB->get_records('local_userequipment_cat_png', ['plugintype' => $plugintype, 'pluginname' => $pluginname]);
        $template->plugintype = $plugintype;
        $template->pluginname = $pluginname;
        $template->pluginvisiblename = get_string('pluginname', $plugintype.'_'.$pluginname);

        if (!empty($pbindings)) {
            foreach ($pbindings as $bnd) {
                $bindtpl = new StdClass;
                $bindtpl->id = $bnd->id;
                $bindtpl->categoryid = $bnd->categoryid;
                $bindtpl->colour = $categories[$bnd->categoryid]->colour;
                $bindtpl->categoryname = format_string($categories[$bnd->categoryid]->name);

                $template->categories[] = $bindtpl;
            }
        }

        return $OUTPUT->render_from_template('local_userequipement/plugin_categories', $template);
    }

    /**
     * Renders a form for selecting associated categories. form is post formatted by a boostrap selectpicker.
     * @param string $plugintype the type of the plugin
     * @param string $pluginname the canonical name of the plugin
     */
    public function list_plugin_bindings_form($plugintype, $pluginname) {
        global $DB, $OUTPUT;

        $template = new StdClass;

        $categories = $DB->get_records('local_userequipment_cat');

        if (empty($pluginname) || empty($plugintype)) {
            throw new moodle_exception("plugintye and plugin name expected ");
        }

        $pbindings = $DB->get_records('local_userequipment_cat_png', ['plugintype' => $plugintype, 'pluginname' => $pluginname]);
        $selectedcats = [];
        if (!empty($pbindings)) {
            foreach ($pbindings as $pbinding) {
                $selectedcats[] = $pbinding->categoryid;
            }
        }

        if ($plugintype != 'mod') {
            $template->plugindisplayname = get_string('pluginname', $plugintype.'_'.$pluginname);
        } else {
            $template->plugindisplayname = get_string('pluginname', $pluginname);
        }

        if (!empty($categories)) {

            foreach ($categories as $cat) {
                $cattpl = new StdClass;
                $cattpl->id = $cat->id;
                $cattpl->name = $cat->name;
                $cattpl->desc = $cat->description;
                $cattpl->colour = $cat->colour;
                if (in_array($cat->id, $selectedcats)) {
                    $cattpl->selected = 'selected';
                }

                $template->categories[] = $cattpl;
            }
        } else {
            $template->nocategories = true;
        }

        return $OUTPUT->render_from_template('local_userequipment/editcatpng_reload', $template);
    }

    /**
     * Renders the modchooser lauch button
     * @param $section when the modchooser needs to be attached to a return section (section enabled formats) the section num.
     * @return a full "add module" button activating the modal modchooser.
     */
    public function render_modchooser_link($sectionid = 0, $sectionnum = 0) {
        $args = [
            'class' => 'openmodal-activitychooser-link',
            'href' => '#',
            'data-toggle' => 'modal',
            'data-sectionid' => $sectionid,
            'data-sectionnum' => $sectionnum,
            'data-target' => '#userequipment-activitychooser',
            'id' => 'openmodal-activitychooser-'.$sectionid
        ];
        return html_writer::tag('button', get_string('addamodule', 'local_userequipment'), $args);
    }

    /**
     * Render modchooser modal. Should be rendered only once.
     */
    public function render_modchooser() {
        global $DB, $OUTPUT, $COURSE, $PAGE, $CFG;
        static $ueinitialized = false;

        if ($ueinitialized) {
            return '';
        }

        $ueinitialized = true;
        $PAGE->requires->js_call_amd('local_userequipment/modchooser', 'init');
 
        $pluginmanager = core_plugin_manager::instance();
        $activities = $pluginmanager->get_enabled_plugins('mod');
        $sm = get_string_manager();

        $template = new StdClass;
        $template->filters = [];

        // Get them once and cache in variable.
        $sql = "
            SELECT DISTINCT
                uc.*
            FROM
                {local_userequipment_cat} uc,
                {local_userequipment_cat_png} ucp
            WHERE
                ucp.categoryid = uc.id
            ORDER BY
                uc.sortorder
        ";
        $allcats = $DB->get_records_sql($sql, []);
        foreach ($allcats as $cat) {
            $filtertpl = new StdClass;
            $filtertpl->id = $cat->id;
            $filtertpl->name = $cat->name;
            $filtertpl->colour = $cat->colour;
            $template->filters[] = $cat;
        }

        $ueconfig = get_config('local_userequipment');
        $uemanager = get_ue_manager();
        foreach (array_keys($activities) as $modname) {

            // User Equipement additions if installed.
            if (!empty($ueconfig->enabled)) {
                if (!$uemanager->check_user_equipment('mod', $modname)) {
                    continue;
                }
            }
            $help = '';
            $plugintpl = new StdClass;
            $plugintpl->modname = $modname;
            if ($sm->string_exists('modulename_help', $modname)) {
                $help = get_string('modulename_help', $modname);
            }
            $plugintpl->help = $help;
            $plugintpl->name = get_string('pluginname', $modname);
            $plugintpl->image = $OUTPUT->pix_icon('icon', '', $modname);
            if ($COURSE->format == 'page') {
                // This sideway ensures the $SESSION->format_page_cm_insertion_page will be set.
                include_once($CFG->dirroot.'/course/format/page/classes/page.class.php');
                $page = format\page\course_page::get_current_page();
                $sectionnum = $page->get_section();
                $plugintpl->addmodurl = new moodle_url('/course/format/page/mod.php', ['id' => $COURSE->id, 'add' => $modname, 'section' => $sectionnum, 'sr' => $sectionnum, 'insertinpage' => $page->id, 'sesskey' => sesskey()]);
            } else {
                $plugintpl->addmodurl = new moodle_url('/course/mod.php', ['id' => $COURSE->id, 'add' => $modname, 'section' => 0, 'sr' => 0]);
            }
            $plugintpl->categories = [];
            // get categories assigned to this module.
            $catpngs = $DB->get_records('local_userequipment_cat_png', ['plugintype' => 'mod', 'pluginname' => $modname]);

            $catclasses = [];
            if (!empty($catpngs)) {
                foreach ($catpngs as $catpng) {
                    $cattpl = new StdClass;
                    $cattpl->id = $allcats[$catpng->categoryid]->id;
                    $cattpl->name = $allcats[$catpng->categoryid]->name;
                    $cattpl->colour = $allcats[$catpng->categoryid]->colour;
                    $cattpl->classes = 'cat-n'.$allcats[$catpng->categoryid]->id;
                    $plugintpl->categories[] = $cattpl;
                    $catclasses[] = 'cat-'.$cattpl->id;
                }
            }

            if (!empty($catclasses)) {
                $plugintpl->catclasses = implode(' ', $catclasses);
            }

            // for those who want to separate resources and activities.
            /*
            $archetype = plugin_supports('mod', $modname, FEATURE_MOD_ARCHETYPE, MOD_ARCHETYPE_OTHER);
            if ($archetype == MOD_CLASS_RESOURCE) {
                $template->resources[] = $plugintpl;
            } else {
                $template->plugins[] = $plugintpl;
            }
            */
            $template->plugins[] = $plugintpl;
        }

        return $OUTPUT->render_from_template('local_userequipment/activitieschooser', $template);
    }
}