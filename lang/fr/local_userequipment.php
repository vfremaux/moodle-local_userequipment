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
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/local/userequipment/lib.php');

$string['userequipment:override'] = 'Surchage les limites d\'équipement';
$string['userequipment:selfequip'] = 'Peut choisir son propre équipement';
$string['userequipment:equip'] = 'Peut configurer l\'équipement d\'autres utilisateurs';
$string['userequipment:selftune'] = 'Peut ajuster son équipement';

$string['activities'] = 'Activités';
$string['activitieschooser'] = 'Ajouter une activité ou une ressource';
$string['activityfilters'] = 'Filtre sur les activités';
$string['addamodule'] = 'Ajouter une activité ou une ressource';
$string['addcategory'] = 'Ajouter une catégorie';
$string['addnew'] = 'Ajouter un nouveau {$a}';
$string['addone'] = 'Ajouter une catégorie';
$string['addplugins'] = 'Ajouter des plugins';
$string['addplugins'] = 'Ajouter des plugins';
$string['addtemplate'] = 'Ajouter un nouveau profil';
$string['all'] = 'Aucun filtre';
$string['allusers'] = 'Tous les utilisateurs activés';
$string['applyoncoursecompletion'] = 'Appliquer quand le cours est achevé';
$string['applyoncoursecompletion_desc'] = 'Sélectionnez ci-dessous le cours par son ID';
$string['applystrict'] = 'Profil strict';
$string['applytemplate'] = 'M\'appliquer l\'équipement : "{$a}"';
$string['applytemplatebtn'] = 'Appliquer le profil';
$string['applytoselection'] = 'Appliquer à la sélection';
$string['assignplugins'] = 'Assigner les plugins';
$string['associatedsystemrole'] = 'Rôle associé (site)';
$string['backtodashboard'] = 'Revenir au tableau de bord';
$string['backtolist'] = 'Retour à la liste des profils';
$string['cancel'] = 'Annuler';
$string['capabilitycontrol'] = 'Désactiver par capacité';
$string['catadd'] = 'Ajouter';
$string['catcolour'] = 'Couleur de la catégorie';
$string['catdesc'] = 'Description de la catégorie';
$string['catedit'] = 'Modifier';
$string['categories'] = 'Categories';
$string['categories'] = 'Catégories de plugins';
$string['categorization'] = 'Catégorisation des plugins';
$string['categorize'] = 'Catégoriser';
$string['categoryassignedplugins'] = 'Plugins associés à la catégorie';
$string['categoryplugins'] = 'Plugins associés';
$string['catname'] = 'Nom de la catégorie';
$string['catpng'] = 'Associer un plugin';
$string['cleanup'] = 'Revenir à l\'équipement complet';
$string['cleanup_desc'] = 'En utilisant cette fonction, vous effacez toutes vos données d\'équipement et accédez à toutes les fonctionnalités de moodle';
$string['colour'] = 'Couleur';
$string['configallowselftuning'] = 'Autoriser l\'ajustement fin';
$string['configallowselftuning_desc'] = 'Si activé, les utilisateurs pourront ajuster eux-mêmes leur équipment. Ce réglage peut être surpassé par une capacité.';
$string['configaskuserstoprofile'] = 'Demander de choisir un équipement à la connexion';
$string['configaskuserstoprofile_desc'] = 'Si actif, tout utilisateur se connectant pour la première fois et susceptible d\'avoir un rôle éditeur se verra proposer de choisir son équipement.';
$string['configautosetupnewusers'] = 'Initialiser les nouveaux utilisateurs';
$string['configautosetupnewusers_desc'] = 'Si actif, tout nouvel utilisateur créé se verra appliquer un équipement par défaut.';
$string['configdisablecontrol'] = 'Source de désactivation';
$string['configdisablecontrolvalue'] = 'Valeur de contrôle';
$string['configuseenhancedmodchooser'] = 'Utiliser le sélecteur d\'activité amélioré';
$string['configuseenhancedmodchooser_desc'] = 'Si activé, le sélecteur d\'activité standard est remplacé par sa version améliorée avec classification pédagogique';
$string['configforcedisableforuse'] = 'Forcer l\'abandon';
$string['configforcedisableforuse_desc'] = 'Un outil pour forcer l\'abandon d\'usage d\'un plugin, en le masquant des choix possibles, tout en laissant fonctionner ses instances, pour tous les utilisateurs, 
indépendamment du plan d\'équipemment. Donner une liste de noms FQDN des plugins à masquer.';
$string['coursetocomplete'] = 'Cours à compléter';
$string['currentplugincount'] = 'Nombre de plugins activés ';
$string['default'] = ' (défaut)';
$string['disabledforuser'] = 'L\'équipement utilisateur a été désactivé pour votre catégorie d\'utilisateur.';
$string['editcategory'] = 'Modifier une catégorie';
$string['editprofile'] = 'Modifier le profil: {$a}';
$string['emptycat'] = 'Pas de sélecteur d\'activité créé';
$string['enableuserequipment'] = 'Activer le plan d\'équipement individuel des utilisateurs';
$string['equipme'] = 'Gérer mes équipements';
$string['equipmentcleaned'] = 'L\'équipement a été supprimé. Vous avez accès à toutes les fonctionnalités.';
$string['fullname'] = 'Plugin';
$string['gotopluginsettings'] = 'Aller aux réglages centraux du plugin';
$string['isdefault'] = 'Est l\'équipement par défaut';
$string['managecategories'] = 'Gestionnaire des catégories de plugins';
$string['managetemplates'] = 'Gestionnaire de profils d\'équipement';
$string['marksinfo'] = 'Vous avez {$a} outils dans votre équipement.';
$string['nocategories'] = 'Aucune catégorie';
$string['none'] = 'Aucun role';
$string['noplugincategories'] = 'Aucune catégorie n\'a été définie pour la classification des plugins.';
$string['noplugins'] = 'Aucun plugin';
$string['notemplates'] = 'Aucun profil';
$string['other'] = 'Autres';
$string['placeholder_catcolour'] = '#00000';
$string['placeholder_catdesc'] = 'description';
$string['placeholder_catname'] = 'nom';
$string['pluginname'] = 'Equipement de l\'utilisateur';
$string['plugins'] = 'Plugins';
$string['pluginsettings'] = 'Réglages du plugin';
$string['pluginusers'] = 'Voir les utilisateurs équipés d\'un plugin';
$string['potentialmembers'] = 'Utilisateurs potentiels';
$string['profile'] = 'Modifier le profil';
$string['profileextended'] = 'Etendu';
$string['profilefieldcontrol'] = 'Désactiver par champ de profil';
$string['profilesimple'] = 'Elémentaire';
$string['profilestandard'] = 'Standard';
$string['profileupdated'] = 'Profil mis à jour';
$string['releasenever'] = 'Laisser assigné';
$string['releaseoncleanup'] = 'Désassigner sur la suppression des marques d\'équipement';
$string['releaseonnewprofile'] = 'Désassigner sur application d\'un nouveau profil';
$string['releaseroleon'] = 'Désassigner les roles';
$string['resources'] = 'Ressources';
$string['shortname'] = 'Code Plugin';
$string['strictapplication'] = 'Application stricte du profil';
$string['target'] = 'Utilisateurs à traiter';
$string['template'] = 'Profil';
$string['templateapplied'] = 'Une nouvelle définition d\'équipement a été chargée.';
$string['templatename'] = 'Nom de l\'équipement';
$string['templates'] = 'Profils d\'équipement';
$string['tools'] = 'Outils';
$string['usercanchoose'] = 'Peut être auto-appliqué';
$string['userequipment'] = 'Equipement de l\'utilisateur';
$string['userplugins'] = 'Voir le profil d\'un utilisateur';
$string['usersupdated'] = 'Les équipements utilisateurs sélectionnés ont été mis à jour';
$string['queryplugin'] = 'Recherche de plugin';
$string['queryplugin_help'] = 'Motif de recherche de plugins (utiliser * ou ? comme jokers sur le nom complet du plugin)';
$string['queryuser_help'] = 'Motif de recherche sur les utilisateurs (utiliser * ou ? comme jokers sur le nom complet)';
$string['queryuser'] = 'Recherche d\'utilisateurs';

