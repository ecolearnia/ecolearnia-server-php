<?php
namespace App\Ecofy\Modules\Relation;

use App\Ecofy\Support\ModelBase;

class Relation extends ModelBase
{
    // model configuration
    protected $primaryKey = 'sid';
    public $timestamps = false;
    protected $dates = ['createdAt', 'modifiedAt', 'lastInteraction'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [''];

    protected $guarded = ['managedBy', 'createdBy', 'createdAt', 'modifiedBy'
        , 'modifiedAt', 'modifiedCounter'
        , 'lastInteraction'];

    /**
     * Get the user that owns the phone.
     */
    public function account1()
    {
        return $this->belongsTo('App\Ecofy\Modules\Account\Account',  'account1Uuid',  'uuid');
    }

    /**
     * Get the user that owns the phone.
     */
    public function account2()
    {
        return $this->belongsTo('App\Ecofy\Modules\Account\Account',  'account2Uuid',  'uuid');
    }
}
