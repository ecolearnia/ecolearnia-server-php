<?php

namespace App\Ecofy\Modules\Auth;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    // model configuration
    protected $primaryKey = 'sid';
    public $timestamps = false;
    protected $dates = ['createdAt', 'modifiedAt', 'sessionTimestamp'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['authCredentialsRaw' ,'security_password', 'security_activationCode', 'security_securityAnswer'];

    protected $guarded = ['managedBy', 'createdBy', 'createdAt', 'modifiedBy'
        , 'modifiedAt', 'modifiedCounter'
        , 'sessionTimestamp', 'lastLogin'];

    /**
     * Get the user that owns the phone.
     */
    public function account()
    {
        return $this->belongsTo('App\Ecofy\Modules\Account\Account',  'accountUuid',  'uuid');
    }
}
