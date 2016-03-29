<?php
namespace App\Ecofy\Support;

use Log;
use Exception;
use DateTime;
use \Ramsey\Uuid\Uuid;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractResourceService
{
    /* Ecofy Query Builder */
    protected $queryBuilder;
    protected $modelFqn;
    protected $primaryKeyName = 'uuid';

    protected $relations = null;

    /**
     * @param string $modelFqn  - Fully qualified name of the model
     * @param array $relations -  Array of name of relations for the eager loading
     */
    public function __construct($modelFqn, $relations = null)
    {
        $this->queryBuilder = new QueryBuilderEloquent();
        // @todo check that the modelFqn starts with '\'
    	$this->modelFqn = $modelFqn;

        $this->relations = $relations;
    }

    /**
     * Gets the model's FQN
     * @return string
     */
    public function getModelFqn()
    {
        return $this->modelFqn;
    }

    /**
     * Validation rules for creation
     * derived class should override this
     *
     * @var array
     */
    protected $validation_rules_create = array();

    /**
     * Validation rules for update.
     * derived class should override this.
     *
     * @var array
     */
    protected $validation_rules_update = array();

    /**
     * Returns the validation object
     */
    public function validator($fields, $is_create = true)
    {
    	$rules = ($is_create) ? $this->validation_rules_create : $this->validation_rules_update;
        return \Validator::make($fields, $rules);
    }

    /**
     * createModel
     * Creates model filling with data by sanitizing first
     */
    public function createModel($data, $modelFqn = null)
    {
        $modelClassName = ($modelFqn == null) ? $this->modelFqn: $modelFqn;
        $model = new $modelClassName();

        if (empty($data))
            return $model;

        if (!empty($model->dateFields)) {
            // Convert date fields from stringified ISO8601 format into DateTime
            foreach($model->dateFields as $dateField) {
                //print("** BEFORE: " . $data[$dateField]);
                if (array_key_exists($dateField, $data)) {
                    $date = \DateTime::createFromFormat('Y-m-d+', $data[$dateField]);
                    // @todo - remove the time part
                    $data[$dateField] = $model->fromDateTime($date);
                    //print("** AFTER: " . $data[$dateField]);
                }
            }
        }

        // The Model::fill checks for fillable and guarded fields
        $model->fill($data);
        return $model;
    }


    /**
     * Creates a new model initializing the createdAt property
     */
    public function createNewModel($data = null, $modelFqn = null)
    {
        $model = $this->createModel($data, $modelFqn);
        $sessionAccount = \Auth::user();
        if (!empty($sessionAccount)) {
            $model->createdBy = $sessionAccount->uuid;
        }
        $model->createdAt = new DateTime();

        // Generate hew UUID
        $primaryKeyName = $this->primaryKeyName;
        if (empty($model->$primaryKeyName)) {
            $model->$primaryKeyName = $this->genUuid();
        }

        return $model;
    }


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
        if (is_array($resource)) {
            // Create model off of array
            $model = $this->createNewModel($resoure);
        } else if ($resource instanceof Model) {
            $model = $resource;
        } else {
            throw new Exception('Unsupported argument type passed');
        }
        /* no longer necessary, createModel() generates the uuid
        // Assign a new UUID
        $primaryKeyName = $this->primaryKeyName;
        if (empty($model->$primaryKeyName)) {
            $model->$primaryKeyName = $this->genUuid();
        }
        */
        $model->save();
        return $model;
    }

    /**
     * query
     *
     * @param Object $criteria - The criteria (in EcoCrieteria format) for the query
     * @param Object $options  - Any options for query operation
     * @return Array.<Model>  - Upon success, return the models
     */
    public function query($criteria, $options = null)
    {
        // Manual test: OK
        $query = $this->buildQuery($criteria);

        $offset = ObjectAccessor::get($options, 'offset', 0);
        $limit = ObjectAccessor::get($options, 'limit', 20);
        // [ <col> => <direction> ]
        $sortParams = ObjectAccessor::get($options, 'sortParams');

        $records = $query; //->skip($offset)->take($limit);
        if (!empty($sortParams)) {
            foreach($sortParams as $col => $direction ) {
                $query->orderBy($col, $direction);
            }
        }
        Log::debug('query: ' . $query->toSql());
        $records = $query->get();

        return $records;
    }

    /**
     * query
     *
     * @param Object  $criteria - The criteria for the query
     * @param Object  $options  - Any options for query operation
     * @return number  - Upon success, return count satisfying the criteria
     */
    public function count($criteria, $options = null)
    {
        $query = $this->buildQuery($criteria);
        $count = $query->count();
        return $count;
    }

    /**
     * Find
     *
     * @param Criteria  $criteria - The crediential object
     * @param object  $options  - Any options for find operation
     * @return Model  - Upon success the model returned
     */
    public function find($criteria, $options = null)
    {
        $query = $this->buildQuery($criteria);
        $record = $query->first();

        return $record;
    }

    /**
     * Find by PK
     *
     * @param mixed  $pk - The primary key of the resource to find
     * @param object  $options  - Any options for find operation
     * @return Model  - Upon success the model returned
     */
    public function findByPK($pk, $options = null)
    {
        $criteria = $this->criteriaByPk($pk);
        $record = $this->find($criteria);
        return $record;
    }

    /**
     * Update
     *
     * @param mixed  $pk - The primary key of the resource to find
     * @param array  $data  - The set of fields to update
     * @param object  $options  - Any options for update operation
     * @return Model  - Upon success the model returned
     */
    public function update($pk, $data, $options = null)
    {
        /* @todo - check the relation and do automatic update of nested objects
        if (!empty($this->relations)) {
        }
        */
        // @todo - Some cases sanitization should be skipped
        //         E.g. when updating lastLogin
        $setData = $this->createModel($data)->toArray();
        $setData['modifiedAt'] = new \DateTime();

        $criteria = $this->criteriaByPk($pk);
        $query = $this->buildQuery($criteria);
        return $query->update($setData);
    }

    /**
     * Remove only one record
     *
     * @param Criteria  $criteria - The crediential object
     * @param object  $options  - Any options for remove operation
     * @return Model  - Upon success the model returned
     */
    public function remove($criteria, $options = null)
    {
        $query = $this->buildQuery($criteria);
        $deletedRows = $query->first()->delete();
        return $deletedRows;
    }

    /**
     * Remove
     *
     * @param mixed  $pk - The primary key of the resource to remove
     * @param object  $options  - Any options for remove operation
     * @return Model  - Upon success the model returned
     */
    public function removeByPK($pk, $options = null)
    {
        $criteria = $this->criteriaByPk($pk);
        $deletedRows = $this->remove($criteria, $options);
        return $deletedRows;
    }

    // }} Resource Access Operations

    public function genUuid()
    {
        return Uuid::uuid4();
    }

    public function criteriaByPk($pk)
    {
        return [
            'var' => $this->primaryKeyName,
            'op'  => '=',
            'val' => $pk
        ];
    }

    /**
     * Creates an Query from EcoCriteria
     *
     * @param EcoCriteria $criteria
     * @param string $modelFql  - The model's fully qualified name,
     *      proiving default (null) makes use of the default model
     */
    protected function buildQuery($criteria, $modelFqn = null)
    {
        $modelClassName = ($modelFqn == null) ? $this->modelFqn: $modelFqn;
        if (empty($criteria)) $criteria = array();

        $modelQuery = $modelClassName::query();
        if ( !empty($this->relations)) {
            // Same as Model::with([relations])
            $modelQuery->with($this->relations);
        }
        $query = $modelQuery->getQuery();

        $queryBuilder = new QueryBuilderEloquent();
        $queryBuilder->buildQuery($criteria, $query);

        return $modelQuery;
    }

    // Import/Export {{

    /**
     * Pre process with the model before importing.
     * Notice the parameter is reference so the data can be modified.
     *
     * @param {array} &$row the row to be imported
     */
    public function prepareRecordForImport(&$row)
    {
        // do nothing, override if you need custom logic
    }

    /**
     * Post process with the model after successfully imported.
     */
    public function afterImport(&$model)
    {
        // do nothing, override if you need custom logic
    }
    // }}

}
