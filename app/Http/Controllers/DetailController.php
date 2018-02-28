<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Detail;
use Validator;
use DB;

class DetailController extends Controller
{

    protected $detail;
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Detail $detail)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->detail = $detail;
    }


    /**
     * Show the form for creating a new detail.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['detailManagementTab'] = "active open";
        $data['detailTab'] = "active";
        $current_detail = $this->detail->getFirstDetail();
        if(count($current_detail) > 0) {
            $data['savedPrize'] = $current_detail->saved_prize;
            $data['pageTitle'] = $current_detail->page_title;
        }else {
            $data['pageTitle'] = '';
            $data['savedPrize'] = '0';
        }
        return view('detail.add', $data);
    }

    
    /**
     * Validation of add and edit action customeValidate
     *
     * @param array $data
     * @param string $mode
     * @return mixed
     */
    public function customeValidate($data, $mode)
    {

        if ($mode == "title") {
            $rules['page_title'] = 'required';
        }
        if ($mode == "prize") {
            $rules['saved_prize'] = 'required';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "detail/add";
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created detail in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeTitle(request $request)
    {

        $validations = $this->customeValidate($request->all(), 'title');
        if ($validations) {
            return $validations;
        }

        // Start Communicate with database
        DB::beginTransaction();
        try{
            $adddetail = $this->detail->addDetail($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('detail/add')->withInput();

        }
        if ($adddetail) {
            //Event::fire(new SendMail($adddetail));
            $request->session()->flash('alert-success', __('app.default_add_success',["module" => __('app.detail')]));
            return redirect('detail/add');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.detail'),"action"=>__('app.add')]));
            return redirect('detail/add')->withInput();
        }
    }

    /**
     * Store a newly created detail in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function storePrize(request $request)
    {

        $validations = $this->customeValidate($request->all(), 'prize');
        if ($validations) {
            return $validations;
        }

        // Start Communicate with database
        DB::beginTransaction();
        try{
            $adddetail = $this->detail->addSavedPrize($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('detail/add')->withInput();

        }
        if ($adddetail) {
            //Event::fire(new SendMail($adddetail));
            $request->session()->flash('alert-success', __('app.default_add_success',["module" => __('app.detail')]));
            return redirect('detail/add');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.detail'),"action"=>__('app.add')]));
            return redirect('detail/add')->withInput();
        }
    }



}
