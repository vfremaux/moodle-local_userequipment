
/*eslint-disable no-unused-vars */

define(['jquery', 'core/config', 'core/log', 'local_userequipment/bootstrap-select'], function($, cfg, log, bootstrapselect) {

    var moodlebindcatpng = {

        init: function() {
            log.debug('Start AMD Userequipment CatPng initialized');
            $("#id_openmodal").bind('click', this.load_change_form);
            $("#modal-status-save").bind('click', this.submit_change_form);
            log.debug('AMD Userequipment CatPng initialized');
        },

        load_change_form: function() {
            var that = $(this);

            var waiter = '<div class="centered"><center><img id="detail-waiter" src="';
            waiter += cfg.wwwroot + '/pix/i/ajaxloader.gif" /></center></div>';
            $('#catpng-edit-inner-form').html(waiter);

            var plugintype = that.attr('data-plugintype');
            var pluginname = that.attr('data-pluginname');

            var url = cfg.wwwroot + '/local/userequipment/ajax/service.php';
            url += '?what=getplugincategories';
            url += '&plugintype=' + plugintype;
            url += '&pluginname=' + pluginname;

            $.get(url, function(data) {
                $('#catpng-edit-inner-form').html(data);
                $("#modal-status-save").attr('data-plugintype', plugintype);
                $("#modal-status-save").attr('data-pluginname', pluginname);
                $('.selectpicker').selectpicker();
            }, 'html');
        },

        submit_change_form: function() {
            var that = $(this);

            var pluginname = that.attr('data-pluginname');
            var plugintype = that.attr('data-plugin');
            var url = cfg.wwwroot + '/local/userequipment/ajax/service.php';
            url += '?what=update';
            url += '&name=' + pluginname;
            url += '&type=' + plugintype;
            var selectkey = '#cat-select-' + plugintype + '-' + pluginname;
            url += '&categories=' + $(selectkey).val();

            $.get(url, function(data) {
                if (data.result === 'success') {
                    $('#plugin-categories-' + plugintype + '-' + pluginname).html(data.catset);
                    $('#catpng-edit-form').modal('hide'); // Close the modal dialog.
                }
            }, 'json');
        }

    };

    return moodlebindcatpng;
});