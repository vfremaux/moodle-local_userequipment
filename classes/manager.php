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
 * Equipment manager
 *
 * @package     local_userequipment
 * @author      Valery Fremaux <valery.fremaux@gmail.com>
 * @copyright   2016 Valery Fremaux (valery.fremaux@gmail.com)
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_userequipment;

use StdClass;
use context_system;
use context_course;

/**
 * Manager class.
 */
class userequipment_manager {

    /** @var single instance **/
    protected static $instance;

    /**
     * Singleton pattern.
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new userequipment_manager();
        }

        return self::$instance;
    }

    /**
     * Forces passing through the singleton instanciator.
     */
    private function __construct() {
    }

    /**
     * Tells if plugin type is supported.
     * @param string $ptype
     */
    public function supports_plugin_type($ptype) {
        return in_array($ptype, array('mod', 'block', 'qtype', 'format'));
    }

    /**
     * Init default values.
     */
    public static function init_defaults() {
        return array(
         'block_activity_modules' => 1,
         'block_activity_publisher' => 0,
         'block_admin' => 1,
         'block_admin_bookmarks' => 1,
         'block_admin_tree' => 1,
         'block_blog_menu' => 1,
         'block_blog_tags' => 1,
         'block_calendar_month' => 1,
         'block_calendar_upcoming' => 1,
         'block_checklist' => 0,
         'block_course_ascendants' => 0,
         'block_course_list' => 1,
         'block_course_summary' => 1,
         'block_dashboard' => 0,
         'block_glossary_random' => 1,
         'block_group_network' => 1,
         'block_html' => 1,
         'block_loancalc' => 0,
         'block_login' => 1,
         'block_mentees' => 1,
         'block_messages' => 1,
         'block_metagenerator' => 0,
         'block_mnet_hosts' => 0,
         'block_news_items' => 1,
         'block_online_users' => 1,
         'block_participants' => 1,
         'block_pix_panel' => 0,
         'block_profilespecifichtml' => 0,
         'block_publishflow' => 0,
         'block_quiz_results' => 1,
         'block_recent_activity' => 1,
         'block_rss_client' => 1,
         'block_search' => 0,
         'block_search_forums' => 1,
         'block_section_links' => 0,
         'block_sharedresources' => 0,
         'block_site_main_menu' => 1,
         'block_social_activities' => 1,
         'block_tag_flickr' => 1,
         'block_tag_youtube' => 1,
         'block_tags' => 1,
         'block_user_mnet_hosts' => 1,
         'block_useradmin' => 1,
         'block_userfiles' => 0,
         'block_vmoodle' => 0,
         'mod_assign' => 1,
         'mod_assignment' => 1,
         'mod_chat' => 1,
         'mod_checklist' => 0,
         'mod_choice' => 1,
         'mod_data' => 1,
         'mod_extmedia' => 1,
         'mod_feedback' => 1,
         'mod_flashcard' => 1,
         'mod_flv' => 1,
         'mod_forum' => 1,
         'mod_glossary' => 1,
         'mod_hotpot' => 0,
         'mod_journal' => 0,
         'mod_label' => 1,
         'mod_lams' => 0,
         'mod_lesson' => 0,
         'mod_lightboxgallery' => 0,
         'mod_magtest' => 0,
         'mod_mplayer' => 0,
         'mod_quiz' => 1,
         'mod_referentiel' => 0,
         'mod_resource' => 1,
         'mod_scheduler' => 0,
         'mod_scorm' => 0,
         'mod_sharedresource' => 0,
         'mod_survey' => 0,
         'mod_tracker' => 0,
         'mod_wiki' => 1,
         'mod_workshop' => 0,
         'rtype_combidirectory' => 1,
         'rtype_combifile' => 1,
         'rtype_directory' => 0,
         'rtype_file' => 0,
         'rtype_html' => 1,
         'rtype_ims' => 0,
         'rtype_repository' => 0,
         'rtype_text' => 0,
         'rtype_userdirectory' => 0,
         'rtype_userfile' => 0,
         'qtype_calculated' => 1,
         'qtype_ddmatch' => 1,
         'qtype_description' => 1,
         'qtype_dragdrop' => 1,
         'qtype_essay' => 1,
         'qtype_gapfill' => 1,
         'qtype_imagetarget' => 1,
         'qtype_match' => 1,
         'qtype_missingtype' => 1,
         'qtype_multianswer' => 1,
         'qtype_multichoice' => 1,
         'qtype_shortanswer' => 1,
         'qtype_numerical' => 1,
         'qtype_order' => 1,
         'qtype_random' => 1,
         'qtype_randomsamatch' => 1,
         'qtype_splitset' => 1,
         'qtype_truefalse' => 1,
         'assigmenttype_offline' => 1,
         'assigmenttype_online' => 1,
         'assigmenttype_onlineaudio' => 1,
         'assigmenttype_upload' => 1,
         'assigmenttype_uploadsingle' => 1,
         'format_topics' => 1,
         'format_topcols' => 1,
         'format_grid' => 1,
         'format_weeks' => 1,
         'format_weekscss' => 0,
         'format_page' => 1,
         'format_flexipage' => 1,
         'format_scorm' => 0,
         'format_lams' => 0,
         'format_social' => 0);
    }

