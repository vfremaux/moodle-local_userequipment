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

    var localuserequipmentequform = {

        init: function() {
            $('.local-userequipment .toggle-handle').bind('click', this.toggle_panel);
            $('.local-userequipment .pluginselect input[type="checkbox"]').bind('change', this.recount_plugins);
            log.debug("Module local_userequipment equipmentform initialized");
        },

        toggle_panel: function(e) {
            e.stopPropagation();
            e.preventDefault();

            // hide all.
            $('.userequipment-panel').addClass('quickform-hidden-tab');

            // unhide required and mark tab as active.
            var that = $(this);
            var plugintype = that.attr('data-plugintype');
            $('.userequipment-panels #id-panel-' + plugintype).removeClass('quickform-hidden-tab');

            $('.local-userequipment .quickform-tab > a').removeClass('active');
            $('.local-userequipment #id-tab-' + plugintype + ' > a').addClass('active');
        },

        recount_plugins : function(e) {

            var that = $(this);
        }

    };

    return localuserequipmentequform;
});
