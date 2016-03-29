<?php

use Illuminate\Database\Seeder;

use App;

// Models
use App\Account;
use App\Auth;
use App\Profile;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authService = App::make('App\Ecofy\Modules\Auth\AuthServiceContract');

        $accountModel = new Account();
        $accountModel->kind: 'root';
        //$accountModel->roles: ['root'];
        $accountModel->status: 'enabled';
        $accountModel->displayName: 'Root';

        $profileModel = new Profile();
        $profileModel->faimlyName = 'Host';
        $profileModel->givenName = 'Root';

        $authCredential = new Auth();
        $authCredential->authSource: 'local';
        $authCredential->authId: 'root';
        $authCredential->username: 'root';
        $authCredential->security_password = 'root';

        $authService->createAccount($accountModel, $profileModel, $authCredential);
    }
}