    /**
     * Applies an equipment template to a user. If strict, will replace existing equipment
     * with the template deleting eventual previous allowance.
     * @param int $templateid the template id.
     * @param int $userid the current user id.
     * @param bool $strict if strict, a template subroggates to any changes the user has precedently done on his own 
     * equipment profile.
     * @return void
     */
    public function apply_template($templateid, $userid, $strict = false) {
        global $DB;

        if (!$DB->record_exists('user', array('id' => $userid))) {
            return;
        }

        $template = $DB->get_record('local_userequipment_tpl', array('id' => $templateid));

        if ($strict) {
            $DB->delete_records('local_userequipment', array('userid' => $userid));
        }

        if ($templatedefs = $DB->get_records('local_userequipment', array('template' => $templateid))) {
            foreach ($templatedefs as $td) {
                $params = array('userid' => $userid, 'plugintype' => $td->plugintype, 'plugin' => $td->plugin);
                if (!$oldrec = $DB->get_record('local_userequipment', $params)) {
                    $def = new StdClass();
                    $def->userid = $userid;
                    $def->plugintype = $td->plugintype;
                    $def->plugin = $td->plugin;
                    $def->available = true;
                    $def->timemodified = time();
                    $def->template = $templateid; // Mark the last applied template.
                    $DB->insert_record('local_userequipment', $def);
                } else {
                    $oldrec->timemodified = time();
                    $oldrec->template = $templateid;
                    $DB->update_record('local_userequipment', $oldrec);
                }
            }
        }

        $this->unmark_cleaned($userid);

        if ($template->associatedsystemrole &&
            $DB->record_exists('role', array('id' => $template->associatedsystemrole))) {
            $context = \context_system::instance();
            role_assign($template->associatedsystemrole, $userid, $context->id);
        }
    }

    /**
     * Adds or updates a template in DB
     * @param object $data data from form.
     */
    public function add_update_template($data) {
        global $DB, $CFG;

        $context = \context_system::instance();

        $options = array('trusttext' => true,
                         'subdirs' => false,
                         'maxfiles' => 100,
                         'maxbytes' => $CFG->maxbytes,
                         'context' => $context);

        $data = file_postupdate_standard_editor($data, 'description', $options, $context,
                                                'local_userequipment', 'templatedesc', $data->template);

        if ($data->isdefault) {
            // Remove all other defaults.
            $DB->set_field('local_userequipment_tpl', 'isdefault', 0, array());
        }

        if ($template = $DB->get_record('local_userequipment_tpl', array('id' => $data->template))) {
            $template->name = $data->name;
            $template->description = $data->description;
            $template->descriptionformat = $data->descriptionformat;
            $template->usercanchoose = $data->usercanchoose;
            $template->isdefault = $data->isdefault;
            $template->associatedsystemrole = $data->associatedsystemrole;
            $template->releaseroleon = $data->releaseroleon;
            $DB->update_record('local_userequipment_tpl', $template);
        } else {
            $template = new StdClass();
            $template->name = $data->name;
            $template->description = $data->description;
            $template->descriptionformat = $data->descriptionformat;
            $template->usercanchoose = $data->usercanchoose;
            $template->isdefault = $data->isdefault;
            $template->associatedsystemrole = $data->associatedsystemrole;
            $template->releaseroleon = $data->releaseroleon;
            $template->id = $data->template = $DB->insert_record('local_userequipment_tpl', $template);
        }
    }

