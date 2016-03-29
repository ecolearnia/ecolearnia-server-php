<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Ecofy\Support\EcoCriteriaBuilder;
use App\Modules\Account\AccountService;
use App\Modules\Relation\RelationService;

use App\Modules\Account\Profile;
use App\Modules\Auth\Auth;

class RelationServiceTest extends TestCase
{

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateModel()
    {
        /*
        $svc = new RelationService();
        $input = $this->createAccountInput();
        $model = $svc->createModel($input);

        $this->assertTrue(!empty($model), 'Account model empty');

        //print_r($model->toArray());

        $this->assertTrue(empty($model->uuid), 'account uuid is not empty');
        */
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testCreateNewModel()
    {
        /*
        $svc = new RelationService();
        $input = $this->createAccountInput();
        $model = $svc->createNewModel($input);

        $this->assertTrue(!empty($model), 'Account model empty');

        //print_r($model->toArray());

        $this->assertTrue(!empty($model->uuid), 'account uuid is empty');
        */
    }

    /**
     *
     */
    public function testQueryRelation()
    {
        $accountSvc = new AccountService();
        $account1 = AccountServiceTest::addTestAccount($accountSvc, 'test1@utest.net', 'reltest','Rel-Test1');
        $account2 = AccountServiceTest::addTestAccount($accountSvc, 'test2@utest.net', 'reltest','Rel-Test2');

        $svc = new RelationService();
        $svc->addRelation($account1->uuid, 'role1', $account2->uuid, 'role2', 'testA');
        $svc->addRelation($account2->uuid, 'role1', $account1->uuid, 'role2', 'testAr');
        $svc->addRelation($account1->uuid, 'role3', $account2->uuid, 'role4', 'testA');

        $r1 = $svc->queryRelationsOf($account1->uuid);
        print_r($r1->toJson(JSON_PRETTY_PRINT));
        $this->assertEquals(3, count($r1), 'Relation  queryRelationsOf did not return 3 entries');

        $r1r = $svc->queryRelationsOf($account2->uuid);
        $this->assertEquals(3, count($r1r), 'Relation  queryRelationsOf did not return 3 entries');

        $c2 = EcoCriteriaBuilder::equals('relationship', 'testA');
        $r2 = $svc->queryRelationsOf($account1->uuid, $c2);
        print_r($r2->toJson(JSON_PRETTY_PRINT));
        $this->assertEquals(2, count($r2), 'Relation  queryRelationsOf did not return 2 entries');

        $removeCnt = $svc->removeRelationsOf($account1->uuid);
        $this->assertEquals(3, $removeCnt, 'Relation  removeRelationsOf did not return 3 entries');

        AccountServiceTest::removeTestAccount($accountSvc, $account1->uuid);
        AccountServiceTest::removeTestAccount($accountSvc, $account2->uuid);
    }

    protected function createRelationInput()
    {

    }
}
