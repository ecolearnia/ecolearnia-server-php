<?php
namespace App\EcoLearnia\Modules\Assignment;

use App\Ecofy\Support\ModelBase;

class Assignment extends ModelBase
{
    // model configuration
    protected $primaryKey = 'sid';
    public $timestamps = false;
    protected $dates = ['createdAt', 'modifiedAt', 'lastInteraction'];
    protected $jsons = ['config', 'state_itemEvalBriefs'];

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
     * Get the content is parent in the graph.
     */
    public function outsetCNode()
    {
        return $this->belongsTo('App\EcoLearnia\Modules\Content\Content',  'outsetCNodeUuid',  'uuid');
    }

    /**
     * Get the first activity instantiated for this assignment.
     */
    public function activityHead()
    {
        return $this->belongsTo('App\EcoLearnia\Modules\Assignment\Activity',  'activityHeadUuid',  'uuid');
    }

    /**
     * Get the last activity instantiated for this assignment.
     */
    public function activityTail()
    {
        return $this->belongsTo('App\EcoLearnia\Modules\Assignment\Activity',  'activityTailUuid',  'uuid');
    }

    /**
     * Get the last activity which students were working for this assignment.
     */
    public function recentActivity()
    {
        return $this->belongsTo('App\EcoLearnia\Modules\Assignment\Activity',  'recentActivityUuid',  'uuid');
    }

    public function getStats()
    {
        return [
            'activitiesCount' => $this->stats_activitiesCount,
            'timeSpent' => $this->stats_activitiesCount,
            'corrects' => $this->stats_corrects,
            'incorrects' => $this->stats_incorrects,
            'partialcorrects' => $this->stats_partialcorrects,
            'score' => $this->stats_score
        ];
    }
}
