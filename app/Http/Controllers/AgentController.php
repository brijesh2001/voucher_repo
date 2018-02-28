<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Mail;
use App\Mail\SuccessMail;

class AgentController extends Controller
{

    protected $agent;
    protected $role;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Agent $agent, Role $role)
    {
        $this->middleware(['auth', 'checkRole']);
        $this->agent = $agent;
        $this->role = $role;
    }

    /**
     * Display a listing of the agent.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * getCollection from App/Models/Agent
         *
         * @return mixed
         */
        $data['agentData'] = $this->agent->getCollection();
        $data['agentManagementTab'] = "active open";
        $data['agentTab'] = "active";
        return view('agent.agentlist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of agent $agentCount
        $agentCount = 0;

        /**
         * getDatatableCollection from App/Models/Agent
         * get all agents
         *
         * @return mixed
         */
        $agentData = $this->agent->getDatatableCollection();

        /**
         * scopeGetFilteredData from App/Models/Agent
         * get filterred agents
         *
         * @return mixed
         */
        $agentData = $agentData->GetFilteredData($request);

        /**
         * getAgentCount from App/Models/Agent
         * get count of agents
         *
         * @return integer
         */
        $agentCount = $this->agent->getAgentCount($agentData);

        // Sorting agent data base on requested sort order
        if (isset(config('constant.agentDataTableFieldArray')[$request->order['0']['column']])) {
            $agentData = $agentData->SortAgentData($request);
        } else {
            $agentData = $agentData->SortDefaultDataByRaw('tbl_agent.id', 'desc');
        }

        /**
         * get paginated collection of agent
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         */
        $agentData = $agentData->GetAgentData($request);
        $appData = array();
        $i = 1;
        foreach ($agentData as $agentData) {
            $row = array();
            $row[] = $i;
            $row[] = $agentData->name;
            $row[] = $agentData->email;
            $row[] = $agentData->mobile;
            $row[] = $agentData->amount;
            $row[] = view('datatable.action', ['module' => "agent",'type' => $agentData->id, 'id' => $agentData->id])->render();
            $row[] = view('datatable.send', ['module' => "agent",'type' => $agentData->id, 'id' => $agentData->id])->render();
            $appData[] = $row;
            $i++;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $agentCount,
            'recordsFiltered' => $agentCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new agent.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['agentManagementTab'] = "active open";
        $data['agentTab'] = "active";
        $data['agentData'] = $this->agent->getCollection();
        $data['roleData'] = $this->role->getCollection();
        return view('agent.add', $data);
    }

    /**
     * Display the specified agent.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        /**
         * get details of the specified agent. from App/Models/Agent
         *
         * @param mixed $id
         * @param string (id) fieldname
         * @return mixed
         */
        $data['details'] = $this->agent->getAgentByField($id, 'id');
        $data['agentData'] = $this->agent->getCollection();
        $data['roleData'] = $this->role->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['agentTab'] = "active";
        return view('agent.edit', $data);
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
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'amount' => 'required',
        );

        if ($mode == 'add' || $mode == 'edit') {
            $rules = array(
                'name' => 'required',
                'email' => 'required',
                'mobile' => 'required',
                'amount' => 'required',
            );
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "agent/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "agent/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created agent in storage.
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
            $addagent = $this->agent->addAgent($request->all());

            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('agent/add')->withInput();

        }
        if ($addagent) {
            //Event::fire(new SendMail($addagent));
            $request->session()->flash('alert-success','Entry added successfully');
            return redirect('agent/list');
        } else {
            $request->session()->flash('alert-danger','Error occured while adding entry');
            return redirect('agent/add')->withInput();
        }
    }

    /**
     * Update the specified agent in storage.
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
            $addagent = $this->agent->addAgent($request->all());
            DB::commit();
        } catch (\Exception $e) {
            //exception handling
            DB::rollback();
            $errorMessage = '<a target="_blank" href="https://stackoverflow.com/search?q='.$e->getMessage().'">'.$e->getMessage().'</a>';
            $request->session()->flash('alert-danger', $errorMessage);
            return redirect('agent/edit/' . $request->get('id'))->withInput();

        }

        if ($addagent) {
            $request->session()->flash('alert-success','Entry edited successfully');
            return redirect('agent/list');
        } else {
            $request->session()->flash('alert-danger','Error occured while adding entry');
            return redirect('agent/edit/' . $request->get('id'))->withInput();
        }
    }


    /**
     * Delete the specified agent in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {
        $deleteAgent = $this->agent->deleteAgent($request->id);
        if ($deleteAgent) {
            $request->session()->flash('alert-success',"Entry deleted successfully");
        } else {
            $request->session()->flash('alert-danger','Error occured while deleting entry');
        }
        echo 1;
    }

    /**
     * Delete the specified agent in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function sendMail(request $request)
    {
        $request_data = $request->all();
        $id = $request_data['id'];
        $data = $this->agent->getAgentByField($id, 'id');
        if(!empty($data)) {
            $agent_data['type'] = 'agent_mail';
            $agent_data['id'] = $id;
            $agent_data['name'] = $data->name;
            $agent_data['amount'] = $data->amount;
            $agent_data['email'] = $data->email;
            Mail::send(new SuccessMail($agent_data));
            return [
                'statusCode' => 1
            ];
        }else {
            return [
                'statusCode' => 0
            ];
        }


    }



}
