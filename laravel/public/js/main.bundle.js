require=(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }(); /*
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

var _lodash = require('lodash');

var _lodash2 = _interopRequireDefault(_lodash);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * @class AbstractResource
 *
 * @module studio
 *
 * @classdesc
 *  A default resource .
 *
 */

var AbstractResource = function () {
    /**
     * @param {object} config
     */

    function AbstractResource(config) {
        _classCallCheck(this, AbstractResource);

        /**
         * The logger
         */
        //this.logger_ = logger.getLogger('Composition');

        /**
         * The base url of the service
         * @type {string}
         */
        this.baseUrl_ = config.baseUrl;
        if (this.baseUrl_.charAt(this.baseUrl_.length - 1) != '/') {
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


    _createClass(AbstractResource, [{
        key: 'get',
        value: function get(params) {
            if (!params || !params._id) {
                Promise.reject(new Error('_id not provided'));
            }
            return this.doRequest({ method: 'GET' }, params._id, params);
        }

        /**
         * Saves a resource (POST)
         * Inserts new (POST) if id was not provided, updates (PUT) otherwise
         * @param
         */

    }, {
        key: 'save',
        value: function save(params, data) {
            var method = 'POST';
            var id = '';
            if (params && params._id) {
                method = 'PUT';
                id = params._id;
            }
            var opts = this.buildCallOpts({ method: method, body: data });

            return this.doRequest({ method: method, body: data }, id, params);
        }

        /**
         * Queries resources (GET list)
         * @param
         */

    }, {
        key: 'query',
        value: function query(params) {
            return this.doRequest({ method: 'GET' }, null, params);
        }

        /**
         * Deletes a resource (DELETE)
         * @param
         */

    }, {
        key: 'delete',
        value: function _delete(params) {
            var opts = this.buildCallOpts({ method: 'DELETE' });
            return this.doRequest({ method: 'DELETE' }, params._id, params);
        }

        /**
         * Same as remove
         */

    }, {
        key: 'remove',
        value: function remove(params) {
            return this.delete(params);
        }

        /**
         * Returns a query string  excluding the _id which should be used in the
         * URL path.
         * @param {Map.<string, string>} key value pairs
         */

    }, {
        key: 'buildQueryString',
        value: function buildQueryString(params) {
            var queryStrings = [];
            for (var key in params) {
                if (key !== '_id') {
                    queryStrings.push(key + '=' + encodeURIComponent(params[key]));
                }
            }
            if (queryStrings.length > 0) {
                return '?' + queryStrings.join('&');
            }
            return '';
        }
    }, {
        key: 'buildCallOpts',
        value: function buildCallOpts(opts) {
            var optParams = opts || {};
            var optsMerged = {};
            _lodash2.default.assign(optsMerged, optParams, this.defaultRequestOpts_);
            return optsMerged;
        }

        /**
         * Makes an http request
         * @param {object} opts - the options
         * @param {!string} path - (optional) path to append to the URL, usually
         *         the id of the resource
         * @param {!Object} queryParams - (optional) key-value pairs for query string
         */

    }, {
        key: 'doRequest',
        value: function doRequest(opts, path, params) {
            var optParams = this.buildCallOpts(opts);
            var pathParam = path || '';
            var qstring = this.buildQueryString(params);
            return fetch(this.baseUrl_ + pathParam + qstring, optParams).then(checkStatus).then(function (response) {
                return response.json();
            });
        }
    }]);

    return AbstractResource;
}();

exports.default = AbstractResource;


function checkStatus(response) {
    if (response.status >= 200 && response.status < 300) {
        return response;
    } else {
        var error = new Error(response.statusText);
        error.response = response;
        throw error;
    }
}

},{"lodash":"lodash"}],2:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
    value: true
});

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }(); /*
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

var _abstractResource = require('./abstract-resource');

var _abstractResource2 = _interopRequireDefault(_abstractResource);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * @class ContentResource
 *
 * @module studio
 *
 * @classdesc
 *  Content Resource.
 *
 */

