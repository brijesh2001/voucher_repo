@extends('layouts.common')
@section('pageTitle')
    {{__('app.dashboard_title',["app_name"=> __('app.app_name')])}}
@endsection
@push('externalCssLoad')

@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>Dashboard</h2>
            <ol class="breadcrumb">

                <!-- <li><a href="#">Master Managemet</a></li> -->
                <!-- <li class="active">Collapsible Sidebar</li> -->
            </ol>
        </div>
        <div class="main-content container-fluid">


            <div class="row">
                <div class="col-md-6">
                    <div class="widget widget-fullwidth">
                        <div class="widget-head">
                            <!--                            <div class="tools"><span class="icon mdi mdi-chevron-down"></span><span class="icon mdi mdi-refresh-sync"></span><span class="icon mdi mdi-close"></span></div>-->
                            <span class="title">Daily Enquiry</span><span class="description">&nbsp;</span>
                        </div>
                        <div class="widget-chart-container">
                            {!! $enquiry->render() !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="widget widget-fullwidth">
                        <div class="widget-head">
                            <span class="title">Sale Data Trend</span><span class="description">&nbsp;</span>
                        </div>
                        <div class="widget-chart-container">
                            <div class="widget-chart-container">
                                {!! $sale->render() !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="widget widget-fullwidth">
                        <div class="widget-head">
                            <span class="title">Conversion Data Trend</span><span class="description">&nbsp;</span>
                        </div>
                        <div class="widget-chart-container">
                            <div class="widget-chart-container">
                                {!! $ratio->render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
@endsection

@push('externalJsLoad')

@push('internalJsLoad')

@endpush