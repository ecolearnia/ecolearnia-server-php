<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\EcoCriteriaBuilder;
use App\Ecofy\Modules\Account\AccountService;

use App\Ecofy\Modules\Account\Profile;
use App\Ecofy\Modules\Auth\Auth;

class AccountServiceTest extends TestCase
{

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateModel()
    {
        $svc = new AccountService();
        $input = self::createAccountInput();
        $model = $svc->createModel($input);

        $this->assertTrue(!empty($model), 'Account model empty');

        //print_r($model->toArray());

        $this->assertTrue(empty($model->uuid), 'account uuid is not empty');
        $this->assertTrue(!empty($model->profile), 'account->profile is empty');
        $this->assertTrue(empty($model->profile->uuid), 'account->profile uuid is not empty');
        $this->assertTrue(!empty($model->auth), 'account->auth is empty');
        $this->assertTrue(empty($model->auth->uuid), 'account->auth uuid is not empty');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewModel()
    {
        $svc = new AccountService();
        $input = self::createAccountInput();
        $model = $svc->createNewModel($input);

        $this->assertTrue(!empty($model), 'Account model empty');

        //print_r($model->toArray());

        $this->assertTrue(!empty($model->uuid), 'account uuid is empty');
        $this->assertTrue(!empty($model->profile), 'account->profile is empty');
        $this->assertTrue(!empty($model->profile->uuid), 'account->profile uuid is empty');
        $this->assertTrue(!empty($model->auth), 'account->auth is empty');
        $this->assertTrue(!empty($model->auth->uuid), 'account->auth uuid is empty');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testAddFindRemoveModel()
    {
        $svc = new AccountService();
        $model = self::addTestAccount($svc);

        $result = $svc->findByPK($model->uuid);

        $this->assertTrue(!empty($result), 'Retrieve after add failed');
        $this->assertTrue(!empty($result->profile), 'model->profile is empty');
        $this->assertTrue(!empty($result->auths), 'model->auths is empty');

        $count = $svc->removeByPK($model->uuid);

        $this->assertEquals(1, $count, 'Failed to remove one');

        $result2 = $svc->findByPK($model->uuid);
        $this->assertEmpty($result2, 'Account still exists after removal');

        $profileCnt = Profile::where('uuid', '=', $result->profile->uuid)->count();
        $this->assertEquals(0, $profileCnt, 'Profile still exists after account removal');

    }


    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testUpdateAccount()
    {
        $svc = new AccountService();
        $model = self::addTestAccount($svc);

        $newData = self::createAccountInput();
        $newData['displayName'] = 'TestUpdated';
        $newData['profile']['familyName'] = 'UtestUpdated';

        $svc->update($model->uuid, $newData);

        $retrieved = $svc->findByPK($model->uuid);


        $expected = json_encode($newData, JSON_PRETTY_PRINT);
        $actual = $retrieved->toJson(JSON_PRETTY_PRINT);

        //$this->assertJsonStringEqualsJsonString($expected, $actual);
        $this->assertEquals($newData['displayName'] , $retrieved->displayName);
        $this->assertEquals($newData['profile']['familyName'] , $retrieved->profile->familyName);

        self::removeTestAccount($svc, $model->uuid);
    }

    // Auxiliary function_exists
    public static function addTestAccount($svc,
        $primaryEmail = 'test@utest.net',
        $kind = 'kutest', $familyName = 'UTest')
    {
        $input = self::createAccountInput($primaryEmail, $kind, $familyName);
        $model = $svc->createNewModel($input);
        $svc->add($model);
        return $model;
    }

    public static function removeTestAccount($svc, $uuid)
    {
        $svc->removeByPK($uuid);
    }


    public static function createAccountInput(
        $primaryEmail = 'test@utest.net',
        $kind = 'kutest', $familyName = 'UTest')
    {
        $profile = [
            'familyName' => $familyName,
            'givenName' => 'createAccountInput',
            'dob' => '2015-09-21',
            'language' => 'en'
        ];

        $auth = [
            'authSource' => 'utest',
            'authId' => 'utest123',
            'authCredentialsRaw' => 'na',
            'status' => '0'
        ];

        $model = [
            'displayName' => 'Test',
            'primaryEmail' => $primaryEmail,
            'kind' => $kind,
            'status' => 'testing',
            'roles' => 'tester',
            'profile' => $profile,
            'auth' => $auth
        ];
        return $model;
    }
}
