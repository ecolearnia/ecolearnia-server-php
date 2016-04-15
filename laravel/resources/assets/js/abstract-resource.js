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

import _ from 'lodash';

 /**
  * @class AbstractResource
  *
  * @module studio
  *
  * @classdesc
  *  A default resource .
  *
  */
 export default class AbstractResource
 {
     /**
      * @param {object} config
      */
     constructor(config)
     {
        /**
         * The logger
         */
        //this.logger_ = logger.getLogger('Composition');

        /**
         * The base url of the service
         * @type {string}
         */
        this.baseUrl_ = config.baseUrl;
        if (this.baseUrl_.charAt(this.baseUrl_.length-1) != '/') {
            this.baseUrl_ += '/';
        }

        /**
         * Default http request options (fetch options)
         * @type {Object} @see https://developer.mozilla.org/en-US/docs/Web/API/GlobalFetch/fetch
         */
        this.defaultRequestOpts_ = {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        };

     }

     /**
      * Gets a resource
      * @param
      */
     get(params)
     {
         if (!params || !params._id) {
             Promise.reject(new Error('_id not provided'));
         }
         var qstring = this.buildQueryString(params)
         let opts  = this.buildCallOpts();
         return fetch(this.baseUrl_ + params._id, opts)
            .then(checkStatus)
            .then(response => response.json());
     }

     save(params, data)
     {
         let opts  = this.buildCallOpts({method:'POST', body: data});

         return fetch(this.baseUrl_, opts)
            .then(checkStatus)
            .then(response => response.json());
     }

     query(params)
     {
         let opts  = this.buildCallOpts();
         return fetch(this.baseUrl_, opts)
            .then(checkStatus)
            .then(response => response.json());
     }

     remove(params)
     {
         let opts  = this.buildCallOpts();
         return fetch(this.baseUrl_, opts)
            .then(checkStatus)
            .then(response => response.json());
     }

     delete(params)
     {
         let opts  = this.buildCallOpts();
         return fetch(this.baseUrl_, opts)
            .then(checkStatus)
            .then(response => response.json());
     }

     /**
      * Returns a query string  excluding the _id which should be used in the
      * URL path.
      * @param {Map.<string, string>} key value pairs
      */
     buildQueryString(params)
     {
        var queryStrings = [];
        for(var key in params)
        {
             if (key !== '_id') {
                 queryStrings.push(key + '=' + encodeURIComponent(params[key]));
             }
        }
        if (queryStrings.length > 0) {
            return '?' + queryStrings.join('&');
        }
        return '';
     }

     buildCallOpts(opts)
     {
         let optsMerged  = {};
         _.assign(optsMerged, opts, this.defaultRequestOpts_);
         return optsMerged;
     }

 }

function checkStatus(response)
{
    if (response.status >= 200 && response.status < 300) {
        return response;
    } else {
        var error = new Error(response.statusText)
        error.response = response;
        throw error;
    }
}
