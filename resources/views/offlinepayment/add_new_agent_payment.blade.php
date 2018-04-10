@extends('layouts.common')
@section('pageTitle')
    {{__('app.default_add_title',["app_name" => __('app.app_name'),"module"=> __('app.add_new_agent_payment')])}}
@endsection

@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.offline')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('/offline/add-new-agent')}}">{{trans('app.offline')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.add')}} {{trans('app.offline')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider"> Add Offline Payment</div>
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
                            <form action="{{url('/offline/store-new-agent-payment')}}" name="app_add_form" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Name<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="name" id="name" placeholder="Name" class="form-control input-sm required" value="{{old('name')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Email<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="email" id="email" placeholder="Email" class="form-control input-sm required" value="{{old('email')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Mobile<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="mobile" id="mobile" placeholder="Mobile" class="form-control input-sm required" value="{{old('mobile')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Payment Date<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="payment_date" id="payment_date" placeholder="Payment Date" class="form-control input-sm required" value="{{old('payment_date')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Voucher Code<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="voucher_code" id="voucher_code" placeholder="Voucher Code" class="form-control input-sm required" value="{{old('voucher_code')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Number Of Voucher<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="number" name="number_of_voucher" id="number_of_voucher" placeholder="Number Of Voucher" class="form-control input-sm required" value="{{old('number_of_voucher')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Rate<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="rate_after_gst" id="rate_after_gst" placeholder="Rate" class="form-control input-sm required" value="{{old('rate_after_gst')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Transaction Id<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="transaction_id" id="transaction_id" placeholder="Transaction Id" class="form-control input-sm " value="{{old('transaction_id')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">GSTN<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="gstn" id="gstn" placeholder="GSTN" class="form-control input-sm" maxlength="15" value="{{old('gstn')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">State <span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <select class="form-control input-sm required" name="state" id="state">
                                            <option value="">State</option>
                                            @if(count($state) > 0)
                                                @foreach($state as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                {{ csrf_field() }}

                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-space btn-info btn-lg">{{trans('app.add')}} Payment</button>
                                        <a href="{{url('/offline/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
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
        $( "#payment_date" ).datepicker({dateFormat: 'dd-mm-yy'});
    } );
</script>
@endpush
