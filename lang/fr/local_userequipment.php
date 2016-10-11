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

$string['userequipment:override'] = 'Surchage les limites d\'équipement';
$string['userequipment:selfequip'] = 'Peut configurer son propre équipement';
$string['userequipment:equip'] = 'Peut configurer l\'équipement d\'autres utilisateurs';

$string['pluginname'] = 'Equipement de l\'utilisateur';
$string['enableuserequipment'] = 'Activer le plan d\'équipement individuel des utilisateurs';
$string['enableuserequipment_desc'] = 'Si activé, les utilisateurs (editeurs) peuvent choisir les fonctions qu\'ils mobilisent, et nettoyer les interfaces de tout ce qui ne leur sert pas dans leur processus d\'édition.';
$string['equipme'] = 'Gérer mes équipements';

$string['allusers'] = 'Tous les utilisateurs activés';
$string['capabilitycontrol'] = 'Désactiver par capacité';
$string['profilefieldcontrol'] = 'Désactiver par champ de profil';
$string['configdisablecontrol'] = 'Source de désactivation';
$string['configdisablecontrol_desc'] = 'Permet de choisir comment l\'auto-équipement de certains utilisateurs est désactivé';
$string['configdisablecontrolvalue'] = 'Valeur de contrôle';
$string['configdisablecontrolvalue_desc'] = 'Définit la valeur de contrôle. Celapeut être le nom d\'une capacité ou le code d\'un champ de profil.';
$string['cancel'] = 'Annuler';
$string['disabledforuser'] = 'L\'équipement utilisateur a été désactivé pour votre catégorie d\'utilisateur.';
$string['plugins'] = 'Plugins';
$string['other'] = 'Autres';
$string['template'] = 'Profil';
$string['templatename'] = 'Nom du profil d\'équipement';
$string['templates'] = 'Profils d\'équipement';
$string['notemplates'] = 'Aucun profil';
$string['addtemplate'] = 'Ajouter un nouveau profil';
$string['managetemplates'] = 'gestionnaire de profils d\'équipement';
$string['applytemplate'] = 'Appliquer le profil : {$a}';
$string['applytemplatebtn'] = 'Appliquer le profil';
$string['applystrict'] = 'Profil strict';
$string['applystrict_desc'] = 'Avec cette option activée, vous supprimez toutes les autorisations non prévues dans ce profil.';
$string['applytoselection'] = 'Appliquer à la sélection';
$string['usersupdated'] = 'Les profils des utilisateurs sélectionnés ont été mis à jour';
$string['cleanup'] = 'Supprimer mes marques d\'équipement';
$string['equipmentcleaned'] = 'Les marques d\'équipement ont été supprimées.';

$string['userequipment'] = 'Equipement de l\'utilisateur';
$string['ueinfo_tpl'] = '
<p>Moodle est une application de plus en plus riche et complète. Il est probable que les administrateurs et les autres usagers de Moodle utilisent des fonctions
dont vous ne vous servez pas du tout (ou aps encore). Le principe de l\'équipement utilisateur vous permet de sélectionner et de nettoyer les menus
 de choix de fonctions de Moodle afin de vous donner une vision plus claire de vos interfaces de gestion des cours.</p>

<h4>Comment cela fonctionne ?</h4>

<p>Lorsque l\'équipement utilisateur est actif dans votre Moodle, chaque utiisateur peut contrôle par lui-même s\'il actve ou non les plugins installés dans la plate-forme. 
Comme nous gérons ces activations au niveau "Utilisateur", il n\'est pas raisonnable d\'explorer globalement tous les roles et tous les droits dont vous disposez dans
tous les contextes de travail. De ce fait, les listes d\'équipement pourraient vous mentionner des fonctionnalités ou plugins dont
vous n\'avez jamais entendu parler et qui de toutes façons vous seraient innaccessibles au regard de vos rôles. 
Nous avons essayé d\'être suffisament explicites pour que vous puissiez rapidement vous repérer dans les propositions et voir si elles correspondent
à votre usage.</p>

<p>Naviguez dans les autres sections du formulaire pour examiner les listes de blocs, modules, et autres types de plugins qui pourraient constituer votre équipement personnel,
tout en gardant en tête que certaines ne seront réellement disponibles que sous un ensemble de conditions autres telles que les décisions des administrateurs, 
vos rôles et le réglage des profils de rôle.</p>

<p>Si vous supprimez tous les choix d\'équipement de votre profil, vous serez à nouveau en présence d\'un Moodle complet.</p>

<h4>Profils d\'équipement</h4>
<p>Il est possible que les administrateurs vous aient appliqué un profil d\'équipement prédéfini. Vous pouvez à tout moment réactiver ou au contraire
réduire encore votre équipement en deà ou au dessus de ce profil. Si vous vous considérez sous-équipé, et n\'arrivez pas à récpérer l\'usage de telle
ou telle fonction, contactez vos administrateurs de plate-forme pour faire le point avec eux.</p>

';

include_once('plugin_descriptions.php');