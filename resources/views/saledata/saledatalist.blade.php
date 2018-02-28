@extends('layouts.common')
@section('pageTitle')
    {{__('app.default_list_title',["app_name" => __('app.app_name'),"module"=> __('app.sale_data')])}}
@endsection
@push('externalCssLoad')
<link rel="stylesheet" href="{{url('css/plugins/jquery.datetimepicker.css')}}" type="text/css"/>
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.sale_data')}} Management</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li class="active">{{trans('app.sale_data')}} Listing</li>
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

                        </div>
                    </div>
                </div>
                <div class="deta-table user-table pull-left">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="panel panel-default panel-table">
                               <label>TO:</label> <input type="text" name="filterDate[tbl_sale_data.created_at]" id="date1" style="width: 100px;" value="" />
                                <label>FROM:</label> <input type="text" name="filterDate1[tbl_sale_data.created_at]" id="date2" style="width: 100px;" value="" />
                                <div class="panel-body">
                                    <table id="dataTable"
                                           class="table display dt-responsive responsive nowrap table-striped table-hover table-fw-widget"
                                           style="width: 100%;">

                                        <thead>

                                        <tr>
                                            <th class="no-sort">Date</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Voucher</th>
                                            <th>Trans Id</th>
                                            <th>Rate</th>
                                            <th>Total Amount</th>
                                            <th>No of Qty</th>

                                        </tr>

                                        </thead>
                                        <thead>
                                        <tr>
                                            <th>
                                                <input type="text" name="filter[tbl_sale_data.created_at]" id="date" style="width: 60px;" value="" />
                                            </th>
                                            <th>
                                                <input type="text" name="filter[tbl_sale_data.name]"  style="width: 60px;" value="" />
                                            </th>
                                            <th>
                                                <input type="text" name="filter[tbl_enquiry.email]" style="width: 80px;"  value="" />
                                            </th>
                                            <th>
                                                <input type="text" name="filter[tbl_enquiry.mobile]" style="width: 80px;"  value="" />
                                            </th>
                                            <th>
                                                <input type="text" name="filter[tbl_sale_data.voucher_code]" style="width: 80px;"  value="" />
                                            </th>
                                            <th>
                                                <input type="text" name="filter[tbl_sale_data.payment_code]" style="width: 80px;"  value="" />
                                            </th>
                                            <th>
                                                <input type="text" name="filter[tbl_sale_data.rate]" style="width: 50px;"  value="" />
                                            </th>
                                            <th>
                                                <input type="text" name="filter[tbl_sale_data.amount_paid]" style="width: 50px;"  value="" />
                                            </th>
                                            <th>
                                                <input type="text" name="filter[tbl_sale_data.number_of_voucher]" style="width: 30px;"  value="" />
                                            </th>

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

<script src="{{url('js/plugins/jquery.datetimepicker.js')}}" type="text/javascript"></script>
<script src="{{url('js/appDatatable.js')}}"></script>
<script src="{{url('js/modules/saledata.js')}}"></script>
<script>
    $( function() {
        $( "#date" ).datepicker({dateFormat: 'dd-mm-yy'});
        $( "#date1" ).datepicker({dateFormat: 'dd-mm-yy'});
        $( "#date2" ).datepicker({dateFormat: 'dd-mm-yy'});
    } );
</script>
@endpush
@push('internalJsLoad')
<script>
    app.saledata.init();
</script>
@endpush