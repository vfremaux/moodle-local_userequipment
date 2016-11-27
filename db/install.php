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

require_once($CFG->dirroot.'/local/userequipment/classes/manager.php');

function xmldb_local_userequipment_install() {
    global $DB;

    $profiles = array();

    $profiles[] = array(
        'name' => 'simple',
        'usercanchoose' => true,
        'features' => ue_simple_features(),
    );

    $profiles[] = array(
        'name' => 'standard',
        'usercanchoose' => true,
        'features' => ue_standard_features(),
    );

    $profiles[] = array(
        'name' => 'extended',
        'usercanchoose' => true,
        'features' => ue_extended_features(),
    );

    // Purge any existing template.
    $DB->delete_records('local_userequipment_tpl');
    $DB->delete_records_select('local_userequipment', " userid = 0 AND template > 0 ");

    foreach ($profiles as $profile) {
        $tplrec = new StdClass();
        $tplrec->name = get_string('profile'.$profile['name'], 'local_userequipment');
        $tplrec->description = get_string('profile'.$profile['name'].'_desc', 'local_userequipment');
        $tplrec->descriptionformat = FORMAT_HTML;
        $tplrec->usercanchoose = $profile['usercanchoose'];

        $tplrec->id = $DB->insert_record('local_userequipment_tpl', $tplrec);

        foreach ($profile['features'] as $feature) {
            $uerec = new StdClass();
            $uerec->userid = 0;
            $uerec->template = $tplrec->id;
            $uerec->plugintype = $feature[0];
            $uerec->plugin = $feature[1];
            $uerec->available = 1;
            $DB->insert_record('local_userequipment', $uerec);
        }
    }
}

function ue_simple_features() {
    return array(
            array('mod', 'label'),
            array('mod', 'assign'),
            array('mod', 'file'),
            array('mod', 'url'),
            array('mod', 'mplayer'),
            array('mod', 'quiz'),
            array('mod', 'forum'),
            array('block', 'html'),
            array('block', 'comments'),
            array('block', 'course_summary'),
            array('block', 'news_items'),
            array('format', 'weeks'),
            array('format', 'topics'),
            array('format', 'singleactivity'),
            array('qtype', 'truefalse'),
            array('qtype', 'shortanswer'),
            array('qtype', 'multichoice'),
            array('qtype', 'multianswer'),
            array('qtype', 'match'),
        );
}

function ue_standard_features($excludes = null) {

    $features = array();

    $pluginman = core_plugin_manager::instance();
    $uemanager = \local_userequipment\userequipment_manager::instance();

    $allplugins = $pluginman->get_plugins();

    foreach ($allplugins as $ptype => $plugins) {
        if (!$uemanager->supports_plugin_type($ptype)) {
            continue;
        }

        foreach ($plugins as $name => $plugin) {
            if ($plugin->source == core_plugin_manager::PLUGIN_SOURCE_STANDARD) {

                if (!empty($excludes)) {
                    if (in_array($ptype.'_'.$name, $excludes)) {
                        continue;
                    }
                }

                $features[] = array($ptype, $name);
            }
        }
    }

    return $features;
}

function ue_extended_features() {

    $excludes = array(
        'mod_imscp',
        'mod_survey'
    );

    $features = ue_standard_features($excludes);

    $additionalfeatures = array(
        array('mod', 'certificate'),
        array('mod', 'scheduler'),
        array('mod', 'sharedresources'),
        array('mod', 'customlabel'),
        array('mod', 'magtest'),
        array('mod', 'mask'),
        array('mod', 'realtimequiz'),
        array('mod', 'offlinequizquiz'),
        array('mod', 'hvp'),
        array('mod', 'game'),
        array('mod', 'cognitivefactory'),
        array('mod', 'checklist'),
        array('mod', 'learningtimecheck'),
        array('mod', 'pagemenu'),
        array('mod', 'wims'),

        array('format', 'page'),
        array('format', 'topcoll'),
        array('format', 'grid'),

        array('block', 'activity_publisher'),
        array('block', 'sharedresources'),
        array('block', 'htmlgroupspecific'),
        array('block', 'htmlrolespecific'),
        array('block', 'htmlprofilespecific'),
        array('block', 'auditquiz_results'),
        array('block', 'course_ascendants'),
        array('block', 'course_descendants'),
        array('block', 'chronometer'),
        array('block', 'teams'),
        array('block', 'progression'),
        array('block', 'quiz_progress'),
        array('block', 'page_tracker'),

        array('qtype', 'splitset'),
        array('qtype', 'randomconstrained'),
        array('qtype', 'ddmatch'),
        array('qtype', 'ddwtos'),
        array('qtype', 'ddmarker'),
        array('qtype', 'ddimageortext'),
        array('qtype', 'gapselect'),
    );

    $pluginman = core_plugin_manager::instance();

    // Check the additional features are installed in this moodle.
    foreach ($additionalfeatures as $f) {
        if (!empty($pluginman->get_plugin_info($f[0].'_'.$f[1]))) {
            $features[] = $f;
        }
    }

    return $features;
}