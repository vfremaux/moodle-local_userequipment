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

define(['jquery', 'core/log', 'core/config'], function($, log, cfg) {

    var localuserequipmentmodchooser = {

        init: function() {

            $('.openmodal-activitychooser-link').bind('click', this.opendialog);
            $('.filter-section .filter').bind('click', this.filter);
            $('.openhelp').bind('click', this.openhelp);
            $('.back-selector').bind('click', this.goback);
            $('.filternone').css('opacity', 0.2); // unshadow "none"
            log.debug("AMD local userequipment modchooser initialized");
        },

        opendialog: function(e) {

            var vregex;
            var sectionid = $(this).attr('data-sectionid');
            var sectionnum = $(this).attr('data-sectionnum');
            console.log(sectionid + '/' + sectionnum);

            $('.add-module-link').each(function() {
                vregex = /section=[0-9]+/;
                this.href = this.href.replace(vregex, 'section=' + sectionnum);
                vregex = /sr=[0-9]+/;
                this.href = this.href.replace(vregex, 'sr=' + sectionnum);
            });
        },

        filter: function(e) {

            e.stopPropagation();
            e.preventDefault();

            // get category id
            var catid = parseInt($(this).attr('data-category'));
            log.debug("Catid : "+ catid);
            log.debug("Hasclass : "+ $(this).hasClass('selected'));
            log.debug("Testcat : "+ (catid === 0));

            if ($(this).hasClass('selected') || (catid === 0)) {
                log.debug("Defiltering ");
                // if selected an isolated, release filter.
                // process button
                $(this).removeClass('selected'); // unmark me
                $('.filter').css('opacity', 'unset'); // unshadow all filters
                $('.filternone').css('opacity', 0.2); // unshadow "none"

                // process activities and resource elements
                $('.activity-element').removeClass('decline');
                // $('.activity-element').show(500);
            } else {
                log.debug("Filtering ");
                // if not yet selected, enable filter.
                // process button
                $('.filter').removeClass('selected'); // remove to all
                $(this).addClass('selected'); // and add to me.
                $('.filter').css('opacity', 0.2); // shadow all
                $('.filternone').css('opacity', 'unset'); // shadow all
                $(this).css('opacity', 'unset'); // unshadow me.

                // process activities and resource elements
                $('.activity-element').not('.cat-' + catid).addClass('decline');
                // $('.activity-element').not('.cat-' + catid).hide(500);
                $('.activity-element').not('.cat-' + catid);
                $('.activity-element.cat-' + catid).removeClass('decline');
                // $('.activity-element.cat-' + catid).show(500);
                $('.activity-element.cat-' + catid);
            }
         },

        openhelp: function(e) {

            e.stopPropagation();
            e.preventDefault();

            $('.carousel-item').fadeIn();

            $('.main-section').hide();
            $('.filter-section').hide();

            // get plugin name and link
            var pluginname = $(this).attr('data-value');
            var pluginlink = $(this).attr('data-link');
            var sectionnum = $(this).attr('data-sectionnum');
            var sectionlink = '&section=' + sectionnum;
            pluginlink = pluginlink.replace(/&amp;/g, '&');
            sectionlink = sectionlink.replace(/&amp;/g, '&');

            // load help for current plugin.
            var url = cfg.wwwroot + '/local/userequipment/ajax/service.php';
            url += '?what=' + 'gethelp';
            url += '&modname=' + pluginname;
            $.get(url, function(data) {
                log.debug("Loading help topic");
                $('#optionsummary-label').html('' + data.image + ' ' + data.label);
                $('#optionsummary-desc').html(data.help);
            }, 'json');

            $('.addoption').attr('href', pluginlink + sectionlink);
         },

         goback: function(e) {

            e.stopPropagation();
            e.preventDefault();

            $('.carousel-item').hide();
            $('.main-section').fadeIn();
            $('.filter-section').fadeIn();
         }
    };

    return localuserequipmentmodchooser;
});