    /**
     * Adds or updates a template in DB for a user
     * @param object $data data from form.
     * @param object $userid the concerned userid.
     */
    public function add_update_user($data, $userid) {
        global $DB;

        $DB->delete_records('local_userequipment', array('userid' => $userid));
        foreach ($data as $pgn => $val) {
            $parts = explode('_', $pgn);
            $type = array_shift($parts);
            $plugin = implode('_', $parts);

            $eqrec = new StdClass();
            $eqrec->plugintype = $type;
            $eqrec->plugin = $plugin;
            $eqrec->userid = $userid;
            $eqrec->template = $data->template ?? 0;
            $eqrec->available = 1;
            $eqrec->timemodified = time();
            $DB->insert_record('local_userequipment', $eqrec);
        }
    }

    /**
     * Get the current equipment of a user.
     * @return an array of boolean marks keyed by FQPN (plugintype_pluginname)
     * @param object $userorid the user as object or id. If given, retrieves all equipment record whatever the template being marked.
     * @param bool $template if > 0, and user is not given we, are rather getting a template equipment definition.
     *
     * if both $template AND $user not given, will return the current's user equipment.
     */
    public function fetch_equipement($userorid = null, $template = null) {
        global $USER, $DB;

        if (is_null($userorid)) {
            $userid = 0;
        } else if (is_object($userorid)) {
            $userid = $userorid->id;
        } else {
            $userid = $userorid;
        }

        if ($template && empty($userid)) {
            $params = array('template' => $template, 'userid' => 0);
        } else {
            if (!empty($userid)) {
                $params = array('userid' => $userid);
            } else {
                $params = array('userid' => $USER->id);
            }
        }

        if ($equiprecs = $DB->get_records('local_userequipment', $params)) {
            $equipement = array();
            foreach ($equiprecs as $eq) {
                $equipement[$eq->plugintype.'_'.$eq->plugin] = $eq->available;
            }
            return $equipement;
        }

        return array();
    }

    /**
     * Get the equipment data of the default profile if defined. Empty set elsewhere.
     */
    public function fetch_default_equipement() {
        global $DB;

        $data = [];

        $default = $DB->get_record('local_userequipment_tpl', ['isdefault' => 1]);
        if (!empty($default)) {
            $recs = $DB->get_records('local_userequipment', ['template' => $default->id]);
            if (!empty($recs)) {
                foreach ($recs as $rec) {
                    $data[$rec->plugintype.'_'.$rec->plugin] = $rec->available;
                }
            }
        }

        return $data;
    }

    /**
     * Deletes a template with its settings and equipment marks.
     * @param int $templateid the template's id.
     */
    public function delete_template_equipment($templateid) {
        global $DB;

        $DB->delete_records('local_userequipment', array('userid' => 0, 'template' => $templateid));
        $DB->delete_records('local_userequipment_tpl', array('id' => $templateid));
    }

    /**
     * Clears equipment of a single user.
     * @param object $user
     */
    public function delete_user_equipment($user) {
        global $DB;

        $DB->delete_records('local_userequipment', array('userid' => $user->id));
    }

    /**
     * Checks if a plugin of some plugintype is in user's equipment.
     * @param string $plugintype the plugin type
     * @param string $plugin the simple plugin name
     * @param int $userid
     */
    public function check_user_equipment($plugintype, $plugin, $userid = 0) {
        global $USER;
        static $checkcache;
        global $DB;

        if (!$this->is_enabled_for_user($USER)) {
            // Is equipment engine enabled for this user ? If not do not filter anything.
            return true;
        }

        if (empty($userid)) {
            $userid = $USER->id;
        }

        if (!isset($checkcache)) {
            $checkcache = array();
        }

        if (!$DB->record_exists('local_userequipment', array('userid' => $userid, 'available' => 1))) {
            return true;
        }

        if (!in_array($plugintype.'_'.$plugin, array_keys($checkcache))) {
            $params = array('plugintype' => $plugintype, 'plugin' => $plugin, 'userid' => $userid);
            if ($check = $DB->get_record('local_userequipment', $params)) {
                $checkcache[$plugintype.'_'.$plugin] = $check->available;
            } else {
                $checkcache[$plugintype.'_'.$plugin] = 0;
            }
        }

        return $checkcache[$plugintype.'_'.$plugin];
    }