var AssignmentService = function () {
    /**
    * @param {object} config
    */

    function AssignmentService(config) {
        _classCallCheck(this, AssignmentService);

        this.assignmentResource = new _abstractResource2.default(config);
    }

    _createClass(AssignmentService, [{
        key: 'startAssignment',
        value: function startAssignment(outsetUuid) {
            return this.assignmentResource.doRequest({ method: 'POST' }, null, { outsetNode: outsetUuid });
        }
    }, {
        key: 'getAssignment',
        value: function getAssignment(assignmentUuid) {
            return this.assignmentResource.get({ _id: assignmentUuid });
        }

        /**
         *
         */

    }, {
        key: 'createNextActivity',
        value: function createNextActivity(assignmentUuid) {
            return this.assignmentResource.doRequest({ method: 'POST' }, assignmentUuid + '/nextactivity');
        }

        /**
         *
         */

    }, {
        key: 'fetchActivity',
        value: function fetchActivity(assignmentUuid, activityUuid) {
            return this.assignmentResource.doRequest({ method: 'GET' }, assignmentUuid + '/activity/' + activityUuid);
        }

        /**
         *
         */

    }, {
        key: 'saveActivityState',
        value: function saveActivityState(assignmentUuid, activityUuid, itemState) {
            return this.assignmentResource.doRequest({ method: 'PUT', body: itemState }, assignmentUuid + '/activity/' + activityUuid + '/state');
        }

        /**
         *
         */

    }, {
        key: 'evalActivity',
        value: function evalActivity(assignmentUuid, activityUuid, submissionDetails) {
            return this.assignmentResource.doRequest({ method: 'PUT', body: submissionDetails }, assignmentUuid + '/activity/' + activityUuid + '/eval');
        }
    }]);

    return AssignmentService;
}();

exports.default = AssignmentService;

},{"./abstract-resource":1}],3:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
  value: true
});

var _abstractResource = require('./abstract-resource');

var _abstractResource2 = _interopRequireDefault(_abstractResource);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; } /*
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

/**
 * @class ContentResource
 *
 * @module studio
 *
 * @classdesc
 *  Content Resource.
 *
 */

var ContentResource = function (_AbstractResource) {
  _inherits(ContentResource, _AbstractResource);

  /**
   * @param {object} config
   */

  function ContentResource(config) {
    _classCallCheck(this, ContentResource);

    return _possibleConstructorReturn(this, Object.getPrototypeOf(ContentResource).call(this, config));
  }

  return ContentResource;
}(_abstractResource2.default);

