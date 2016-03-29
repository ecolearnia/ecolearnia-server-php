<?php

namespace App\Ecofy\Middleware;

use Closure;

use App\Ecofy\Modules\Auth\AuthServiceContract;
use App\Ecofy\Modules\Account\AccountServiceContract;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isAjax = $request->ajax();
        $ecofyToken = $request->cookie('ecofy_token');
        $authorization = $request->header('Authorization');

        $authService = \App::make(AuthServiceContract::class);
        $accountService = \App::make(AccountServiceContract::class);

        if (empty($ecofyToken)) {
            $ecofyToken = $authorization;
        }

        $account = null;
        if (!empty($ecofyToken)) {
            $decodedToken = $authService->decodeToken($ecofyToken);
            $account = $accountService->findByPK($decodedToken->id);
            if (!empty($account)) {
                \Auth::login($account);
            }


            /*
            print('---DECODED---');
            print_r($decodedToken);
            print_r($account);
            die();
            */
        }

        if (empty($account)) {
            if ($request->ajax()) {
                return response('Unauthorized', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        return $next($request);
    }
}
