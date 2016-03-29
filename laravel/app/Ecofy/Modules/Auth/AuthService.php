<?php

namespace App\Ecofy\Modules\Auth;

use DateTime;
use Log;
use DB;

use \Ramsey\Uuid\Uuid;
use \Firebase\JWT\JWT;

use App\Ecofy\Support\ObjectAccessor;
use App\Ecofy\Support\EcoCriteriaBuilder;
use App\Ecofy\Support\AbstractResourceService;

// Models
use App\Ecofy\Modules\Account\Account;
use App\Ecofy\Modules\Account\Profile;

use App\Ecofy\Modules\Auth\AuthServiceContract;


class AuthService extends AbstractResourceService
    implements AuthServiceContract
{
    protected $accountService = null;

    public function __construct() {
		parent::__construct('\\App\\Ecofy\\Modules\\Auth\\Auth',['account']);
	}

    public function name()
    {
        return 'AuthService';
    }

    public function getAccountService()
    {
        if ( $this->accountService == null) {
            $this->accountService = \App::make('App\Ecofy\Modules\Account\AccountServiceContract');
        }
        return $this->accountService;
    }

    /**
     * Returns a new instance of account model
     */
    public function newAuth($array)
    {
        $model = new Auth($array);
        $model->createdAt = new DateTime();
        $model->status = 1;

        return $model;
    }

    /**
     * authenticate
     * @param Model\Auth $auth
     */
    public function authenticate($auth)
    {
        Log::info('Parsed $auth:' . print_r($auth, true));
        if ($auth->authSource == 'local') {
            $criteria = EcoCriteriaBuilder::conj(
                    [EcoCriteriaBuilder::comparison('authSource', '=', $auth->authSource),
                    EcoCriteriaBuilder::comparison('username', '=', $auth->username)]
                );
        } else {
            $criteria = EcoCriteriaBuilder::conj(
                    [EcoCriteriaBuilder::comparison('authSource', '=', $auth->authSource),
                    EcoCriteriaBuilder::comparison('authId', '=', $auth->authId)]
                );
        }
        $auth = $this->find($criteria);

        $retval = $this->login($auth);

        return $retval;
    }

    /**
     * Create account, profile and auth within transaction
     *
     * @param array $modesl - Array with account, profile and auth models
     * @return
     */
    public function createAccountAndAuth($models)
    {
        $accountModel = $models['account'];
        $profileModel = $models['profile'];
        $authCredential = $models['auth'];

        // Check if account already exists with same account->primaryEmail
        $matchingAccount = $this->getAccountService()->findByEmail($accountModel->primaryEmail);
        if (!empty($matchingAccount)) {
            $accountModel = $matchingAccount;
        }

        DB::transaction(function () use($accountModel, $profileModel, $authCredential) {
            if (empty($accountModel->uuid))
                $accountModel->uuid = Uuid::uuid4();
            $accountModel->save();
            $profileModel->accountUuid = $accountModel->uuid;

            if (empty($profileModel->uuid))
                $profileModel->uuid = Uuid::uuid4();
            $profileModel->save();
            $authCredential->accountUuid = $accountModel->uuid;

            if (empty($authCredential->uuid))
                $authCredential->uuid = Uuid::uuid4();
            $authCredential->save();
            $authCredential->account = $accountModel;
        });
        return $authCredential;
    }

    /**
     * Logs in the authenticated user
     * @param Model\Auth $auth the authenticated user with account property set
     *
     * @return array  - Array with ['auth' => auth, 'token' => <JWT token>]
     */
    public function login($auth)
    {
        if (empty($auth)) {
            Log::info('Login failure');
            return FALSE;
        }

        $this->getAccountService()->touchLastLogin($auth->account);

        $retval = [
            'auth' => $auth,
            // @todo: generate JWT token
            // @see https://scotch.io/tutorials/token-based-authentication-for-angularjs-and-laravel-apps
            'token' => $this->encodeToken($auth->account),
        ];
        Log::info('Loggedin (AuthAndToken):' . print_r($retval, true));

        return $retval;
    }

    /**
     * encode JWT token
     */
    public function encodeToken($account)
    {
        $data = [
            'id' => $account->uuid,
            'kind' => $account->kinds,
            'roles' => $account->roles,
            'displayName' => $account->displayName,
        ];
        $jwt = JWT::encode(
            $data,      //Data to be encoded in the JWT
            env('JWT_SECRET_KEY', 'SuperSecret'), // The signing key
            'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
        );

        return $jwt;
    }

    public function decodeToken($token)
    {
        $decoded = JWT::decode($token, env('JWT_SECRET_KEY', 'SuperSecret'), array('HS512'));
        return $decoded;
    }


}
