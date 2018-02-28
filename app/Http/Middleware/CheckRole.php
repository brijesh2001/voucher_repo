<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\LaraHelpers;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
         * variable array $route_action
         * variable array $permissions
         *  in $route_action has Permission key. permission key has a current route role.
         *  $permissions has users access permission. here we check current route is related
         *  to user permission or not
         * */
        $route_action = $request->route()->getAction();
        $permissions = LaraHelpers::GetUserPermissions();
        if (isset($route_action['Permission'])) {
            if (!in_array($route_action['Permission'], $permissions)) {
                $request->session()->flash('alert-danger', trans('ims.access-denied'));
                return redirect('dashboard');
            }
        }

        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin' , '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');

        return $response;
    }
}
