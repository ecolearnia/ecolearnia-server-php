<?php
namespace App\EcoLearnia\Modules\Assignment;

use App\Ecofy\Support\ModelBase;

class Activity extends ModelBase
{
    // model configuration
    protected $primaryKey = 'sid';
    public $timestamps = false;
    protected $dates = ['createdAt', 'modifiedAt'];
    protected $jsons = ['contentInstance', 'item_state', 'item_timestamps', 'item_evalDetailsList'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [''];

    protected $guarded = ['managedBy', 'createdBy', 'createdAt', 'modifiedBy'
        , 'modifiedAt', 'modifiedCounter'];

    /**
     * Get the assignment which this actiivty belongs to
     */
    public function assignment()
    {
        return $this->belongsTo('App\EcoLearnia\Modules\Assignment\Assignment',  'assignmentUuid',  'uuid');
    }

    /**
     * Get the content item which this activity instantates.
     */
    public function content()
    {
        return $this->belongsTo('App\EcoLearnia\Modules\Content\Content',  'contentUuid',  'uuid');
    }

}