$string['blocks'] = 'Blocs';
$string['modules'] = 'Activités ou ressources';
$string['courseformats'] = 'Formats de cours';
$string['questiontypes'] = 'Types de question';
$string['seeresultsfor'] = 'Voir les résultats pour ';
$string['noneequiped'] = 'Aucun utilisateur équipé ';

$string['strictapplication_help'] = 'Une application stricte du profil d\'équipement supprimera tous les choix préalables des utilisateurs ciblés.';

$string['isdefault_desc'] = 'Si ce profil d\'équipement est le profil par défaut, tout nouvel utilisateur créé se verra appliquer ce profil.
Marquer ce profil supprime l\'affectation, par défaut précédente.';

$string['applystrict_desc'] = 'Avec cette option activée, vous supprimez toutes les autorisations non prévues dans ce profil.';

$string['enableuserequipment_desc'] = 'Si activé, les utilisateurs (editeurs) peuvent choisir les fonctions qu\'ils mobilisent,
et nettoyer les interfaces de tout ce qui ne leur sert pas dans leur processus d\'édition.';

$string['configdisablecontrol_desc'] = 'Permet de choisir comment l\'auto-équipement de certains utilisateurs est désactivé.
La désactivation peut être pilotée par les rôles en désignant une capacité précise, ou par des valeurs de champ de profil.';

$string['configdisablecontrolvalue_desc'] = 'Définit la valeur de contrôle. Cela peut être le nom d\'une capacité ou le code
d\'un champ de profil qui doit contenir une valeur non vide. Vous pouvez également utiliser une expression
&lt;codechamp&gt;=&lt;valeur&gt; pour désactiver l\'équipement sur une valeur précise du champ de profil.';

