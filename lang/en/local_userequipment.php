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
 * This screen can map courses to quesiton categories used in the tests so
 * that a list of self enrolment could be proposed in HTML results
 *
 * @copyright 2015 Valery Fremaux (valery.fremaux@gmail.com)
 * @package local_userequipment
 * @category local
 */

$string['userequipment:override'] = 'Overrides the equipment limitations';
$string['userequipment:selfequip'] = 'Can configure his own equipment';
$string['userequipment:equip'] = 'Can configure equipment of other users';

$string['addtemplate'] = 'Add new template';
$string['allusers'] = 'All users enabled';
$string['applystrict']  = 'Apply strict template';
$string['applytemplate'] = 'Apply template : "{$a}"';
$string['applytemplatebtn'] = 'Apply template';
$string['applytoselection'] = 'Apply to selected users';
$string['associatedsystemrole'] = 'Associated system role';
$string['cancel'] = 'Cancel';
$string['capabilitycontrol'] = 'Disable by capability';
$string['cleanup'] = 'Delete all my equipment marks. You will have access to all features.';
$string['configaskuserstoprofile'] = 'Ask users to self profile';
$string['configaskuserstoprofile_desc'] = 'If enabled, all users with edition capability will be asked to choose an application profile when first login.';
$string['configautosetupnewusers'] = 'Auto setup new users';
$string['configautosetupnewusers_desc'] = 'If enabled, all new created users will be applied the default profile';
$string['configdisablecontrol'] = 'Disable control source';
$string['configdisablecontrolvalue'] = 'Disable control value';
$string['default'] = ' (default)';
$string['disabledforuser'] = 'Self equipment has been disabled for you.';
$string['enableuserequipment'] = 'Enable per user feature equipment';
$string['equipme'] = 'Manage my equipment';
$string['equipmentcleaned'] = 'Equipment marks have been successfully deleted';
$string['isdefault'] = 'Is default profile';
$string['managetemplates'] = 'manage templates facility';
$string['marksinfo'] = 'You have {$a} profile marks in your equipment profile.';
$string['none'] = 'No role';
$string['notemplates'] = 'No templates';
$string['other'] = 'Other';
$string['pluginname'] = 'User equipment';
$string['plugins'] = 'Plugins';
$string['potentialmembers'] = 'Potential users';
$string['profileextended'] = 'Extended';
$string['profilefieldcontrol'] = 'disable by profile field';
$string['profilesimple'] = 'Elementary';
$string['profilestandard'] = 'Standard only';
$string['releasenever'] = 'Never release';
$string['releaseoncleanup'] = 'Release on equipment cleanup';
$string['releaseonnewprofile'] = 'Release when applying new profile';
$string['releaseroleon'] = 'Release associated role on';
$string['target'] = 'Target Users';
$string['template'] = 'Template';
$string['templateapplied'] = 'The template has been applied to your profile';
$string['templatename'] = 'Template name';
$string['templates'] = 'Templates';
$string['usercanchoose'] = 'User choice';
$string['userequipment'] = 'User feature self-equipment';
$string['usersupdated'] = 'Users had equipment profile updated';

$string['isdefault_desc'] = 'If this user equipement profile is the default profile, then any new user created will be setup with this profile.
Checking this checkbox will superseede any other default choice.';

$string['enableuserequipment_desc'] = 'If enabled, each user (editing users) can self-equipe their profile with available features,
or cleanup their GUIs from unused or unnecessary items.';

$string['applystrict_desc'] = 'Using strict application will erase all previous activation in the targetted user\'s profiles.';

$string['configdisablecontrol_desc'] = 'Allows selecting the way some users may be equipement disabled. you can choose using a specific capability
and manage equipment availability by roles, or drive it using profile fields.';

$string['configdisablecontrolvalue_desc'] = 'Set value for control. Value may be a capability name or a custom user control field name that has to contain
a non empty value. you may also defined the value as a fieldname=value expression, in which case the field will have to contain the exact value to
disable equipment.';

$string['ueinfo_tpl'] = '
<p>Moodle is becoming a very complete and rich application. Probably administrators and other users do use different features you really need. User Feature
Self Equipement principle will help you to tune the Moodle menus and features choice to better fit your needs and avoid loosing time in finding things
in blocks, activities, or other choice menus. </p>

<h4>How it works ?</h4>

<p>When User Feature Self-Equipment is enabled in your Moodle, each user can choose to activate or not some plugins. As we are working at user level, this
feature cannot decently check all your roles in all the contexts you are involved in. Thus the equipement forms may show you features you have never seen
or would never see in any Moodle your are teaching in. We tried to explicit the descriptions so you can figure out if this feature suits to your role or not.</p>

<p>Browse in the other form sections to discover the list of features, blocks, modules, quetion types, or other module options that you might be using, reminding that
the feature accessibility might depend on other factors, such as global settings, roles assignations, role profiles and so on.</p>

<p>Removing all profile equipment choices will let you using a full featured moodle again.</p>

<h4>User Equipement Profiles</h4>
<p>The administrators may have applied a preset profile to you. In all case you can add or remove features from this starting point. If you feel really underequiped,
contact your administrators to be applied a richer profile, or to get some information about real availability of this or that feature.</p>

';

$string['ueselfinfo_tpl'] = '
<h4>My Equipment Profiles</h4>

<p>In some Moodle you may apply some profiles for yourself directly without asking an administrator do do it for you. Click on one of the following buttons to change
your profile. This will reset all your equipement and apply the profile equipement. You may toggle on additional features individually later if the profile do not
provide them.</p>
';

$string['profilesimple_desc'] = 'Elementary profile that accesses to the very basic features such as forum assign, publishing files,
url and video and making quiz.';

$string['profilestandard_desc'] = 'A profile that only activates standard plugins, making course more compatbile with any Moodle
installation of same version level.';

$string['profileextended_desc'] = 'An extended profile activating valuable pedagogic additions.';

require_once('plugin_descriptions.php');