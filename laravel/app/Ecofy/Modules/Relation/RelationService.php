<?php

namespace App\Ecofy\Modules\Relation;

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

use App\Ecofy\Modules\Relation\RelationServiceContract;


class RelationService extends AbstractResourceService
    implements RelationServiceContract
{
    protected $accountService = null;

    public function __construct() {
		parent::__construct('\\App\\Modules\\Relation\\Relation',['account1', 'account2']);
	}

    public function name()
    {
        return 'RelationService';
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
    public function newRelation($array)
    {
        $model = new Relation($array);
        $model->createdAt = new DateTime();

        return $model;
    }

    /**
     * Add a relation
     */
    public function addRelation($relationship, $account1Uuid, $role1, $account2Uuid, $role2)
    {
        $model = $this->createNewModel(null);
        $model->account1Uuid = $account1Uuid;
        $model->role1 = $role1;
        $model->account2Uuid = $account2Uuid;
        $model->role2 = $role2;
        $model->relationship = $relationship;

        return $this->add($model);
    }

    /**
     * query
     *
     * @param String accountUuid - The account for which query the relations
     * @param Object $criteria - The criteria (in EcoCrieteria format) for the query
     * @param Object $options  - Any options for query operation
     * @return Array.<Model>  - Upon success, return the models
     */
    public function queryRelationsOf($accountUuid, $criteria = null, $options = null)
    {
        $inRelation = EcoCriteriaBuilder::disj(
            [
                EcoCriteriaBuilder::equals('account1Uuid', $accountUuid),
                EcoCriteriaBuilder::equals('account2Uuid', $accountUuid)
            ]
        );

        $relationCriteria = $inRelation;
        if (!empty($criteria)) {
            $relationCriteria = EcoCriteriaBuilder::conj( [ $inRelation, $criteria] );
        }


        $records = parent::query($relationCriteria);

        return $records;
    }

    /**
     * Remove relations of the account
     *
     * @param object  $options  - Any options for remove operation
     * @return Model  - Upon success the model returned
     */
    public function removeRelationsOf($accountUuid, $options = null)
    {
        $inRelation = EcoCriteriaBuilder::disj(
            [
                EcoCriteriaBuilder::comparison('account1Uuid', '=', $accountUuid),
                EcoCriteriaBuilder::comparison('account2Uuid', '=', $accountUuid)
            ]
        );
        $query = $this->buildQuery($inRelation);
        $deletedRows = $query->delete();
        return $deletedRows;
    }

    /**
     * Request relation
     *
     * @param Model\Relation $Relation
     */
    public function request($requestor, $requestee, $relationhip)
    {

    }



}
