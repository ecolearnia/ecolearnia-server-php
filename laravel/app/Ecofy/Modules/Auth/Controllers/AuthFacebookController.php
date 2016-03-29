<?php

namespace App\Ecofy\Modules\Auth\Controllers;

use Log;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Socialite;

use App\Ecofy\Support\AbstractAuthSocialController;
use App\Ecofy\Support\ObjectAccessor;
use App\Ecofy\Modules\Auth\Auth;
use App\Ecofy\Modules\Account\Account;
use App\Ecofy\Modules\Account\Profile;

// Not really required
//use Facebook\Facebook;

// Ecofy service
use App\Ecofy\Modules\Auth\AuthServiceContract;


/**
 * For Socialite documentation
 * @see: https://github.com/laravel/socialite
 */
class AuthFacebookController extends AbstractAuthSocialController
{
    public static $FIELDS = ['name', 'email', 'gender', 'verified', 'about'
        , 'first_name', 'last_name', 'middle_name'
        , 'bio', 'birthday', 'education', 'interested_in', 'languages'
        , 'location', 'locale', 'name_format', 'timezone', 'updated_time'
        , 'website', 'work', 'cover'];

    public function __construct() {
		parent::__construct('facebook', self::$FIELDS);
	}

    function buildAuthModel($authService, $oauthUser)
    {
        $authData['authSource'] = $this->driver;
        $authData['authId'] = $oauthUser->id;
        $authData['authCredentialsRaw'] = json_encode($oauthUser->user);
        $authData['status'] = 1;
        $authData['rememberToken'] = $oauthUser->token;
        $authData['security_password'] = null;
        $authData['security_activationCode'] = null;
        $authData['security_securityQuestion'] = null;
        $authData['security_securityAnswer'] = null;

        return $authService->newAuth($authData);
    }

    function buildAccountModel($authService, $oauthUser)
    {
        $accountData['kind'] = 'basic';
        // $accountData['roles'] = null;
        $accountData['status'] = 'registered';
        $accountData['displayName'] = $oauthUser->name;
        $accountData['primaryEmail'] = $oauthUser->email;
        $accountData['imageUrl'] = $oauthUser->avatar_original;

        return $authService->getAccountService()->newAccount($accountData);
    }

    function buildProfileModel($authService, $oauthUser)
    {
        $profileModel = new Profile();
        $profileModel->familyName = ObjectAccessor::get($oauthUser->user, 'last_name');
        $profileModel->givenName = ObjectAccessor::get($oauthUser->user, 'first_name');
        $profileModel->highlight = ObjectAccessor::get($oauthUser->user, 'about', null);
        $profileModel->gender = ObjectAccessor::get($oauthUser->user, 'gender', null);
        $profileModel->language = ObjectAccessor::get($oauthUser->user, 'locale', null);
        $profileModel->timezone = ObjectAccessor::get($oauthUser->user, 'timezone', null);

        return $profileModel;
    }

}