exports.default = ContentResource;

},{"./abstract-resource":1}],"main":[function(require,module,exports){
'use strict';

var ContentResource = require('./content-resource').default;
module.exports.ContentResource = ContentResource;

var AssignmentService = require('./assignment-service').default;
module.exports.AssignmentService = AssignmentService;

},{"./assignment-service":2,"./content-resource":3}]},{},[])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy93YXRjaGlmeS9ub2RlX21vZHVsZXMvYnJvd3NlcmlmeS9ub2RlX21vZHVsZXMvYnJvd3Nlci1wYWNrL19wcmVsdWRlLmpzIiwicmVzb3VyY2VzL2Fzc2V0cy9qcy9hYnN0cmFjdC1yZXNvdXJjZS5qcyIsInJlc291cmNlcy9hc3NldHMvanMvYXNzaWdubWVudC1zZXJ2aWNlLmpzIiwicmVzb3VyY2VzL2Fzc2V0cy9qcy9jb250ZW50LXJlc291cmNlLmpzIiwicmVzb3VyY2VzL2Fzc2V0cy9qcy9tYWluLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiJBQUFBOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQ21CQTs7Ozs7Ozs7Ozs7Ozs7Ozs7O0lBV3NCOzs7OztBQUtqQixhQUxpQixnQkFLakIsQ0FBWSxNQUFaLEVBQ0E7OEJBTmlCLGtCQU1qQjs7Ozs7Ozs7Ozs7QUFVRyxhQUFLLFFBQUwsR0FBZ0IsT0FBTyxPQUFQLENBVm5CO0FBV0csWUFBSSxLQUFLLFFBQUwsQ0FBYyxNQUFkLENBQXFCLEtBQUssUUFBTCxDQUFjLE1BQWQsR0FBcUIsQ0FBckIsQ0FBckIsSUFBZ0QsR0FBaEQsRUFBcUQ7QUFDckQsaUJBQUssUUFBTCxJQUFpQixHQUFqQixDQURxRDtTQUF6RDs7Ozs7O0FBWEgsWUFtQkcsQ0FBSyxtQkFBTCxHQUEyQjtBQUN2QixxQkFBUztBQUNMLDBCQUFVLGtCQUFWO0FBQ0EsZ0NBQWdCLGtCQUFoQjthQUZKO1NBREosQ0FuQkg7S0FEQTs7Ozs7Ozs7aUJBTGlCOzs0QkFzQ2IsUUFDSjtBQUNJLGdCQUFJLENBQUMsTUFBRCxJQUFXLENBQUMsT0FBTyxHQUFQLEVBQVk7QUFDeEIsd0JBQVEsTUFBUixDQUFlLElBQUksS0FBSixDQUFVLGtCQUFWLENBQWYsRUFEd0I7YUFBNUI7QUFHQSxtQkFBTyxLQUFLLFNBQUwsQ0FBZSxFQUFDLFFBQVEsS0FBUixFQUFoQixFQUFnQyxPQUFPLEdBQVAsRUFBWSxNQUE1QyxDQUFQLENB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Ozs7Ozs7Ozs7Ozs7O0lBV3FCOzs7Ozs7O0FBS2pCLFdBTGlCLGVBS2pCLENBQVksTUFBWixFQUNBOzBCQU5pQixpQkFNakI7O2tFQU5pQiw0QkFPUixTQURUO0dBREE7O1NBTGlCOzs7Ozs7OztBQzdCdEIsSUFBSSxrQkFBa0IsUUFBUSxvQkFBUixFQUE4QixPQUE5QjtBQUN0QixPQUFPLE9BQVAsQ0FBZSxlQUFmLEdBQWlDLGVBQWpDOztBQUVBLElBQUksb0JBQW9CLFFBQVEsc0JBQVIsRUFBZ0MsT0FBaEM7QUFDeEIsT0FBTyxPQUFQLENBQWUsaUJBQWYsR0FBbUMsaUJBQW5DIiwiZmlsZSI6ImdlbmVyYXRlZC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzQ29udGVudCI6WyIoZnVuY3Rpb24gZSh0LG4scil7ZnVuY3Rpb24gcyhvLHUpe2lmKCFuW29dKXtpZighdFtvXSl7dmFyIGE9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtpZighdSYmYSlyZXR1cm4gYShvLCEwKTtpZihpKXJldHVybiBpKG8sITApO3ZhciBmPW5ldyBFcnJvcihcIkNhbm5vdCBmaW5kIG1vZHVsZSAnXCIrbytcIidcIik7dGhyb3cgZi5jb2RlPVwiTU9EVUxFX05PVF9GT1VORFwiLGZ9dmFyIGw9bltvXT17ZXhwb3J0czp7fX07dFtvXVswXS5jYWxsKGwuZXhwb3J0cyxmdW5jdGlvbihlKXt2YXIgbj10W29dWzFdW2VdO3JldHVybiBzKG4/bjplKX0sbCxsLmV4cG9ydHMsZSx0LG4scil9cmV0dXJuIG5bb10uZXhwb3J0c312YXIgaT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2Zvcih2YXIgbz0wO288ci5sZW5ndGg7bysrKXMocltvXSk7cmV0dXJuIHN9KSIsIi8qXG4gKiBUaGlzIGZpbGUgaXMgcGFydCBvZiB0aGUgRWNvTGVhcm5pYSBwbGF0Zm9ybS5cbiAqXG4gKiAoYykgWW91bmcgU3VrIEFobiBQYXJrIDx5cy5haG5wYXJrQG1hdGhuaWEuY29tPlxuICpcbiAqIEZvciB0aGUgZnVsbCBjb3B5cmlnaHQgYW5kIGxpY2Vuc2UgaW5mb3JtYXRpb24sIHBsZWFzZSB2aWV3IHRoZSBMSUNFTlNFXG4gKiBmaWxlIHRoYXQgd2FzIGRpc3RyaWJ1dGVkIHdpdGggdGhpcyBzb3VyY2UgY29kZS5cbiAqL1xuXG4vKipcbiAqIEVjb0xlYXJuaWEgdjAuMC4yXG4gKlxuICogQGZpbGVvdmVydmlld1xuICogIFRoaXMgZmlsZSBpbmNsdWRlcyB0aGUgZGVmaW5pdGlvbiBvZiBJdGVtUGxheWVyIGNsYXNzLlxuICpcbiAqIEBhdXRob3IgWW91bmcgU3VrIEFobiBQYXJrXG4gKiBAZGF0ZSA1LzEzLzE1XG4gKi9cblxuaW1wb3J0IF8gZnJvbSAnbG9kYXNoJztcblxuIC8qKlxuICAqIEBjbGFzcyBBYnN0cmFjdFJlc291cmNlXG4gICpcbiAgKiBAbW9kdWxlIHN0dWRpb1xuICAqXG4gICogQGNsYXNzZGVzY1xuICAqICBBIGRlZmF1bHQgcmVzb3VyY2UgLlxuICAqXG4gICovXG4gZXhwb3J0IGRlZmF1bHQgY2xhc3MgQWJzdHJhY3RSZXNvdXJjZVxuIHtcbiAgICAgLyoqXG4gICAgICAqIEBwYXJhbSB7b2JqZWN0fSBjb25maWdcbiAgICAgICovXG4gICAgIGNvbnN0cnVjdG9yKGNvbmZpZylcbiAgICAge1xuICAgICAgICAvKipcbiAgICAgICAgICogVGhlIGxvZ2dlclxuICAgICAgICAgKi9cbiAgICAgICAgLy90aGlzLmxvZ2dlcl8gPSBsb2dnZXIuZ2V0TG9nZ2VyKCdDb21wb3NpdGlvbicpO1xuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBUaGUgYmFzZSB1cmwgb2YgdGhlIHNlcnZpY2VcbiAgICAgICAgICogQHR5cGUge3N0cmluZ31cbiAgICAgICAgICovXG4gICAgICAgIHRoaXMuYmFzZVVybF8gPSBjb25maWcuYmFzZVVybDtcbiAgICAgICAgaWYgKHRoaXMuYmFzZVVybF8uY2hhckF0KHRoaXMuYmFzZVVybF8ubGVuZ3RoLTEpICE9ICcvJykge1xuICAgICAgICAgICAgdGhpcy5iYXNlVXJsXyArPSAnLyc7XG4gICAgICAgIH1cblxuICAgICAgICAvKipcbiAgICAgICAgICogRGVmYXVsdCBodHRwIHJlcXVlc3Qgb3B0aW9ucyAoZmV0Y2ggb3B0aW9ucylcbiAgICAgICAgICogQHR5cGUge09iamVjdH0gQHNlZSBodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL1dlYi9BUEkvR2xvYmFsRmV0Y2gvZmV0Y2hcbiAgICAgICAgICovXG4gICAgICAgIHRoaXMuZGVmYXVsdFJlcXVlc3RPcHRzXyA9IHtcbiAgICAgICAgICAgIGhlYWRlcnM6IHtcbiAgICAgICAgICAgICAgICAnQWNjZXB0JzogJ2FwcGxpY2F0aW9uL2pzb24nLFxuICAgICAgICAgICAgICAgICdDb250ZW50LVR5cGUnOiAnYXBwbGljYXRpb24vanNvbidcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICB9XG5cbiAgICAgLyoqXG4gICAgICAqIEdldHMgYSByZXNvdXJjZVxuICAgICAgKiBAcGFyYW1cbiAgICAgICovXG4gICAgIGdldChwYXJhbXMpXG4gICAgIHtcbiAgICAgICAgIGlmICghcGFyYW1zIHx8ICFwYXJhbXMuX2lkKSB7XG4gICAgICAgICAgICAgUHJvbWlzZS5yZWplY3QobmV3IEVycm9yKCdfaWQgbm90IHByb3ZpZGVkJykpO1xuICAgICAgICAgfVxuICAgICAgICAgcmV0dXJuIHRoaXMuZG9SZXF1ZXN0KHttZXRob2Q6ICdHRVQnfSwgcGFyYW1zLl9pZCwgcGFyYW1zKTtcbiAgICAgfVxuXG4gICAgIC8qKlxuICAgICAgKiBTYXZlcyBhIHJlc291cmNlIChQT1NUKVxuICAgICAgKiBJbnNlcnRzIG5ldyAoUE9TVCkgaWYgaWQgd2FzIG5vdCBwcm92aWRlZCwgdXBkYXRlcyAoUFVUKSBvdGhlcndpc2VcbiAgICAgICogQHBhcmFtXG4gICAgICAqL1xuICAgICBzYXZlKHBhcmFtcywgZGF0YSlcbiAgICAge1xuICAgICAgICAgdmFyIG1ldGhvZCA9ICdQT1NUJztcbiAgICAgICAgIHZhciBpZCA9ICcnO1xuICAgICAgICAgaWYgKHBhcmFtcyAmJiBwYXJhbXMuX2lkKSB7XG4gICAgICAgICAgICAgbWV0aG9kID0gJ1BVVCc7XG4gICAgICAgICAgICAgaWQgPSBwYXJhbXMuX2lkO1xuICAgICAgICAgfVxuICAgICAgICAgbGV0IG9wdHMgID0gdGhpcy5idWlsZENhbGxPcHRzKHttZXRob2Q6IG1ldGhvZCwgYm9keTogZGF0YX0pO1xuXG4gICAgICAgICByZXR1cm4gdGhpcy5kb1JlcXVlc3Qoe21ldGhvZDogbWV0aG9kLCBib2R5OiBkYXRhfSwgaWQsIHBhcmFtcyk7XG4gICAgIH1cblxuICAgICAvKipcbiAgICAgICogUXVlcmllcyByZXNvdXJjZXMgKEdFVCBsaXN0KVxuICAgICAgKiBAcGFyYW1cbiAgICAgICovXG4gICAgIHF1ZXJ5KHBhcmFtcylcbiAgICAge1xuICAgICAgICAgcmV0dXJuIHRoaXMuZG9SZXF1ZXN0KHttZXRob2Q6ICdHRVQnfSwgbnVsbCwgcGFyYW1zKTtcbiAgICAgfVxuXG4gICAgIC8qKlxuICAgICAgKiBEZWxldGVzIGEgcmVzb3VyY2UgKERFTEVURSlcbiAgICAgICogQHBhcmFtXG4gICAgICAqL1xuICAgICBkZWxldGUocGFyYW1zKVxuICAgICB7XG4gICAgICAgICBsZXQgb3B0cyAgPSB0aGlzLmJ1aWxkQ2FsbE9wdHMoe21ldGhvZDonREVMRVRFJ30pO1xuICAgICAgICAgcmV0dXJuIHRoaXMuZG9SZXF1ZXN0KHttZXRob2Q6ICdERUxFVEUnfSwgcGFyYW1zLl9pZCwgcGFyYW1zKTtcbiAgICAgfVxuXG4gICAgIC8qKlxuICAgICAgKiBTYW1lIGFzIHJlbW92ZVxuICAgICAgKi9cbiAgICAgcmVtb3ZlKHBhcmFtcylcbiAgICAge1xuICAgICAgICAgcmV0dXJuIHRoaXMuZGVsZXRlKHBhcmFtcyk7XG4gICAgIH1cblxuICAgICAvKipcbiAgICAgICogUmV0dXJucyBhIHF1ZXJ5IHN0cmluZyAgZXhjbHVkaW5nIHRoZSBfaWQgd2hpY2ggc2hvdWxkIGJlIHVzZWQgaW4gdGhlXG4gICAgICAqIFVSTCBwYXRoLlxuICAgICAgKiBAcGFyYW0ge01hcC48c3RyaW5nLCBzdHJpbmc+fSBrZXkgdmFsdWUgcGFpcnNcbiAgICAgICovXG4gICAgIGJ1aWxkUXVlcnlTdHJpbmcocGFyYW1zKVxuICAgICB7XG4gICAgICAgIHZhciBxdWVyeVN0cmluZ3MgPSBbXTtcbiAgICAgICAgZm9yKHZhciBrZXkgaW4gcGFyYW1zKVxuICAgICAgICB7XG4gICAgICAgICAgICAgaWYgKGtleSAhPT0gJ19pZCcpIHtcbiAgICAgICAgICAgICAgICAgcXVlcnlTdHJpbmdzLnB1c2goa2V5ICsgJz0nICsgZW5jb2RlVVJJQ29tcG9uZW50KHBhcmFtc1trZXldKSk7XG4gICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIGlmIChxdWVyeVN0cmluZ3MubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgcmV0dXJuICc/JyArIHF1ZXJ5U3RyaW5ncy5qb2luKCcmJyk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuICcnO1xuICAgICB9XG5cbiAgICAgYnVpbGRDYWxsT3B0cyhvcHRzKVxuICAgICB7XG4gICAgICAgICBsZXQgb3B0UGFyYW1zID0gb3B0cyB8fCB7fTtcbiAgICAgICAgIGxldCBvcHRzTWVyZ2VkICA9IHt9O1xuICAgICAgICAgXy5hc3NpZ24ob3B0c01lcmdlZCwgb3B0UGFyYW1zLCB0aGlzLmRlZmF1bHRSZXF1ZXN0T3B0c18pO1xuICAgICAgICAgcmV0dXJuIG9wdHNNZXJnZWQ7XG4gICAgIH1cblxuICAgICAvKipcbiAgICAgICogTWFrZXMgYW4gaHR0cCByZXF1ZXN0XG4gICAgICAqIEBwYXJhbSB7b2JqZWN0fSBvcHRzIC0gdGhlIG9wdGlvbnNcbiAgICAgICogQHBhcmFtIHshc3RyaW5nfSBwYXRoIC0gKG9wdGlvbmFsKSBwYXRoIHRvIGFwcGVuZCB0byB0aGUgVVJMLCB1c3VhbGx5XG4gICAgICAqICAgICAgICAgdGhlIGlkIG9mIHRoZSByZXNvdXJjZVxuICAgICAgKiBAcGFyYW0geyFPYmplY3R9IHF1ZXJ5UGFyYW1zIC0gKG9wdGlvbmFsKSBrZXktdmFsdWUgcGFpcnMgZm9yIHF1ZXJ5IHN0cmluZ1xuICAgICAgKi9cbiAgICAgZG9SZXF1ZXN0KG9wdHMsIHBhdGgsIHBhcmFtcylcbiAgICAge1xuICAgICAgICAgbGV0IG9wdFBhcmFtcyA9IHRoaXMuYnVpbGRDYWxsT3B0cyhvcHRzKTtcbiAgICAgICAgIGxldCBwYXRoUGFyYW0gPSBwYXRoIHx8ICcnO1xuICAgICAgICAgdmFyIHFzdHJpbmcgPSB0aGlzLmJ1aWxkUXVlcnlTdHJpbmcocGFyYW1zKTtcbiAgICAgICAgIHJldHVybiBmZXRjaCh0aGlzLmJhc2VVcmxfICsgcGF0aFBhcmFtICsgcXN0cmluZywgb3B0UGFyYW1zKVxuICAgICAgICAgICAgLnRoZW4oY2hlY2tTdGF0dXMpXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpO1xuICAgICB9XG59XG5cbmZ1bmN0aW9uIGNoZWNrU3RhdHVzKHJlc3BvbnNlKVxue1xuICAgIGlmIChyZXNwb25zZS5zdGF0dXMgPj0gMjAwICYmIHJlc3BvbnNlLnN0YXR1cyA8IDMwMCkge1xuICAgICAgICByZXR1cm4gcmVzcG9uc2U7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgdmFyIGVycm9yID0gbmV3IEVycm9yKHJlc3BvbnNlLnN0YXR1c1RleHQpXG4gICAgICAgIGVycm9yLnJlc3BvbnNlID0gcmVzcG9uc2U7XG4gICAgICAgIHRocm93IGVycm9yO1xuICAgIH1cbn1cbiIsIi8qXG4gKiBUaGlzIGZpbGUgaXMgcGFydCBvZiB0aGUgRWNvTGVhcm5pYSBwbGF0Zm9ybS5cbiAqXG4gKiAoYykgWW91bmcgU3VrIEFobiBQYXJrIDx5cy5haG5wYXJrQG1hdGhuaWEuY29tPlxuICpcbiAqIEZvciB0aGUgZnVsbCBjb3B5cmlnaHQgYW5kIGxpY2Vuc2UgaW5mb3JtYXRpb24sIHBsZWFzZSB2aWV3IHRoZSBMSUNFTlNFXG4gKiBmaWxlIHRoYXQgd2FzIGRpc3RyaWJ1dGVkIHdpdGggdGhpcyBzb3VyY2UgY29kZS5cbiAqL1xuXG4vKipcbiAqIEVjb0xlYXJuaWEgdjAuMC4yXG4gKlxuICogQGZpbGVvdmVydmlld1xuICogIFRoaXMgZmlsZSBpbmNsdWRlcyB0aGUgZGVmaW5pdGlvbiBvZiBJdGVtUGxheWVyIGNsYXNzLlxuICpcbiAqIEBhdXRob3IgWW91bmcgU3VrIEFobiBQYXJrXG4gKiBAZGF0ZSA1LzEzLzE1XG4gKi9cblxuIGltcG9ydCBBYnN0cmFjdFJlc291cmNlIGZyb20gJy4vYWJzdHJhY3QtcmVzb3VyY2UnO1xuXG4gLyoqXG4gICogQGNsYXNzIENvbnRlbnRSZXNvdXJjZVxuICAqXG4gICogQG1vZHVsZSBzdHVkaW9cbiAgKlxuICAqIEBjbGFzc2Rlc2NcbiAgKiAgQ29udGVudCBSZXNvdXJjZS5cbiAgKlxuICAqL1xuIGV4cG9ydCBkZWZhdWx0IGNsYXNzIEFzc2lnbm1lbnRTZXJ2aWNlXG4ge1xuICAgIC8qKlxuICAgICogQHBhcmFtIHtvYmplY3R9IGNvbmZpZ1xuICAgICovXG4gICAgY29uc3RydWN0b3IoY29uZmlnKVxuICAgIHtcbiAgICAgICAgdGhpcy5hc3NpZ25tZW50UmVzb3VyY2UgPSBuZXcgQWJzdHJhY3RSZXNvdXJjZShjb25maWcpO1xuICAgIH1cblxuICAgIHN0YXJ0QXNzaWdubWVudChvdXRzZXRVdWlkKVxuICAgIHtcbiAgICAgICAgcmV0dXJuIHRoaXMuYXNzaWdubWVudFJlc291cmNlLmRvUmVxdWVzdCh7bWV0aG9kOiAnUE9TVCd9LCBudWxsLCB7b3V0c2V0Tm9kZTogb3V0c2V0VXVpZH0pO1xuICAgIH1cblxuICAgIGdldEFzc2lnbm1lbnQoYXNzaWdubWVudFV1aWQpXG4gICAge1xuICAgICAgICByZXR1cm4gdGhpcy5hc3NpZ25tZW50UmVzb3VyY2UuZ2V0KHtfaWQ6IGFzc2lnbm1lbnRVdWlkfSk7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICpcbiAgICAgKi9cbiAgICBjcmVhdGVOZXh0QWN0aXZpdHkoYXNzaWdubWVudFV1aWQpXG4gICAge1xuICAgICAgICByZXR1cm4gdGhpcy5hc3NpZ25tZW50UmVzb3VyY2UuZG9SZXF1ZXN0KHttZXRob2Q6ICdQT1NUJ30sIGFzc2lnbm1lbnRVdWlkICsgJy9uZXh0YWN0aXZpdHknKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKlxuICAgICAqL1xuICAgIGZldGNoQWN0aXZpdHkoYXNzaWdubWVudFV1aWQsIGFjdGl2aXR5VXVpZClcbiAgICB7XG4gICAgICAgIHJldHVybiB0aGlzLmFzc2lnbm1lbnRSZXNvdXJjZS5kb1JlcXVlc3Qoe21ldGhvZDogJ0dFVCd9LCBhc3NpZ25tZW50VXVpZCArICcvYWN0aXZpdHkvJyArIGFjdGl2aXR5VXVpZCk7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICpcbiAgICAgKi9cbiAgICBzYXZlQWN0aXZpdHlTdGF0ZShhc3NpZ25tZW50VXVpZCwgYWN0aXZpdHlVdWlkLCBpdGVtU3RhdGUpXG4gICAge1xuICAgICAgICByZXR1cm4gdGhpcy5hc3NpZ25tZW50UmVzb3VyY2UuZG9SZXF1ZXN0KHttZXRob2Q6ICdQVVQnLCBib2R5OiBpdGVtU3RhdGV9LCBhc3NpZ25tZW50VXVpZCArICcvYWN0aXZpdHkvJyArIGFjdGl2aXR5VXVpZCArICcvc3RhdGUnKTtcbiAgICB9XG5cbiAgICAvKipcbiAgICAgKlxuICAgICAqL1xuICAgIGV2YWxBY3Rpdml0eShhc3NpZ25tZW50VXVpZCwgYWN0aXZpdHlVdWlkLCBzdWJtaXNzaW9uRGV0YWlscylcbiAgICB7XG4gICAgICAgIHJldHVybiB0aGlzLmFzc2lnbm1lbnRSZXNvdXJjZS5kb1JlcXVlc3Qoe21ldGhvZDogJ1BVVCcsIGJvZHk6IHN1Ym1pc3Npb25EZXRhaWxzfSwgYXNzaWdubWVudFV1aWQgKyAnL2FjdGl2aXR5LycgKyBhY3Rpdml0eVV1aWQgKyAnL2V2YWwnKTtcbiAgICB9XG4gfVxuIiwiLypcbiAqIFRoaXMgZmlsZSBpcyBwYXJ0IG9mIHRoZSBFY29MZWFybmlhIHBsYXRmb3JtLlxuICpcbiAqIChjKSBZb3VuZyBTdWsgQWhuIFBhcmsgPHlzLmFobnBhcmtAbWF0aG5pYS5jb20+XG4gKlxuICogRm9yIHRoZSBmdWxsIGNvcHlyaWdodCBhbmQgbGljZW5zZSBpbmZvcm1hdGlvbiwgcGxlYXNlIHZpZXcgdGhlIExJQ0VOU0VcbiAqIGZpbGUgdGhhdCB3YXMgZGlzdHJpYnV0ZWQgd2l0aCB0aGlzIHNvdXJjZSBjb2RlLlxuICovXG5cbi8qKlxuICogRWNvTGVhcm5pYSB2MC4wLjJcbiAqXG4gKiBAZmlsZW92ZXJ2aWV3XG4gKiAgVGhpcyBmaWxlIGluY2x1ZGVzIHRoZSBkZWZpbml0aW9uIG9mIEl0ZW1QbGF5ZXIgY2xhc3MuXG4gKlxuICogQGF1dGhvciBZb3VuZyBTdWsgQWhuIFBhcmtcbiAqIEBkYXRlIDUvMTMvMTVcbiAqL1xuXG4gaW1wb3J0IEFic3RyYWN0UmVzb3VyY2UgZnJvbSAnLi9hYnN0cmFjdC1yZXNvdXJjZSc7XG5cbiAvKipcbiAgKiBAY2xhc3MgQ29udGVudFJlc291cmNlXG4gICpcbiAgKiBAbW9kdWxlIHN0dWRpb1xuICAqXG4gICogQGNsYXNzZGVzY1xuICAqICBDb250ZW50IFJlc291cmNlLlxuICAqXG4gICovXG4gZXhwb3J0IGRlZmF1bHQgY2xhc3MgQ29udGVudFJlc291cmNlIGV4dGVuZHMgQWJzdHJhY3RSZXNvdXJjZVxuIHtcbiAgICAgLyoqXG4gICAgICAqIEBwYXJhbSB7b2JqZWN0fSBjb25maWdcbiAgICAgICovXG4gICAgIGNvbnN0cnVjdG9yKGNvbmZpZylcbiAgICAge1xuICAgICAgICBzdXBlcihjb25maWcpO1xuICAgICB9XG4gfVxuIiwiXG52YXIgQ29udGVudFJlc291cmNlID0gcmVxdWlyZSgnLi9jb250ZW50LXJlc291cmNlJykuZGVmYXVsdDtcbm1vZHVsZS5leHBvcnRzLkNvbnRlbnRSZXNvdXJjZSA9IENvbnRlbnRSZXNvdXJjZTtcblxudmFyIEFzc2lnbm1lbnRTZXJ2aWNlID0gcmVxdWlyZSgnLi9hc3NpZ25tZW50LXNlcnZpY2UnKS5kZWZhdWx0O1xubW9kdWxlLmV4cG9ydHMuQXNzaWdubWVudFNlcnZpY2UgPSBBc3NpZ25tZW50U2VydmljZTtcbiJdfQ==
