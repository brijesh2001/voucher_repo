@extends('layouts.common')
@section('pageTitle')
    {{__('app.default_list_title',["app_name" => __('app.app_name'),"module"=> __('app.voucher')])}}
@endsection
@push('externalCssLoad')
<link rel="stylesheet" href="{{url('css/plugins/jquery.datetimepicker.css')}}" type="text/css"/>
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.voucher')}} Management</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li class="active">{{trans('app.voucher')}} Listing</li>
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
                                <a href="{{url('/voucher/add')}}">
                                    <button class="btn btn-space btn-primary"><i
                                                class="icon mdi mdi-plus "></i> {{trans('app.add')}} {{trans('app.voucher')}}
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
                                <div class="panel-body">
                                    <table id="dataTable"
                                           class="table display dt-responsive responsive nowrap table-striped table-hover table-fw-widget"
                                           style="width: 100%;">
                                        {{--<colgroup>
                                            <col width="9%">
                                            <col width="9%">
                                            <col width="9%">
                                            <col width="9%">
                                            <col width="10%">
                                            <col width="10%">
                                            <col width="10%">
                                            <col width="10%">
                                            <col width="10%">
                                            <col width="10%">

                                        </colgroup>--}}
                                        <thead>

                                        <tr>
                                            <th class="no-sort">Sr no</th>
                                            <th>Voucher Code</th>
                                            <th>Status</th>
                                            <th>Updated At</th>
                                            <th class="no-sort">Action</th>
                                        </tr>

                                        </thead>
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>
                                                <input type="text" name="filter[voucher_code]" style="width: 80px;" id="
                                                       voucher_code" value="" />
                                            </th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
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
<script src="{{url('js/modules/promo.js')}}"></script>
@endpush
@push('internalJsLoad')
<script>
    app.promo.init();
</script>
@endpush