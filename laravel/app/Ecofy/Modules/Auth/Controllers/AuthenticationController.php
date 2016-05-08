<?php

namespace App\Ecofy\Modules\Auth\Controllers;

use Log;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use App\Ecofy\Modules\Auth\Auth;
use App\Ecofy\Modules\Account\Account;
use App\Ecofy\Modules\Account\Profile;
use App\Ecofy\Modules\Auth\AuthServiceContract;


class AuthenticationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    private $authService;

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(AuthServiceContract $authService)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->authService = $authService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Show the public landing page.
     *
     * @param  int  $id
     * @return Response
     */
    public function signinForm()
    {
        return view('signin');
    }

    public function signin()
    {
        if ($request->isMethod('get')) {
            return $this->signinForm();
        }
    }

    public function signupForm()
    {
        return view('signup');
    }

    public function signup(Request $request)
    {
        if ($request->isMethod('get')) {
            return $this->signupForm();
        }
        /*
        $this->validate($request, [
            'givenName' => 'required',
            'familyName' => 'required',
            'email' => 'required|unique',
        ]);
        */

        $returnUrl = $request->input('return_url', '/portal');

        print_r($request->all());
        print_r($returnUrl);
        die();

        $models = [];

        // @todo: use authService::createModel()
        $authCredentials = new Auth();
        $authCredentials->authSource = 'local';
        $authCredentials->authId = '-NONE-'; // only applicable for social network
        $authCredentials->username = $request->input('username');
        $authCredentials->security_password = $request->input('password');
        $models['auth'] = $authCredentials;

        // @todo: use AccountService::createModel()
        $profileModel = new Profile();
        $profileModel->givenName = $request->input('givenName');
        $profileModel->familyName = $request->input('familyName');
        $profileModel->gender = $request->input('gender');
        $emails = $request->input('email');
        $profileModel->dob = new \DateTime($request->input('dob'));
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

        $auth = $this->authService->createAccountAndAuth($models);
        $authAndToken = $this->authService->login($auth);

        $minutes = 365 * 24 * 60;
        $secure = false;
        $httpOnly = false;
        $path = '/';
        $domain = null;
        return redirect($returnUrl)
            ->withCookie('ecofy_token', $authAndToken['token']
                , $minutes , $path, $domain, $secure, $httpOnly);
    }


    /**
     * Returns cookie with expirated empty token
     *
     */
    protected function signout()
    {
        $redirUrl = !empty($request['redir_url']) ? $request['redir_url'] : '/portal' ;

        $minutes = -1;
        $secure = false;
        $httpOnly = false;
        $path = '/';
        $domain = null;
        return redirect($redirUrl)
            ->withCookie('ecofy_token', null
                , $minutes , $path, $domain, $secure, $httpOnly);
    }

}