    /**
     * Checks if a user is allowed to fine tune his equipement
     * @param object $user
     */
    public function can_tune($user = null) {
        global $USER;

        $config = get_config('local_userequipment');

        if (is_null($user)) {
            $user = $USER;
        }

        if (!empty($config->allow_self_tuning)) {
            return true;
        }

        if (has_capability('local/userequipment:selftune', context_system::instance())) {
            return true;
        }

        return false;
    }

    /**
     * checks if user can use equipement interface
     */
    public function is_enabled_for_user($user) {
        global $COURSE;
        global $DB;

        $config = get_config('local_userequipment');

        if (!$config->enabled) {
            // Everything allowed.
            return true;
        }

        switch ($config->disable_control ?? 0) {
            case 0 :
                return true;
                break;
            case 'capability':
                if (empty($config->disable_control_value)) {
                    return true;
                }
                $context = context_course::instance($COURSE->id);
                if (has_capability($config->disable_control_value, $context, $user->id)) {
                    return true;
                }
                break;
            case 'profilefield':
                $field = $DB->get_record('user_profile_field', array('shortname' => $config->disable_control_value));
                $value = $DB->get_field('user_profile_data', 'data', array('fieldid' => $field->id, 'userid' => $user->id));
                if ($value) {
                    return true;
                }
                break;
        }
        return false;
    }

    /**
     * Marks a user in preference as not wanting to be applied the default profile any more. User
     * has cleaned his equipment profile to make his own choice.
     * @param mixed $userorid
     */
    public function mark_cleaned($userorid) {
        global $DB;

        if (is_object($userorid)) {
            $userid = $userorid->id;
        } else {
            $userid = $userorid;
        }

        // Mark in preference we DO NOT want equipment restrictions any more (no defaults reloading).
        if (!$oldrec = $DB->get_record('user_preferences', array('userid' => $userid, 'name' => 'noequipment'))) {
            $prefrec = new Stdclass();
            $prefrec->userid = $userid;
            $prefrec->name = 'noequipment';
            $prefrec->value = 1;
            $DB->insert_record('user_preferences', $prefrec);
        } else {
            $oldrec->value = 1;
            $DB->update_record('user_preferences', $oldrec);
        }
    }

    /**
     * Marks a user in preference as not wanting to be applied the default profile any more. User
     * has cleaned his equipment profile to make his own choice.
     * @param mixed $userorid
     */
    public function unmark_cleaned($userorid) {
        global $DB;

        if (is_object($userorid)) {
            $userid = $userorid->id;
        } else {
            $userid = $userorid;
        }

        // Mark in preference we DO NOT want equipment restrictions any more (no defaults reloading).
        $DB->delete_records('user_preferences', ['userid' => $userid, 'name' => 'noequipment']);
    }

    /**
     * Checks if a user has asked for cleaning his profile once, thus requiring no default
     * equipement is required.
     * @param object $userorid
     */
    public function is_marked_cleaned($userorid) {
        global $DB;

        if (is_object($userorid)) {
            $userid = $userorid->id;
        } else {
            $userid = $userorid;
        }

        return $DB->record_exists('user_preferences', array('userid' => $userid, 'name' => 'noequipment'));
    }

    public function remove_all_roles_on_cleanup($userid) {
    }

    /**
     * returns the supported equipmenet types.
     */
    public function get_supported_types() : array {
        return ['block', 'mod', 'format', 'qtype'];
    }

    /**
     * returns the supported equipmenet types.
     * @param string $t
     */
    public function get_string_for_type(string $t) : string {
        $stringkeys = [
            'block' => 'blocks',
            'mod' => 'modules',
            'format' => 'courseformats',
            'qtype' => 'questiontypes'
        ];
        return get_string($stringkeys[$t], 'local_userequipment');
    }

