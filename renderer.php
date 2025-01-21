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
require_once($CFG->dirroot.'/local/userequipment/classes/manager.php');

class local_userequipment_renderer extends plugin_renderer_base {

    public function addmembersform($templateid, &$toapplyselector, &$potentialmembersselector) {

        $template = new StdClass;

        $template->formurl = new moodle_url('/local/userequipment/apply.php');
        $template->templateid = $templateid;
        $template->sesskey = sesskey();

        $template->applyselector = $toapplyselector->display(true);
        $template->larrow = $this->output->larrow();
        $template->rarrow = $this->output->rarrow();

        $template->potentialselector = $potentialmembersselector->display(true);
        $template->stricthelpbutton = $this->output->help_icon('strictapplication', 'local_userequipment');

        $template->returl = new moodle_url('/local/userequipment/templates.php');

        return $this->output->render_from_template('local_userequipment/applytomembersform', $template);
    }

    /**
     * Renders all divs that show plugin categories for reloading after a modal change.
     * @param string $plugintype
     * @param string $pluginname
     */
    public function list_plugin_bindings($plugintype, $pluginname) {
        global $DB;
        static $allcats;

        $template = new StdClass;

        if (is_null($allcats)) {
            $allcats = $DB->get_records('local_userequipment_cat', []);
        }

        $sql = "
            SELECT
                cp.*
            FROM
                {local_userequipment_cat_png} cp,
                {local_userequipment_cat} c
            WHERE
                cp.categoryid = c.id AND
                cp.plugintype = ? AND
                cp.pluginname = ?
            ORDER BY
                c.sortorder
        ";

        $pbindings = $DB->get_records_sql($sql, [$plugintype, $pluginname]);
        $template->plugintype = $plugintype;
        $template->pluginname = $pluginname;
        $template->pluginvisiblename = get_string('pluginname', $plugintype.'_'.$pluginname);
        $template->iscategorized = false;
        $template->isajax = true;

        if (!empty($pbindings)) {
            foreach ($pbindings as $bnd) {
                $template->iscategorized = true;
                $catdivtpl = new StdClass;
                $catdivtpl->catid = $bnd->categoryid;
                $catdivtpl->color = $allcats[$bnd->categoryid]->colour;
                $catdivtpl->catname = format_string($allcats[$bnd->categoryid]->name);

                $template->categories[] = $catdivtpl;
            }
        }

        return $this->output->render_from_template('local_userequipment/categories', $template);
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
        global $DB, $OUTPUT, $COURSE, $PAGE, $CFG, $USER;
        static $ueinitialized = false;

        if ($ueinitialized) {
            return '';
        }

        $ueinitialized = true;
        $PAGE->requires->js_call_amd('local_userequipment/modchooser', 'init');
 
        $pluginmanager = core_plugin_manager::instance();
        $activities = $pluginmanager->get_enabled_plugins('mod');
        $sm = get_string_manager();

        $allcats = $DB->get_records('local_userequipment_cat', []);

        $template = new StdClass;
        $template->filters = [];

        // Get them once and cache in a static variable.
        // Filter for the current users. Do not show empty filters to the user
        $sql = "
            SELECT DISTINCT
                uc.*
            FROM
                {local_userequipment_cat} uc,
                {local_userequipment_cat_png} ucp,
                {local_userequipment} ue
            WHERE
                ucp.categoryid = uc.id AND
                ucp.plugintype = ue.plugintype AND
                ucp.pluginname = ue.plugin AND
                ue.userid = ? AND
                ue.available = 1 AND
                ue.plugintype = 'mod'
            ORDER BY
                uc.sortorder
        ";
        $allusercats = $DB->get_records_sql($sql, [$USER->id]);

        foreach ($allusercats as $cat) {
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
                $page = \format_page\course_page::get_current_page();
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

    /**
     * Builds and render the whole self-equipment (or template equipment form).
     * @param object $manager the ue manager instance.
     * @param array $equipment the existing equipment state as an array keyed by plugintypes, and plugin names
     * @return the form HTML string.
     */
    public function render_equipmentform($manager, $equipment, $istemplate = false) : string {
        global $OUTPUT, $USER;

        if (is_null($equipment)) {
            $equipment = [];
        }

        $context = context_user::instance($USER->id);
        $systemcontext = context_system::instance();

        $supportedtypes = $manager->get_supported_types();

        $template = new StdClass;

        $template->formtabs = [];
        $template->istemplate = $istemplate;
        $template->catmodal = $this->output->render_from_template('local_userequipment/editcatpng', []);
        $template->modchooser = $this->render_modchooser();
        $template->cantune = false;

        if (!$template->istemplate) {
            $template->templateid = 0;
            $template->userid = $USER->id;
            if ($manager->can_tune()) {
                $template->cantune = true;
            }
        } else {
            $template->templateid = optional_param('templateid', 0, PARAM_INT);
            $template->userid = 0;
        }

        $firsttab = true;
        foreach ($supportedtypes as $t) {
            $tabtpl = new StdClass;
            if ($template->istemplate) {
                if ($firsttab) {
                    $tabtpl->isactive = true;
                }
            }
            $tabtpl->plugintype = $t;
            $tabtpl->plugintypestr = $manager->get_string_for_type($t);
            $template->formtabs[] = $tabtpl;
            $firsttab = false;
        }

        $template->canedit = has_capability('moodle/site:config', $systemcontext);
        $template->formpanels = [];

        // Profiles panel tpl.
        $paneltpl = new StdClass;
        $paneltpl->isselfequipable = has_capability('local/userequipment:selfequip', $context); 
        $profiles = $manager->get_self_equipable_templates();
        if (!empty($profiles)) {
            foreach ($profiles as $pf) {
                $profiletpl = new StdClass;
                $profiletpl->id = $pf->id;
                $profiletpl->description = $pf->description;
                $profiletpl->alt = get_string('applytemplate', 'local_userequipment', $pf->name);
                $paneltpl->profiles[] = $profiletpl;
            }
        }
        $template->application[] = $paneltpl;

        $pmanager = core_plugin_manager::instance();
        $sm = get_string_manager();
        $manager = \local_userequipment\userequipment_manager::instance();

        // Get panels for types.
        $firsttab = true;
        $template->pluginscount = 0;

        if (!empty($equipment)) {
            $template->cancleanup = true;
        }

        foreach ($supportedtypes as $t) {
            $paneltpl = new StdClass;

            $paneltpl->ishidden = true;
            if ($template->istemplate) {
                $template->cantune = true;
                if ($firsttab) {
                    $paneltpl->ishidden = false;
                }
            }
            $firsttab = false; // Definitely in the loop.

            $paneltpl->plugintype = $t;
            $paneltpl->paneltitle = $manager->get_string_for_type($t);

            $plugininfoclass = '\\core\\plugininfo\\'.$t;
            $plugininfofunc = $plugininfoclass.'::get_enabled_plugins';

            // Fetch the set of plugins. 
            $plugins = $pmanager->get_plugins_of_type($t);
            $enabledplugins = $plugininfofunc($t);
            foreach ($plugins as $pname => $p) {

                if (!array_key_exists($pname, $enabledplugins)) {
                    continue;
                }

                $plugintpl = new StdClass;
                $plugintpl->pluginname = $p->name;
                $plugintpl->pluginvisiblename = get_string("pluginname", "{$t}_{$p->name}");
                if (array_key_exists($t.'_'.$plugintpl->pluginname, $equipment)) {
                    $plugintpl->checkedstatus = ($equipment[$t.'_'.$plugintpl->pluginname]) ? 'checked="checked"' : '' ;
                    if ($plugintpl->checkedstatus) {
                        $template->pluginscount++;
                    }
                }

                $plugintpl->shortdescription = $manager->get_suitable_description($t, $plugintpl->pluginname);
                $plugintpl->categorization = $this->render_plugin_categorization($t, $plugintpl->pluginname);

                $paneltpl->plugins[] = $plugintpl;
            }

            // Reorder plugins by visible name.
            usort($paneltpl->plugins, 'ue_sort_by_name');

            $template->formpanels[] = $paneltpl;
        }

        return $this->output->render_from_template('local_userequipment/equipmentform', $template);
    }

    /**
     * Renders the plugin categorisation
     * @param $plugintype
     * @param $pluginname
     */
    public function render_plugin_categorization($plugintype, $pluginname) {
        global $DB;
        static $allcats;

        $template = new StdClass;
        $template->plugintype = $plugintype;
        $template->pluginname = $pluginname;

        if (is_null($allcats)) {
            $allcats = $DB->get_records('local_userequipment_cat', []);
        }

        $plugincats = $DB->get_records('local_userequipment_cat_png', ['plugintype' => $plugintype, 'pluginname' => $pluginname]);

        if (!empty($plugincats)) {
            $template->iscategorized = true;
            foreach ($plugincats as $cat) {
                $catdivtpl = new StdClass;
                $catdivtpl->catid = $allcats[$cat->categoryid]->id;
                $catdivtpl->catname = $allcats[$cat->categoryid]->name;
                $catdivtpl->color = $allcats[$cat->categoryid]->colour;
                $template->categories[] = $catdivtpl;
            }
        }

        return $this->output->render_from_template('local_userequipment/categories', $template);
    }
}

/**
 * Start with checked ones. Then order by name.
 */
function ue_sort_by_name($a, $b) {
    global $CFG;

    if (preg_match('/^fr/', $CFG->lang)) {
        setlocale(LC_COLLATE, 'fr_FR');
    }

    $chka = (!empty($a->checkedstatus) ? 0 : 1).' '.$a->pluginvisiblename;
    $chkb = (!empty($b->checkedstatus) ? 0 : 1).' '.$b->pluginvisiblename;

    if ($chka > $chkb) {
        return 1;
    } else if ($chka < $chkb) {
        return -1;
    }
    return 0;
}