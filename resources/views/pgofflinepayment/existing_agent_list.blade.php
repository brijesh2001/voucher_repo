@extends('layouts.common')
@section('pageTitle')
    {{__('app.default_list_title',["app_name" => __('app.app_name'),"module"=> __('app.pgoffline')])}}
@endsection
@push('externalCssLoad')
<link rel="stylesheet" href="{{url('css/plugins/jquery.datetimepicker.css')}}" type="text/css"/>
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>PTE GURUS Offline Payment Management</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li class="active">Payment Listing</li>
            </ol>
        </div>
        <div class="main-content container-fluid">

            <!-- Caontain -->
            <div class="panel panel-default panel-border-color panel-border-color-primary pull-left">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="activity-but activity-space pull-left">
                            <div class="pull-left">
                                <a href="javascript:void(0);" class="btn btn-warning func_SearchGridData"><i
                                            class="icon mdi mdi-search"></i> Search</a>
                            </div>
                            <div class="pull-left">
                                <a href="javascript:void(0);" class="btn btn-danger func_ResetGridData"
                                   style="margin-left: 10px;">Reset</a>
                            </div>
                            <div class="addreport pull-right">
                                <a href="{{url('/pgoffline/add-new-agent')}}">
                                    <button class="btn btn-space btn-primary"><i
                                                class="icon mdi mdi-plus "></i> Add new payment
                                    </button>
                                </a>
                            </div>
                            <div class="addreport pull-right">
                                <a href="javascript:void(0);" class="export">
                                    <button class="btn btn-space btn-primary"><i
                                                class="icon mdi mdi-plus "></i> Export
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="deta-table user-table pull-left">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-table">
                                <label>TO:</label> <input type="text" name="filterDate[tbl_pgoffline_payment.payment_date]" id="date1" style="width: 100px;" value="" />
                                <label>FROM:</label> <input type="text" name="filterDate1[tbl_pgoffline_payment.payment_date]" id="date2" style="width: 100px;" value="" />
                                <div class="panel-body">
                                    <table id="dataTable"
                                           class="table display dt-responsive responsive nowrap table-striped table-hover table-fw-widget"
                                           style="width: 100%;">

                                        <thead>

                                        <tr>
                                            <th class="no-sort">Date</th>
                                            <th class="no-sort">Invoice No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>GSTN</th>
                                            <th>HSN/SAC</th>
                                            <th>Item</th>
                                            <th>Details</th>
                                            <th>Trans Id</th>
                                            <th>Before GST</th>
                                            <th>SGST</th>
                                            <th>CGST</th>
                                            <th>IGST</th>
                                            <th>After GST</th>
                                            <th>Payment Type</th>
                                            <th>State</th>
                                            <th>Actions</th>
                                            <th>PDF Download</th>
                                            <th>Email Send</th>


                                        </tr>

                                        </thead>
                                        <thead>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <input type="hidden" name="filterExport[export_excel]"  id="export_excel" value="0" />
                                        </tr>
                                        </thead>

                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('externalJsLoad')
<script src="{{url('js/appDatatable.js')}}"></script>
<script src="{{url('js/modules/pgofflinepayment.js')}}"></script>
<script src="{{url('js/plugins/jquery.datetimepicker.js')}}" type="text/javascript"></script>
<script>
    $( function() {
        $( "#date1" ).datepicker({dateFormat: 'dd-mm-yy'});
        $( "#date2" ).datepicker({dateFormat: 'dd-mm-yy'});
    } );
</script>
@endpush
@push('internalJsLoad')
<script>
    app.pgofflinepayment.init();
    $(document).ready(function () {
        $(document).on('click', '.export', function () {
            $('#export_excel').val('1');
            dataTable.ajax.reload();
            $('#export_excel').val('0');
        });

    });

    $(document).on('click','.send',function(){

        var id = $(this).attr('rel');
        $.ajax({
            type: "POST",
            url: app.config.SITE_PATH + 'pgoffline/send-mail',
            data: {id: id, _token: csrf_token},
            success: function (response) {
                if (response.statusCode == "1") {
                    alert('mail send');
                }
            }
        });

    });
</script>
@endpush