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


// Ecofy service
use App\Ecofy\Modules\Auth\AuthServiceContract;


/**
 * For Socialite documentation
 * @see: https://github.com/laravel/socialite
 */
class AuthGoogleController extends AbstractAuthSocialController
{
    public function __construct() {
		parent::__construct('google');
	}

    public function buildAuthModel($authService, $oauthUser)
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

    public function buildAccountModel($authService, $oauthUser)
    {
        $accountData['kind'] = 'basic';
        // $accountData['roles'] = null;
        $accountData['status'] = 'registered';
        $accountData['displayName'] = $oauthUser->user['displayName'];
        $accountData['primaryEmail'] = $oauthUser->email;
        $accountData['imageUrl'] = ObjectAccessor::get($oauthUser->user, 'image.url');

        return $authService->getAccountService()->newAccount($accountData);
    }

    public function buildProfileModel($authService, $oauthUser)
    {
        $profileData['familyName'] = ObjectAccessor::get($oauthUser->user, 'name.familyName');
        $profileData['givenName'] = ObjectAccessor::get($oauthUser->user, 'name.givenName');
        $profileData['highlight'] = ObjectAccessor::get($oauthUser->user, 'braggingRights', null);
        $profileData['gender'] = ObjectAccessor::get($oauthUser->user, 'gender', null);
        $profileData['language'] = $oauthUser->user['language'];

        return $authService->getAccountService()->newProfile($profileData);
    }

}
