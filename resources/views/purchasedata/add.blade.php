@extends('layouts.common')
@section('pageTitle')
    {{('Purchase data')}}
@endsection
@push('externalCssLoad')
<link rel="stylesheet" href="{{url('css/plugins/jquery.datetimepicker.css')}}" type="text/css" />
@endpush
@push('internalCssLoad')

@endpush
@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>Purchase {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('/purchase/list')}}">Purchase {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.add')}} Purchase entry</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider">{{trans('app.add')}} Purchase entry</div>
                        <div class="panel-body">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{url('/purchase/store')}}" name="app_add_form" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Invoice Date<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="purchase_date" id="purchase_date" placeholder="Invoice Date" class="form-control input-sm required" value="{{old('purchase_date')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Payment made Date<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="received_date" id="received_date" placeholder="Payment made Date" class="form-control input-sm required" value="{{old('received_date')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">RTGS No<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="rtgs" id="rtgs" placeholder="RTGS No" class="form-control input-sm required" value="{{old('rtgs')}}" />
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Narration<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="narration" id="narration" placeholder="Narration" class="form-control input-sm required" value="{{old('narration')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Invoice Number<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="invoice_number" id="invoice_number" placeholder="Invoice Number" class="form-control input-sm required" value="{{old('invoice_number')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Quantity<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="number" name="quantity" id="quantity" placeholder="Quantity" class="form-control input-sm required" value="{{old('quantity')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Voucher Per Prize<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="number" name="per_voucher_prize" id="per_voucher_prize" placeholder="Quantity" class="form-control input-sm required" value="{{old('per_voucher_prize')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Total Amount<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="number" name="total_amount" id="total_amount" placeholder="Total Amount" class="form-control input-sm required" value="{{old('total_amount')}}" />
                                    </div>
                                </div>

                                {{ csrf_field() }}

                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-space btn-info btn-lg">{{trans('app.add')}} entry</button>
                                        <a href="{{url('/purchase/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('externalJsLoad')
<script src="{{url('js/plugins/jquery.datetimepicker.js')}}" type="text/javascript"></script>
<script>
    $( function() {
        $( "#purchase_date" ).datepicker({dateFormat: 'dd-mm-yy'});
        $( "#received_date" ).datepicker({dateFormat: 'dd-mm-yy'});
    } );
</script>
@endpush
