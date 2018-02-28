@extends('layouts.common')
@section('pageTitle')
    Agent List
@endsection
@push('externalCssLoad')
<link rel="stylesheet" href="{{url('css/plugins/jquery.datetimepicker.css')}}" type="text/css"/>
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>Agent List Management</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li class="active">Agent Data Listing</li>
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
                            {{--<div class="pull-left">
                                <a href="javascript:void(0);" class="btn btn-warning func_SearchGridData"><i
                                            class="icon mdi mdi-search"></i> Search</a>
                            </div>
                            <div class="pull-left">
                                <a href="javascript:void(0);" class="btn btn-danger func_ResetGridData"
                                   style="margin-left: 10px;">Reset</a>
                            </div>--}}
                            <div class="addreport pull-right">
                                <a href="{{url('/agent/add')}}">
                                    <button class="btn btn-space btn-primary"><i
                                                class="icon mdi mdi-plus "></i> {{trans('app.add')}} entry
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

                                        <thead>

                                        <tr>
                                            <th class="no-sort">Sr no</th>
                                            <th class="no-sort">Name</th>
                                            <th class="no-sort">Email</th>
                                            <th class="no-sort">Mobile</th>
                                            <th class="no-sort">Amount</th>
                                            <th class="no-sort">Action</th>
                                            <th class="no-sort">Mail Action</th>
                                        </tr>

                                        </thead>
                                        <thead>
                                        <tr>
                                            <th> </th>
                                            <th> </th>
                                            <th></th>
                                            <th></th>
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
<script src="{{url('js/modules/agent.js')}}"></script>
@endpush
@push('internalJsLoad')
<script>
    app.agent.init();
</script>

<script type="text/javascript">

    $(document).on('click','.send',function(){

        var id = $(this).attr('rel');
            $.ajax({
                type: "POST",
                url: app.config.SITE_PATH + 'agent/send-mail',
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