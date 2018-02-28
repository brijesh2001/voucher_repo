<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
use Event;
use Hash;
use App\Events\SendMail;
use DB;

class PromoController extends Controller
{

    protected $promo;
    protected $role;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Promo $promo, Role $role)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->promo = $promo;
        $this->role = $role;
    }

    /**
     * Display a listing of the promo.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * getCollection from App/Models/Promo
         *
         * @return mixed
         */
        $data['promoData'] = $this->promo->getCollection();
        $data['promoManagementTab'] = "active open";
        $data['roleData'] = $this->role->getCollection();
        $data['promoTab'] = "active";
        return view('promo.promolist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of promo $promoCount
        $promoCount = 0;

        /**
         * getDatatableCollection from App/Models/Promo
         * get all promos
         *
         * @return mixed
         */
        $promoData = $this->promo->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/Promo
         * get filterred promos
         *
         * @return mixed
         */
        $promoData = $promoData->GetFilteredData($request);

        /**
         * getPromoCount from App/Models/Promo
         * get count of promos
         *
         * @return integer
         */
        $promoCount = $this->promo->getPromoCount($promoData);

        // Sorting promo data base on requested sort order
        if (isset(config('constant.promoDataTableFieldArray')[$request->order['0']['column']])) {
            $promoData = $promoData->SortPromoData($request);
        } else {
            $promoData = $promoData->SortDefaultDataByRaw('tbl_promo_voucher.id', 'desc');
        }

        /**
         * get paginated collection of promo
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */
        $promoData = $promoData->GetPromoData($request);
        $appData = array();
        $i = 1;
        foreach ($promoData as $promoData) {
            $row = array();
            $row[] = $i;
            $row[] = $promoData->voucher_code;
            $row[] = view('datatable.action', ['module' => "voucher",'type' => $promoData->id, 'id' => $promoData->id])->render();
            $appData[] = $row;
            $i++;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $promoCount,
            'recordsFiltered' => $promoCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new promo.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['promoManagementTab'] = "active open";
        $data['promoTab'] = "active";
        $data['promoData'] = $this->promo->getCollection();
        $data['roleData'] = $this->role->getCollection();
        return view('promo.add', $data);
    }

    /**
     * Display the specified promo.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        /**
         * get details of the specified promo. from App/Models/Promo
         *
         * @param mixed $id
         * @param string (id) fieldname
         * @return mixed
         */
        $data['details'] = $this->promo->getPromoByField($id, 'id');
        $data['promoData'] = $this->promo->getCollection();
        $data['roleData'] = $this->role->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['promoTab'] = "active";
        return view('promo.edit', $data);
    }

    /**
     * Display the specified promo profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        /**
         * get details of the specified promo. from App/Models/Promo
         *
         * @param mixed $id
         * @param string (id) fieldname
         * @return mixed
         */
        $data['profileTab'] = "active";
        $data['details'] = $this->promo->getPromoByField(Auth::promo()->id, 'id');
        return view('promo.profile', $data);
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
            'voucher_code' => 'required',
        );

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "voucher/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "voucher/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created promo in storage.
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
            $request_voucher_data = $request->all();

            $voucher = explode("\r\n",$request_voucher_data['voucher_code']);

           foreach ($voucher as $vocher_id) {
               $request_data['voucher_code'] = $vocher_id;
               $addpromo = $this->promo->addPromo($request_data);
           }

            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('voucher/add')->withInput();

        }
        if ($addpromo) {
            //Event::fire(new SendMail($addpromo));
            $request->session()->flash('alert-success', __('app.default_add_success',["module" => __('app.voucher')]));
            return redirect('voucher/list');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.voucher'),"action"=>__('app.add')]));
            return redirect('voucher/add')->withInput();
        }
    }

    /**
     * Update the specified promo in storage.
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
            $addpromo = $this->promo->addPromo($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('voucher/edit/' . $request->get('id'))->withInput();

        }

        if ($addpromo) {


            $request->session()->flash('alert-success', __('app.default_edit_success',["module" => __('app.voucher')]));
            return redirect('voucher/list');
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.voucher'),"action"=>__('app.update')]));
            return redirect('voucher/edit/' . $request->get('id'))->withInput();
        }
    }

    /**
     * Update status to the specified promo in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(request $request)
    {
        $updatePromo = $this->promo->updateStatus($request->all());
        if ($updatePromo) {
            $request->session()->flash('alert-success', __('app.default_status_success',["module" => __('app.promo')]));
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.promo'),"action"=>__('app.change_status')]));
        }
        echo 1;
    }

    /**
     * Delete the specified promo in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {
        $deletePromo = $this->promo->deletePromo($request->id);
        if ($deletePromo) {
            $request->session()->flash('alert-success', __('app.default_delete_success',["module" => __('app.voucher')]));
        } else {
            $request->session()->flash('alert-danger', __('app.default_error',["module" => __('app.voucher'),"action"=>__('app.delete')]));
        }
        echo 1;
    }



}
