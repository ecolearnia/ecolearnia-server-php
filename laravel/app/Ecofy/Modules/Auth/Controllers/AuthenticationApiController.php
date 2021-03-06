<?php

namespace App\Ecofy\Modules\Auth\Controllers;

use \Exception;
use Log;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

// Ecofy service
use App\Ecofy\Support\ObjectAccessor;
use App\Ecofy\Modules\Auth\AuthServiceContract;

use App\Ecofy\Modules\Auth\Auth;
use App\Ecofy\Modules\Account\Account;
use App\Ecofy\Modules\Account\Profile;

use App\Ecofy\Support\AbstractBaseController;



/**
 * Authentication API controller
 */
class AuthenticationApiController extends AbstractBaseController
{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function myaccount()
    {
        $account = \Auth::user();
        return \Response::json($account);
    }

    /**
     * Signs in a user with local authentication
     */
    public function signup(Request $request, AuthServiceContract $authService)
    {
        $payload = $request->all();

        $models = [];

        // @todo: use authService::createModel()
        $authCredentials = new Auth();
        $authCredentials->authSource = 'local';
        $authCredentials->authId = '-NONE-'; // only applicable for social network
        $authCredentials->username = ObjectAccessor::get($payload, 'auth.username');
        $authCredentials->security_password = ObjectAccessor::get($payload, 'auth.security.password');
        $models['auth'] = $authCredentials;

        // @todo: use AccountService::createModel()
        $profileModel = new Profile();
        $profileModel->givenName = ObjectAccessor::get($payload, 'profile.givenName');
        $profileModel->familyName = ObjectAccessor::get($payload, 'profile.familyName');
        $profileModel->gender = ObjectAccessor::get($payload, 'profile.gender');
        $emails = ObjectAccessor::get($payload, 'profile.emails');
        $profileModel->dob = new \DateTime(ObjectAccessor::get($payload, 'profile.dob'));
        $models['profile'] = $profileModel;
        Log::info('Parsed $profileModel:' . print_r($profileModel, true));

        $accountModel = new Account();
        $accountModel->kind = 'basic';
        //$accountModel->roles = ;
        $accountModel->status = 'signedup';
        $accountModel->primaryEmail = $emails[0];
        $accountModel->displayName = $profileModel->givenName . ' ' . $profileModel->familyName;
        $models['account'] = $accountModel;
        Log::info('Parsed $accountModel:' . print_r($accountModel, true));

        try {
            $auth = $authService->createAccountAndAuth($models);
            $authAndToken = $authService->login($auth);
            return $this->jsonResponse($authAndToken, 200);
        } catch (Exception $e) {
            return $this->jsonResponse(array('error' => $e->getMessage()), 500);
        }
        //return $this->jsonResponse($authAndToken, 200);
        //return json_encode($authAndToken);
    }

    /**
     * Signs in a user with local authentication
     */
    public function signin(Request $request, AuthServiceContract $authService)
    {
        $payload = $request->json();

        $authCredentials = new Auth();
        $authCredentials->authSource = 'local';
        $authCredentials->authId = '-NONE-'; // only applicable for social network
        $authCredentials->username = $payload->get('username');
        $authCredentials->password = $payload->get('password');

        $authAndToken = $authService->authenticate($authCredentials);

        return $this->jsonResponse($authAndToken, 200);
        //return json_encode($authAndToken);
    }

    public function signout(AuthServiceContract $authService)
    {
        // Not much to do as the client-side is responsible of destroying the cookie

        return $this->jsonResponse(['ok' => true], 200);
        //return json_encode(['ok' => true]);
    }


}
