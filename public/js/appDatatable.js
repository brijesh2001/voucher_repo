/**
 * Created by Brijesh on 24-01-2017.
 */

app.dataTable = {

    // Default datatable settings
    settings: function (param) {
        $.fn.dataTable.ext.errMode = 'none';
        dataTable = $(param.id).DataTable({
           /* responsive: {
                details: false
            },
            "scrollX": true,
            "bAutowidth": false,*/
            "info": true,
            "filter": false,
            "bLengthChange": true,
            "pageLength": 25,
            "lengthMenu": [[25,50,100,500],[25,50,100,500]],
            "order": param.order,
            "processing": true,
            "serverSide": true,
            "ajax": param.request,
            "language": {
             "emptyTable": "No Record Found",
             'processing': "Loading ...",
             },
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false
            }]
        });

        // Load datatable events
        app.dataTable.events(param);
    },

    // DataTable Events
    events: function (param) {

        // DataTable draw  event
        dataTable.off('draw.dt').on('draw.dt', function (o) {
            app.removeLoader();
        });

        // Show loader on ajax call
        dataTable.off('preXhr.dt').on('preXhr.dt', function () {
            app.showLoader("#dataTable");
        });

        // DataTable error event
        dataTable.off('error.dt').on('error.dt', function (e, settings, techNote, message) {
            // Attach search button click event
            alert("Server Error found try again later!");
            app.removeLoader();
        });

    },

    search: function () {
        $(".func_SearchGridData").on('click', function () {
            dataTable.ajax.reload();
        });

    },

    reset: function () {

        $(".func_ResetGridData").on('click', function () {
            $("#dataTable input,#dataTable select").each(function () {
                $(this).val("");
            });

            dataTable.ajax.reload();
        });

    },

    eventFire: function () {
        $("#dataTable input[type='text']").keydown(function(event){
            if(event.keyCode == 13) {
                dataTable.ajax.reload();
            }
        });

        $("#dataTable select").change(function(){
            dataTable.ajax.reload();
        });
    },

    // datatable settings
    custom: function (param) {
        if (typeof dataTable !== 'object') {

            app.dataTable.settings({
                id: "#dataTable",
                order: [],
                request: {
                    url: app.config.SITE_PATH + param.url,
                    type: "POST",
                    data: function (d) {
                        d._token = csrf_token;
                        var filter = {};
                        $.each($('input[name^="filter\\["]').serializeArray(), function () {
                            if ($.trim(this.value) !== '') {
                                var name = this.name.replace(/filter\[/, '');
                                name = name.replace(/\]/, '');
                                filter[name] = this.value;
                            }
                        });

                        d.filter = filter;

                        var filterDate = {};
                        $.each($('input[name^="filterDate\\["]').serializeArray(), function () {
                            if ($.trim(this.value) !== '') {
                                var name = this.name.replace(/filterDate\[/, '');
                                name = name.replace(/\]/, '');
                                filterDate[name] = this.value;
                            }
                        });

                        d.filterDate = filterDate;

                        var filterDate1 = {};
                        $.each($('input[name^="filterDate1\\["]').serializeArray(), function () {
                            if ($.trim(this.value) !== '') {
                                var name = this.name.replace(/filterDate1\[/, '');
                                name = name.replace(/\]/, '');
                                filterDate1[name] = this.value;
                            }
                        });

                        d.filterDate1 = filterDate1;

                        var filterSelect = {};
                        $.each($('select[name^="filterSelect\\["]').serializeArray(), function () {
                            if ($.trim(this.value) !== '') {
                                var name = this.name.replace(/filterSelect\[/, '');
                                name = name.replace(/\]/, '');
                                filterSelect[name] = this.value;
                            }
                        });

                        var filterExport = {};
                        $.each($('input[name^="filterExport\\["]').serializeArray(), function () {
                            if ($.trim(this.value) !== '') {
                                var name = this.name.replace(/filterExport\[/, '');
                                name = name.replace(/\]/, '');
                                filterExport[name] = this.value;
                            }
                        });

                        d.filterExport = filterExport;

                    },
                    complete:function (response) {

                        var data = $.parseJSON(response.responseText);
                        if(typeof data.url != 'undefined' && data.url != '') {

                            window.location = app.config.SITE_PATH+'enquiry/download/'+data.url;
                        }
                    }
                }
            });
        } else {
            dataTable.ajax.reload();
        }
    }

}