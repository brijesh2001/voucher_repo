<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Detail;
use App\Models\Prize;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\Models\State;
use Mail;
use App\Mail\SuccessMail;
use Kyranb\Footprints\TrackRegistrationAttribution;
use Spatie\Sitemap\SitemapGenerator;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers,TrackRegistrationAttribution;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Override login athentication method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */

    public function login(Request $request)
    {

        $this->validate($request, ['email' => 'required', 'password' => 'required'] );

        $remember = false;
        if($request->remember == '1'){
            $remember = true;
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password,'status' => 1],$remember)) {

            $request->session()->flash('alert-success', trans('app.user_login_success'));
            return redirect('dashboard');
        }else{
            $request->session()->flash('alert-danger', trans('app.user_login_error'));
            return redirect('login')->withInput($request->except('password'));
        }
    }

    /**
     * Index front page
     *
     * @return view
     */
    public function welcome(Request $request)
    {
       $user_id = $request->all();
        if(isset($user_id['user_id']) && !empty($user_id['user_id'])) {
            $state_model = new State();
            $data['state'] = $state_model->getCollection();
            $data['user_id'] = $user_id['user_id'];
            $detail_model = new Detail();
            //For saved prize and detail
            $data_saved_prize = $detail_model->getData();
            if(!empty($data_saved_prize)) {
                $data['title'] = $data_saved_prize->page_title;
                $data['saved_prize'] = floor($data_saved_prize->saved_prize);
            }
            $user_detail = new Agent();
            $user_data = $user_detail->getAgentByField($user_id['user_id'],'id');
            if(!empty($user_data)) {
                $data['rate'] =   floor($user_data->amount);
                $data['email'] =   $user_data->email;
                $data['name'] =   $user_data->name;
                $data['mobile'] =   $user_data->mobile;
            }

        }else {
            $state_model = new State();
            $data['state'] = $state_model->getCollection();
            $detail_model = new Detail();
            //For saved prize and detail
            $data_saved_prize = $detail_model->getData();
            if(!empty($data_saved_prize)) {
                $data['title'] = $data_saved_prize->page_title;
                $data['saved_prize'] = $data_saved_prize->saved_prize;
            }
            //For prize
            $prize_model = new Prize();
            $prize_data = $prize_model->getFirstPrize();
            if(!empty($prize_data)) {
                $data['rate'] =   floor($prize_data->rate);
            }
        }

        return view('front.index',$data);
    }
    public function hello()
    {
        return view('test');
    }




    /**
     * refundPolicy view page
     *
     */
    public function refundPolicy()
    {
        return view('front.refund_policy');
    }

    /**
     * contactUs view page
     *
     */
    public function contactUs()
    {
        return view('front.contact_us');
    }

    /**
     * contactUs view page
     *
     */
    public function infographics()
    {
        return view('front.infographics');
    }
    /**
     * contactUs view page
     *
     */
    public function aboutUs()
    {
        return view('front.about_us');
    }


    /**
     * contactUs view page
     *
     */
    public function sendQuery(Request $request)
    {
        $request_data = $request->all();
        Mail::send(new SuccessMail($request_data));
        $request->session()->flash('alert-success','Thanks admin will contact you within 24 hours.');
        return redirect('/');
    }

    /**
     * contactUs view page
     *
     */
    public function thankYou()
    {
        return view('front.thankyou');
    }

    /**
     * contactUs view page
     *
     */
    public function buy(Request $request)
    {
        $user_id = $request->all();
        if(isset($user_id['user_id']) && !empty($user_id['user_id'])) {
            $state_model = new State();
            $data['state'] = $state_model->getCollection();
            $data['user_id'] = $user_id['user_id'];
            $detail_model = new Detail();
            //For saved prize and detail
            $data_saved_prize = $detail_model->getData();
            if(!empty($data_saved_prize)) {
                $data['title'] = $data_saved_prize->page_title;
                $data['saved_prize'] = floor($data_saved_prize->saved_prize);
            }
            $user_detail = new Agent();
            $user_data = $user_detail->getAgentByField($user_id['user_id'],'id');
            if(!empty($user_data)) {
                $data['rate'] =   floor($user_data->amount);
                $data['email'] =   $user_data->email;
                $data['name'] =   $user_data->name;
                $data['mobile'] =   $user_data->mobile;
            }

        }else {
            $state_model = new State();
            $data['state'] = $state_model->getCollection();
            $detail_model = new Detail();
            //For saved prize and detail
            $data_saved_prize = $detail_model->getData();
            if(!empty($data_saved_prize)) {
                $data['title'] = $data_saved_prize->page_title;
                $data['saved_prize'] = $data_saved_prize->saved_prize;
            }
            //For prize
            $prize_model = new Prize();
            $prize_data = $prize_model->getFirstPrize();
            if(!empty($prize_data)) {
                $data['rate'] =   floor($prize_data->rate);
            }
        }

        return view('front.buy',$data);
    }

    public function sitemap()
    {
        $variable = SitemapGenerator::create('host.voucher.com')->writeToFile(storage_path().DIRECTORY_SEPARATOR.'logs');
    }
}