$string['ueinfo_tpl_deprecated'] = '
<p>Moodle est une application de plus en plus riche et complète. Il est probable que les administrateurs et les autres usagers de Moodle
utilisent des fonctions dont vous ne vous servez pas du tout (ou pas encore). Le principe de l\'équipement utilisateur vous permet
de sélectionner et de nettoyer les menus de choix de fonctions de Moodle afin de vous donner une vision plus claire de vos interfaces de
gestion des cours.</p>

<h4>Comment cela fonctionne ?</h4>

<p>L\'équipement utilisateur est actif dans votre Moodle. Chaque utiisateur peut contrôler par lui-même s\'il active
ou non les plugins installés dans la plate-forme. Comme nous gérons ces activations au niveau "Utilisateur", il n\'est pas
raisonnable d\'explorer globalement tous les roles et tous les droits dont vous disposez dans tous les contextes de travail.
De ce fait, les listes d\'équipement pourraient vous mentionner des fonctionnalités ou plugins dont vous n\'avez jamais entendu
parler et qui de toutes façons vous seraient innaccessibles au regard de vos rôles. Nous avons essayé d\'être suffisament
explicites pour que vous puissiez rapidement vous repérer dans les propositions et voir si elles correspondent à votre usage.</p>

<p>Naviguez dans les autres sections du formulaire pour examiner les listes de blocs, modules, et autres types de plugins qui
pourraient constituer votre équipement personnel, tout en gardant en tête que certains ne seront réellement disponibles que
sous un ensemble de conditions autres telles que les décisions des administrateurs, vos rôles et le réglage des profils de rôle.</p>

<p>Si vous supprimez tous les choix d\'équipement de votre profil, vous serez à nouveau en présence d\'un Moodle complet.</p>

<h4>Profils d\'équipement</h4>
<p>Il est possible que les administrateurs vous aient appliqué un profil d\'équipement prédéfini. Vous pouvez à tout moment
réactiver ou au contraire réduire encore votre équipement en deçà ou au dessus de ce profil. Si vous vous considérez sous-équipé,
et n\'arrivez pas à récupérer l\'usage de telle ou telle fonction, contactez vos administrateurs de plate-forme pour faire le
point avec eux.</p>
';

$string['ueinfo_tpl'] = "
<h4>Introduction</h4>

<p>Moodle dispose de nombreux plugins. Il est probable qu'il y ait des outils sur ce Moodle dont vous ne vous servez
pas du tout (ou pas encore).</p>
<p>Si l'administrateur vous y autorise, vous pourrez constituer votre propre équipement, ou choisir un équipement
en outils prédéfini.</p>

<h4>Equipement sur mesure</h4>

<p>Naviguez dans les autres onglets pour examiner les listes d'outils qui vont constituer votre équipement personnel
en cochant ce qui vous intéresse.</p>
<p>Si vous supprimez tous les choix d'équipement, vous aurez à nouveau tous les outils à votre disposition.</p>

<h4>Equipement prédéfini</h4>

<p>Il est possible que les administrateurs vous aient appliqué outillage prédéfini. Si les administrateurs l'ont autorisé, Vous pouvez à tout moment le modifier
pour ajouter ou supprimer un outil ou changer pour un des équipements proposés ci après.</p>
";

$string['ueselfinfo_tpl'] = '
<h4>Me choisir un profil d\'équipement</h4>

<p>Dans certains Moodles, vous pouvez vous choisir un profil d\'équipement directement sans passer par un administrateur. Cliquez sur un
des boutons ci-dessous pour appliquer un profil. Ceci effacera tout votre équipement précédent et appliquera celui que vous avez choisi.
Vous pourrez toujours réactiver des fonctionnalités individuelles en plus si elles ne sont pas proposées par le profil.</p>
';

$string['profilesimple_desc'] = 'Un profil simple pour ceux qui désirent les fonctions de base d\'un LMS uniquement, avec la possibilité
de publier des ressources, de récolter des devoirs et de faire des quiz. Ce profil convient aux personnes ayant eu peu d\'expérience
numérique antérieur et désireuses de démarrer un enseignement numérique basique mais essentiel.';

$string['profilestandard_desc'] = 'Un profil exploitant les fonctionnalités standard de moodle, installées sur tous les sites de la même
version. Ce profil convient pour ceux qui désirent constituer des volumes de cours transportables et compatibles avec d\'autres
implantations de plates-formes et bénéficiant de fonctionnalités pédagogiques riches.';

$string['profileextended_desc'] = 'Un profil enrichi avec des fonctionnalités pédagogiques supplémentaires, afin de pouvoir diversifier les
activités et rendre l\'expérience d\'apprentissage plus dynamique.';

$string['associatedsystemrole_help'] = 'Un rôle appliqué dans le contexte site lorsque ce profil est appliqué.';

include(__DIR__.'/pro_additional_strings.php');
include(__DIR__.'/plugin_descriptions.php');