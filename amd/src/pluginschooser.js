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

// jshint unused: true, undef:true
/* eslint no-unused-vars: 0, no-console: 0 */

define(['jquery', 'core/log', 'core/config', 'core/str'], function($, log, cfg, str) {

    var localuserequipmentplgchooser = {

        strs: [],

        init: function() {

            var stringdefs = [
                {key: 'noneselected', component: 'local_userequipment'}, // 0
                {key: 'noneavailable', component: 'local_userequipment'} // 1
            ];

            str.get_strings(stringdefs).done(function(s) {
                localuserequipmentplgchooser.strs = s;
            });

            $('#id_selectall').on('click', this.select_all);
            $('#id_selectplugins').on('click', this.select_plugins);
            $('#id_unselectplugins').on('click', this.unselect_plugins);
            $('#id_unselectall').on('click', this.unselect_all);
            log.debug("AMD local userequipment pluginchooser initialized");
        },

        select_all: function(e) {
            e.stopPropagation();
            e.preventDefault();

            var aselect = document.getElementById('id_availableplugins');

            // Checking if none value.
            if (aselect.length === 0 && aselect.item(0).value === 'ue_none') {
                return;
            }

            // Selecting all options.
            for (var i = 0; i < aselect.length; i++) {
                aselect.item(i).selected = true;
            }

            localuserequipmentplgchooser.select_plugins(e);
        },

        select_plugins: function(e) {
            e.stopPropagation();
            e.preventDefault();

            var aselect = document.getElementById('id_availableplugins');
            var sselect = document.getElementById('id_selectedplugins');

            // Moving option to the selected select.
            localuserequipmentplgchooser.move_selected_options(aselect, sselect);
            for (let i = 0; i < sselect.options.length; i++) {
                sselect.options[i].selected = "true";
            }
        },

        unselect_plugins: function(e) {
            e.stopPropagation();
            e.preventDefault();

            var aselect = document.getElementById('id_availableplugins');
            var sselect = document.getElementById('id_selectedplugins');

            // Moving option to the selected select.
            localuserequipmentplgchooser.move_selected_options(sselect, aselect);
        },

        unselect_all: function(e) {
            e.stopPropagation();
            e.preventDefault();

            // Getting HTMLelement.
            var sselect = document.getElementById('id_selectedplugins');

            // Checking if none value.
            if (sselect.length === 0 && sselect.item(0).value === 'ue_none') {
                return;
            }

            // Selecting all options.
            for (var i = 0; i < sselect.length; i++) {
                sselect.item(i).selected = true;
            }

            localuserequipmentplgchooser.unselect_plugins(e);
        },

        move_selected_options: function(fromselect, toselect) {

            // Getting HTMLelement.
            var option;

            // Moving option to the selected select.
            for (var i = 0; i < fromselect.length; i++) {
                if (fromselect.item(i).selected) {
                    // Checking if none value.
                    if (fromselect.item(i).value === 'ue_none') {
                        continue;
                    }

                    // Checking if to is none value.
                    if (toselect.length === 1 && toselect.item(0).value === 'ue_none') {
                        toselect.remove(0);
                    }
                    // Adding option to selected.
                    toselect.appendChild(fromselect.item(i));

                    // Updating counter.
                    i--;
                }
            }

            // Checking if remains value.
            if (fromselect.length === 0) {

                // Adding none value.
                option = document.createElement('option');
                option.value = 'ue_none';
                option.text = localuserequipmentplgchooser.strs[0];
                fromselect.appendChild(option);
            }

            if (toselect.length === 0) {

                // Adding none value.
                option = document.createElement('option');
                option.value = 'ue_none';
                option.text = localuserequipmentplgchooser.strs[1];
                toselect.appendChild(option);
            }
        }

    };

    return localuserequipmentplgchooser;
});
