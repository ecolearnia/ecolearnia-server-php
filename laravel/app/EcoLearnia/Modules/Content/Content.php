<?php
namespace App\EcoLearnia\Modules\Content;

use App\Ecofy\Support\ModelBase;

class Content extends ModelBase
{
    // model configuration
    protected $primaryKey = 'sid';
    public $timestamps = false;
    protected $dates = ['createdAt', 'modifiedAt'];

    protected $jsons = ['content', 'config'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    //protected $hidden = [''];

    protected $guarded = ['managedBy', 'createdBy', 'createdAt', 'modifiedBy'
        , 'modifiedAt', 'modifiedCounter'
        ];

    /**
     * Get the content is parent in the graph.
     */
    public function parent()
    {
        return $this->belongsTo('App\EcoLearnia\Modules\Content\Content',  'parentUuid',  'uuid');
    }

    /**
     * Get the chil contents in the graph.
     */
    public function children()
    {
        return $this->hasMany('App\EcoLearnia\Modules\Content\Content', 'parentUuid', 'uuid');
    }
}