    /**
     * A "as clever as possible" function to get suitable description printable to the user.
     * Description sources are (in order of preference) : 
     * 1. Admin defined plugin description statements in local_userequipment backoffice.
     * 2. hard strings in local_userequipment strings.
     * 3. local overriden hard strings in plugoin scope strings.
     * 4. available core strings for plugin.
     */
    public function get_suitable_description($plugintype, $pluginname) {
        global $DB;

        // Admin defined overrides in local_userequipment plugin.
        $desc = $DB->get_record('local_userequipment_png', ['plugintype' => $plugintype, 'pluginname' => $pluginname]);
        if ($desc) {
            return format_text($desc->plugindescription, $desc->plugindescriptionformat);
        }

        // Desc hard strings in local_userequipment string files.
        $sm = get_string_manager();
        if ($sm->string_exists('plugdesc_'.$plugintype.'_'.$pluginname, 'local_userequipment')) {
            return get_string('plugdesc_'.$plugintype.'_'.$pluginname, 'local_userequipment');
        } 

        // Desc hard strings in plugin string files.
        $fqdn = $plugintype.'_'.$pluginname;
        if ($sm->string_exists('plugdesc_'.$plugintype.'_'.$pluginname, $fqdn)) {
            return get_string('plugdesc_'.$plugintype.'_'.$pluginname, $fqdn);
        }

        // This is a last chance special case for plugins who do NOT comply with full frankenstyle name.
        if ($plugintype == 'mod') {
            $fqdn = $pluginname;
            if ($sm->string_exists('plugdesc_'.$plugintype.'_'.$pluginname, $fqdn)) {
                return get_string('plugdesc_'.$plugintype.'_'.$pluginname, $fqdn);
            }
        }

        // Now we just have to rely on core/standard description strings if exists.
        $fqdn = $plugintype.'_'.$pluginname;
        if ($sm->string_exists('plugdesc_'.$plugintype.'_'.$pluginname, $fqdn)) {
            if ($plugintype == 'mod') {
                return get_string('modulename_help', $fqdn);
            } else {
                return get_string('modulename_help', $fqdn);
            }
        }

        // This is a last chance special case for plugins who do NOT comply with full frankenstyle name.
        if ($plugintype == 'mod') {
            $fqdn = $pluginname;
            if ($sm->string_exists('modulename_help', $fqdn)) {
                return get_string('modulename_help', $fqdn);
            }
        }

        // No way ! there is really nothing nowhere :-D
        return '';
    }

    /**
     *
     */
    public function get_self_equipable_templates() {
        global $DB;

        $selfusabletemplates = $DB->get_records('local_userequipment_tpl', array('usercanchoose' => true));
        return $selfusabletemplates;
    }

    /**
     * Get potential users that can apply to a given template.
     * Unprogrammed template with no rules will always return an empty set.
     * @param object $template
     * @return an array of tiny user records.
     */
    public function get_potential_users($template) {
        global $DB;

        if (!empty($template->applycoursecompletion) && !empty($template->completedcourse)) {
            $sql = "
                SELECT DISTINCT
                    u.id,
                    u.username,
                    u.firstname,
                    u.lastname,
                    u.email
                FROM
                    course_completions cc,
                    user u
                WHERE
                    u.id = cc.userid AND
                    cc.course = ? AND 
                    cc.timecompleted IS NOT NULL AND
                    cc.timecompleted > 0 AND
                    u.deleted = 0 AND
                    u.suspended = 0
            ";
            $completedusers = $DB->get_records_sql($sql, [$template->completedcourse]);
        }

        if (!empty($template->applyonprofile)) {
            // This uses a profile match expression.
            // First approach : simple parser on user and profile_field
            // Syntaxes : user:<field>:<value> or user:field:"<quoted value>"
            // Syntaxes : profile_field:<field>:<value> or profile_field:field:"<quoted value>"
            // Syntaxes : profile_field:<field>:<value> or profile_field:field:"<quoted value>"
            $profileusers = [];
        }

        if (!empty($template->applywhencohortmember)) {
            // Cohort id should match.
            $sql = "
                SELECT DISTINCT
                    u.id,
                    u.username,
                    u.firstname,
                    u.lastname,
                    u.email
                FROM
                    {cohort_members} cm,
                    {user} u
                WHERE
                    u.id = cm.userid AND
                    cm.cohortid = ? AND
                    u.deleted = 0 AND
                    u.suspended = 0
            ";
            $cohortmembers = $DB->get_records_sql($sql, [$template->applywhencohortmember]);
        }

        $targetusers = [];
        if (!empty($completedusers)) {
            foreach ($completedusers as $u) {
                $targetusers[$u->id] = $u;
            }
        }

        if (!empty($profileusers)) {
            foreach ($profileusers as $u) {
                // Admits it rewrites someone we already have.
                $targetusers[$u->id] = $u;
            }
        }

        if (!empty($cohortmembers)) {
            foreach ($cohortmembers as $u) {
                // Admits it rewrites someone we already have.
                $targetusers[$u->id] = $u;
            }
        }

        return $targetusers;
    }
}
