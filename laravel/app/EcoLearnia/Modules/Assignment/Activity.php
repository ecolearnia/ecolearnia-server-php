<?php
namespace App\EcoLearnia\Modules\Content;

use App\Ecofy\Support\ModelBase;

class Content extends ModelBase
{
    // model configuration
    protected $primaryKey = 'sid';
    public $timestamps = false;
    protected $dates = ['createdAt', 'modifiedAt'];
    protected $jsons = ['contentInstance', 'state', 'timestamps', 'evalDetails'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [''];

    protected $guarded = ['managedBy', 'createdBy', 'createdAt', 'modifiedBy'
        , 'modifiedAt', 'modifiedCounter'
        '];

}
