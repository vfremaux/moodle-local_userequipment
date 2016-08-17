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

$string['pluginname'] = 'User equipment';
$string['enableuserequipment'] = 'Enable per user feature equipment';
$string['enableuserequipment_desc'] = 'If enabled, each user (editing users) can self-equipe their profile with available features, or cleanup their GUIs from unused or unnecessary items.';
$string['equipme'] = 'Manage my equipment';
$string['cleanup'] = 'Delete all my equipment marks';
$string['equipmentcleaned'] = 'Equipment marks have been successfully deleted';

$string['cancel'] = 'Cancel';
$string['target'] = 'Target Users';
$string['other'] = 'Other';
$string['potentialmembers'] = 'Potential users';
$string['plugins'] = 'Plugins';
$string['template'] = 'Template';
$string['templatename'] = 'Template name';
$string['templates'] = 'Templates';
$string['notemplates'] = 'No templates';
$string['addtemplate'] = 'Add new template';
$string['managetemplates'] = 'manage templates facility';
$string['applytemplate'] = 'Apply template: {$a}';
$string['applytemplatebtn'] = 'Apply template';
$string['applystrict']  = 'Apply strict template';
$string['applystrict_desc'] = 'Using strict application will erase all previous activation in the targetted user\'s profiles.';
$string['applytoselection'] = 'Apply to selected users';
$string['usersupdated'] = 'Users had equipment profile updated';

$string['userequipment'] = 'User feature self-equipment';
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
<p>the administrators may have applied a preset profile to you. In all case you can add or remove features from this starting point. If you feel really underequiped,
contact your administrators to be applied a richer profile, or to get some information about real availability of this or that feature.</p>

';

include_once('plugin_descriptions.php');