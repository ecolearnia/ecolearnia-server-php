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
     * Add child uuid to the content json
     * @param string $childUuid the child uuid to add
     */
    public function addChildUuid($childUuid, $index = -1)
    {
        if ($this->type != 'node') {
            return false;
        }
        $content = $this->content;

        if (empty($content) || !is_array($content))
        {
            $content = [ $childUuid ];
        } else {
            if ($index == -1 || count($content) < $index) {
                array_push($content, $childUuid);
            } else {
                array_splice($content, $index, 0, $childModel->uuid );
            }
        }
        $this->content = $content;
        return true;
    }

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
