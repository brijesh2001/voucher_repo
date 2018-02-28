<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Prize;
use Validator;
use DB;

class PrizeController extends Controller
{

    protected $prize;
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Prize $prize)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->prize = $prize;
    }


    /**
     * Show the form for creating a new prize.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['prizeManagementTab'] = "active open";
        $data['prizeTab'] = "active";
        $current_prize = $this->prize->getFirstPrize();
        if(count($current_prize) > 0) {
            $data['currentPize'] = $current_prize->rate;
        }else {
            $data['currentPize'] = 0;
        }
        return view('prize.add', $data);
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
        $rules = array(
            'rate' => 'required',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "prize/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "prize/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created prize in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {

        $validations = $this->customeValidate($request->all(), 'add');
        if ($validations) {
            return $validations;
        }

        // Start Communicate with database
        DB::beginTransaction();
        try{
            $addprize = $this->prize->addPrize($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('prize/add')->withInput();

        }
        if ($addprize) {
            //Event::fire(new SendMail($addprize));
            $request->session()->flash('alert-success', __('app.default_add_success',["module" => __('app.prize')]));
            return redirect('prize/add');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.prize'),"action"=>__('app.add')]));
            return redirect('prize/add')->withInput();
        }
    }

    /**
     * Update the specified prize in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(request $request)
    {
        $validations = $this->customeValidate($request->all(), 'edit');
        if ($validations) {
            return $validations;
        }

        // Start Communicate with database
        DB::beginTransaction();
        try{
            $addprize = $this->prize->addPrize($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('prize/edit/' . $request->get('id'))->withInput();

        }

        if ($addprize) {


            $request->session()->flash('alert-success', __('app.default_edit_success',["module" => __('app.prize')]));
            return redirect('prize/list');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.prize'),"action"=>__('app.update')]));
            return redirect('prize/edit/' . $request->get('id'))->withInput();
        }
    }


}
