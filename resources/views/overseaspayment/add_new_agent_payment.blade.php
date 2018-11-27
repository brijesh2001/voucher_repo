@extends('layouts.common')
@section('pageTitle')
    {{__('app.default_add_title',["app_name" => __('app.app_name'),"module"=> __('app.add_new_agent_payment')])}}
@endsection

@section('content')
    <div class="be-content">
        <div class="page-head">
            <h2>{{trans('app.overseas')}} {{trans('app.management')}}</h2>
            <ol class="breadcrumb">
                <li><a href="{{url('/dashboard')}}">{{trans('app.admin_home')}}</a></li>
                <li><a href="{{url('/overseas/add-new-agent')}}">{{trans('app.overseas')}} {{trans('app.management')}}</a></li>
                <li class="active">{{trans('app.add')}} {{trans('app.overseas')}}</li>
            </ol>
        </div>
        <div class="main-content container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-border-color panel-border-color-primary">
                        <div class="panel-heading panel-heading-divider"> Add Overseas Payment</div>
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
                            <form action="{{url('/overseas/store-new-agent-payment')}}" name="app_add_form" id="app_form" style="border-radius: 0px;" method="post" class="form-horizontal group-border-dashed">

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
                                        <input type="text" name="payment_date" id="payment_date" placeholder="Payment Date" class="form-control input-sm required" value="{{old('payment_date')}}" autocomplete="off" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Item<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="item" id="item" placeholder="Item" class="form-control input-sm required" value="{{old('item')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Detail<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="detail" id="detail" placeholder="Detail" class="form-control input-sm required" value="{{old('detail')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Dollar Amount Paid<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="dollor_amount_paid" id="dollor_amount_paid" placeholder="Dollar Amount Paid" class="form-control input-sm required" value="{{old('dollor_amount_paid')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Dollar Amount Received<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="dollor_amount_received" id="dollor_amount_received" placeholder="Dollar Amount Received" class="form-control input-sm required" value="{{old('dollor_amount_received')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Rs Amount<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="amount" id="amount" placeholder="Rs Amount" class="form-control input-sm required" value="{{old('amount')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Transaction Id<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="transaction_id" id="transaction_id" placeholder="Transaction Id" class="form-control input-sm " value="{{old('transaction_id')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">LUT/GSTN<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="lut" id="lut" placeholder="LUT/GSTN" class="form-control input-sm" value="{{old('lut')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Address<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="address" id="address" placeholder="Address" class="form-control input-sm"  value="{{old('address')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Country<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <input type="text" name="country" id="country" placeholder="Country" class="form-control input-sm"  value="{{old('country')}}" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Payment Type<span class="error">*</span></label>
                                    <div class="col-sm-6 col-md-4">
                                        <select name="payment_type" class="form-control input-sm required" id="payment_type">
                                            <option value="">{{trans('app.select')}}</option>
                                            <option value="Online Payment">Online Payment</option>
                                            <option value="Bank Payment">Bank Payment</option>
                                            <option value="Cash On Hand">Cash On Hand</option>
                                            <option value="Cash Deposit">Cash Deposit</option>
                                        </select>
                                    </div>
                                </div>

                                {{ csrf_field() }}

                                <div class="col-sm-6 col-md-8 savebtn">
                                    <p class="text-right">
                                        <button type="submit" class="btn btn-space btn-info btn-lg">{{trans('app.add')}} Payment</button>
                                        <a href="{{url('/overseas/list')}}" class="btn btn-space btn-danger btn-lg">Cancel</a>
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
