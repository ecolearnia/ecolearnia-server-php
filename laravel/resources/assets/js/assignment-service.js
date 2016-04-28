/*
 * This file is part of the EcoLearnia platform.
 *
 * (c) Young Suk Ahn Park <ys.ahnpark@mathnia.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * EcoLearnia v0.0.2
 *
 * @fileoverview
 *  This file includes the definition of ItemPlayer class.
 *
 * @author Young Suk Ahn Park
 * @date 5/13/15
 */

 import AbstractResource from './abstract-resource';

 /**
  * @class ContentResource
  *
  * @module studio
  *
  * @classdesc
  *  Content Resource.
  *
  */
 export default class AssignmentService
 {
    /**
    * @param {object} config
    */
    constructor(config)
    {
        this.assignmentResource = new AbstractResource(config);
    }

    startAssignment(outsetUuid)
    {
        return this.assignmentResource.doRequest({method: 'POST'}, null, {outsetNode: outsetUuid});
    }

    getAssignment(assignmentUuid)
    {
        return this.assignmentResource.get({_id: assignmentUuid});
    }

    /**
     *
     */
    createNextActivity(assignmentUuid)
    {
        return this.assignmentResource.doRequest({method: 'POST'}, assignmentUuid + '/nextactivity');
    }

    /**
     *
     */
    fetchActivity(assignmentUuid, activityUuid)
    {
        return this.assignmentResource.doRequest({method: 'GET'}, assignmentUuid + '/activity/' + activityUuid);
    }

    /**
     *
     */
    saveActivityState(assignmentUuid, activityUuid, itemState)
    {
        return this.assignmentResource.doRequest({method: 'PUT', body: itemState}, assignmentUuid + '/activity/' + activityUuid + '/state');
    }

    /**
     *
     */
    evalActivity(assignmentUuid, activityUuid, submissionDetails)
    {
        return this.assignmentResource.doRequest({method: 'PUT', body: submissionDetails}, assignmentUuid + '/activity/' + activityUuid + '/eval');
    }
 }
