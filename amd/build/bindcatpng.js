
/*eslint-disable no-unused-vars */

define(['jquery', 'core/config', 'core/log', 'local_userequipment/bootstrap-select'], function($, cfg, log, bootstrapselect) {

    var moodlebindcatpng = {

        init: function() {
            log.debug('Start AMD Userequipment CatPng initialized');
            var plugin = $(this).attr('data-value');
            $("#id_openmodal"+ plugin).bind('click', this.load_change_form);
            $("#modal-status-save").bind('click', this.submit_change_form);
            log.debug('AMD Userequipment CatPng initialized');
        },

        load_change_form: function() {
            var that = $(this);

            var waiter = '<div class="centered"><center><img id="detail-waiter" src="';
            waiter += cfg.wwwroot + '/pix/i/ajaxloader.gif" /></center></div>';
            $('#catpng-edit-inner-form').html(waiter);

            var id = that.attr('id').replace(/issue-edit-(.*?)-handle-/, '');
            var mode = that.attr('data-mode');
            var ctx = that.attr('data-context');

            var url = cfg.wwwroot + '/local/userequipment/ajax/service.php';
            url += '?what=getmodalform';
            url += '&mode=' + mode;
            url += '&id=' + id;
            url += '&ctx=' + ctx;

            $.get(url, function(data) {
                $('#catpng-edit-inner-form').html(data);
                $("#modal-status-save").attr('data-issue', id);
                $("#modal-status-save").attr('data-purpose', mode);
                $('.selectpicker').selectpicker();
            }, 'html');
        },

        submit_change_form: function() {
            var that = $(this);

            var issueid = that.attr('data-issue');
            var purpose = that.attr('data-purpose');
            var url = cfg.wwwroot + '/local/userequipment/ajax/service.php';
            url += '?what=update' + purpose;
            url += '&id=' + issueid;
            var selectkey = '#' + purpose + '-select-' + issueid;
            url += '&' + purpose + '=' + $(selectkey).val();

            $.get(url, function(data) {
                if (data.result === 'success') {
                    if (purpose == 'status') {
                        var oldclassname = 'status-' + moodlebindcatpng.get_status_code(data.oldvalue);
                        $('.issue-list-status-' + issueid).removeClass(oldclassname);
                        var newclassname = 'status-' + moodlebindcatpng.get_status_code(data.newvalue);
                        $('.issue-list-status-' + issueid).addClass(newclassname);
                    }
                    $('#tracker-' + purpose + '-' + issueid).html(data.newlabel);
                    $('#catpng-edit-form').modal('hide'); // Close the modal dialog.
                }
            }, 'json');
        },

        get_status_code: function (statusix) {
            var statuscodes = ['posted',
                'open',
                'resolving',
                'waiting',
                'resolved',
                'abandonned',
                'transfered',
                'testing',
                'validated',
                'published'
            ];

            return statuscodes[statusix];
        },

        solve_issue: function() {
            var that = $(this);

            var issueid = that.attr('id').replace('resolve-', '');
            var cmid = that.attr('data-cmid');
            var url = cfg.wwwroot + '/local/userequipment/view.php?id=' + cmid;
            url += '&view=view';
            url += '&screen=mytickets';
            url += '&what=solve';
            url += '&issueid=' + issueid;
            url += '&sesskey=' + cfg.sesskey;
            window.location = url;
        },

        quick_find: function() {
            $('#id-quick-find-form').submit();
        }

    };

    return moodlebindcatpng;
});