<?php
namespace App\Ecofy\Modules\Account;

use DateTime;
use DB;
use Log;

use App\Ecofy\Support\EcoCriteriaBuilder;
use App\Ecofy\Support\AbstractResourceService;

// Models
use Illuminate\Database\Eloquent\Model;
use App\Ecofy\Modules\Auth\Auth;

use App\Ecofy\Modules\Relation\RelationService;


class AccountService extends AbstractResourceService
    implements AccountServiceContract
{
    // From ModelBase
    /**
     * Validation rules for creation
     *
     * @var array
     */
    protected $validation_rules_create = array(
        'primaryEmail' => 'required|email',
        'displayName' => 'required',
        'profile.familyName' => 'required',
        'profile.givenName' => 'required',
        'auth.username' => 'required',
        'auth.security_password' => 'required'
    );

    /**
     * Validation rules for update
     *
     * @var array
     */
    protected $validation_rules_update = array(
        'primaryEmail' => 'email'
    );

    protected $relationService;


    public function __construct() {
		parent::__construct('\\App\\Ecofy\\Modules\\Account\\Account', ['profile', 'auths']);
        $this->relationService = new RelationService();
	}

    /**
     * @override
     * Creates model filling with data by sanitizing first
     */
    public function createModel($data, $modelFqn = null)
    {
        //var_dump($data);
        $model = parent::createModel($data, $modelFqn);
        if (array_key_exists('profile', $data)) {
            $model->profile = parent::createModel($data['profile'], Profile::class);
            if (!empty($model->uuid)) {
                $model->profile->accountUuid = $model->uuid;
            }
        }
        if (array_key_exists('auth', $data)) {
            //var_dump($data['auth']);
            $model->auth = parent::createModel($data['auth'], Auth::class);
            if (!empty($model->uuid)) {
                $model->auth->accountUuid = $model->uuid;
            }
        }
        return $model;
    }


    /**
     * @overrides  AbstractResourceService::createNewModel()
     * Creates a new model initializing the createdAt property
     */
    public function createNewModel($data = null, $modelFqn = null)
    {
        $model = parent::createNewModel($data, $modelFqn);
        if (array_key_exists('profile', $data)) {
            $model->profile = parent::createNewModel($data['profile'], Profile::class);
            $model->profile->accountUuid = $model->uuid;
        }
        if (array_key_exists('auth', $data)) {
            $model->auth = parent::createNewModel($data['auth'], Auth::class);
            $model->auth->accountUuid = $model->uuid;
        }
        return $model;
    }


    public function newAccount($array)
    {
        $model = $this->createNewModel($array);

        return $model;
    }

    public function newProfile($array)
    {
        $model = $this->createNewModel($array, Profile::class);

        return $model;
    }

    /**
     * Sanitize the Profile data
     * @return Profile model
     */
    public function createProfileModel($data)
    {
        return $this->createModel($data, Profile::class);
    }

    /**
     * find by email
     *
     * @param string  $email - Email to search for
     * @return Model  - Upon success, return the found model
     */
    public function findByEmail($email)
    {
        $criteria = EcoCriteriaBuilder::comparison('primaryEmail', '=', $email);
        return $this->find($criteria);
    }

    /**
     * Updates the lastLogin field to now
     * @param Model\Account $account  - The account to update
     */
	public function touchLastLogin($account)
	{
        $primaryKeyName = $this->primaryKeyName;
        $pk = $account->$primaryKeyName;
		$data = [
			//'lastLogin' => new \DateTime()
            'lastLogin' => new \Carbon\Carbon()
		];
		$this->update($pk, $data);
	}

    // Overriding from AbstractResourceService
    // Resource Access Operations {{

    /**
     * Add
     *
     * @param Object  $resource - The resource (record) to add
     * @param Object  $options  - Any options for add operation
     * @return Model  - Upon success, return the added model
     */
    public function add($resource, $options = null)
    {
        $model = null;
        $profileData = null;
        $authData = null;

        if (is_array($resource)) {
            // Create model off of array
            $model = $this->createNewModel($resource);
        } else if ($resource instanceof Model) {
            $model = $resource;
        } else {
            throw new Exception('Unsupported argument type passed');
        }

        DB::transaction(function () use($model) {
            $profileModel = $model->profile;
            $authModel = $model->auth;
            unset($model->profile);
            unset($model->auth);
            $model->save();

            if (!empty($profileModel)) {
                $profileModel->save();
            }
            if (!empty($authModel)) {
                $authModel->save();
            }
        });

        return $model;
    }

    /**
     * Updates the record.
     * Wihin a transaction update profile and account
     */
    public function update($pk, $data, $options = null)
    {
        $account = $this->createModel($data);

        $accountData = $account->toArray();
        unset($accountData['auth']);
        unset($accountData['auths']);
        unset($accountData['profile']);
        $profileData = !empty($account->profile) ? $account->profile->toArray() : null;

        $updateResult = null;
        DB::transaction(function () use($pk, $accountData, $profileData) {
            if (!empty($profileData)) {
                // Update profile
                $query = Profile::query()->getQuery();
                $criteria = EcoCriteriaBuilder::comparison('accountUuid', '=', $pk);
                $profileData['modifiedAt'] = new \DateTime();
                $temp = $this->buildQuery($criteria, Profile::class)->update($profileData);
            }

            // Update account
            //var_dump($accountData);
            //var_dump($profileData);
            //die();
            $updateResult = parent::update($pk, $accountData);
        });

        return $updateResult;
    }

    /**
     * Remove
     *
     * @param Criteria  $criteria - The crediential object
     * @param object  $options  - Any options for remove operation
     * @return Model  - Upon success the model returned
     */
    public function remove($criteria, $options = null)
    {
        $deletedRows = 0;

        $query = $this->buildQuery($criteria);
        $model = $query->first();

        if (!empty($model)) {
            $cascade = (!empty($options) && array_key_exists('cascade', $options))
                ? $options['cascade'] : true;

            if ( $cascade) {
                $model->profile()->delete();
                $model->auths()->delete();
            }

            $deletedRows = $model->delete();
        }

        return $deletedRows;
    }

    // }  Resource Access Operations

    // Import/Export {{
    public function prepareRecordForImport(&$row)
    {
        $row['status'] = 'imported';
        $row['kind'] = 'basic';
        $row['auth']['uuid'] = $this->genUuid();
        $row['auth']['authSource'] = 'local';
        $row['auth']['authId'] = $row['auth']['uuid'];
    }

    public function afterImport(&$model)
    {
        $sessionAccount = \Auth::user();

        // Create relation
        $this->relationService->addRelation('supervision',
            $sessionAccount->uuid, 'supervisor',
            $model->uuid, 'supervisee');

    }
    // }}
}
