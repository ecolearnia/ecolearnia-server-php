<?php
namespace App\Ecofy\Modules\Account;

use App\Ecofy\Support\ModelBase;

class Profile extends ModelBase
{
    // model configuration
    protected $primaryKey = 'sid';
    public $timestamps = false;
    protected $dates = ['createdAt', 'modifiedAt', 'dob'];

    /**
     * Used by Services
     * Fields that only has date parts (and not time)
     */
    public $dateFields = ['dob'];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    // When uncommented, it failes at readin from DB (MySQL)
    //protected $dateFormat = \DateTime::ATOM;

    protected $guarded = ['sid', 'managedBy', 'createdBy', 'createdAt', 'modifiedBy'
        , 'modifiedAt', 'modifiedCounter'];

    /**
     * Get the user that owns the phone.
     */
    public function account()
    {
        return $this->belongsTo('App\Ecofy\Modules\Account\Account');
    }
}
