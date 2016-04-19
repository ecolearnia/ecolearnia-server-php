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
            var qstring = this.buildQueryString(params);
            var opts = this.buildCallOpts();
            return fetch(this.baseUrl_ + params._id, opts).then(checkStatus).then(function (response) {
                return response.json();
            });
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

            return fetch(this.baseUrl_ + id, opts).then(checkStatus).then(function (response) {
                return response.json();
            });
        }

        /**
         * Queries resources (GET list)
         * @param
         */

    }, {
        key: 'query',
        value: function query(params) {
            var opts = this.buildCallOpts();
            return fetch(this.baseUrl_, opts).then(checkStatus).then(function (response) {
                return response.json();
            });
        }

        /**
         * Deletes a resource (DELETE)
         * @param
         */

    }, {
        key: 'delete',
        value: function _delete(params) {
            var opts = this.buildCallOpts({ method: 'DELETE' });
            return fetch(this.baseUrl_ + params._id, opts).then(checkStatus).then(function (response) {
                return response.json();
            });
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
            var optsMerged = {};
            _lodash2.default.assign(optsMerged, opts, this.defaultRequestOpts_);
            return optsMerged;
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

},{"./content-resource":2}]},{},[])
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy93YXRjaGlmeS9ub2RlX21vZHVsZXMvYnJvd3NlcmlmeS9ub2RlX21vZHVsZXMvYnJvd3Nlci1wYWNrL19wcmVsdWRlLmpzIiwicmVzb3VyY2VzL2Fzc2V0cy9qcy9hYnN0cmFjdC1yZXNvdXJjZS5qcyIsInJlc291cmNlcy9hc3NldHMvanMvY29udGVudC1yZXNvdXJjZS5qcyIsInJlc291cmNlcy9hc3NldHMvanMvbWFpbi5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUNtQkE7Ozs7Ozs7Ozs7Ozs7Ozs7OztJQVdzQjs7Ozs7QUFLakIsYUFMaUIsZ0JBS2pCLENBQVksTUFBWixFQUNBOzhCQU5pQixrQkFNakI7Ozs7Ozs7Ozs7O0FBVUcsYUFBSyxRQUFMLEdBQWdCLE9BQU8sT0FBUCxDQVZuQjtBQVdHLFlBQUksS0FBSyxRQUFMLENBQWMsTUFBZCxDQUFxQixLQUFLLFFBQUwsQ0FBYyxNQUFkLEdBQXFCLENBQXJCLENBQXJCLElBQWdELEdBQWhELEVBQXFEO0FBQ3JELGlCQUFLLFFBQUwsSUFBaUIsR0FBakIsQ0FEcUQ7U0FBekQ7Ozs7OztBQVhILFlBbUJHLENBQUssbUJBQUwsR0FBMkI7QUFDdkIscUJBQVM7QUFDTCwwQkFBVSxrQkFBVjtBQUNBLGdDQUFnQixrQkFBaEI7YUFGSjtTQURKLENBbkJIO0tBREE7Ozs7Ozs7O2lCQUxpQjs7NEJBc0NiLFFBQ0o7QUFDSSxnQkFBSSxDQUFDLE1BQUQsSUFBVyxDQUFDLE9BQU8sR0FBUCxFQUFZO0FBQ3hCLHdCQUFRLE1BQVIsQ0FBZSxJQUFJLEtBQUosQ0FBVSxrQkFBVixDQUFmLEVBRHdCO2FBQTVCO0FBR0EsZ0JBQUksVUFBVSxLQUFLLGdCQUFMLENBQXNCLE1BQXRCLENBQVYsQ0FKUjtBQUtJLGdCQUFJLE9BQVEsS0FBSyxhQUFMLEVBQVIsQ0FMUjtBQU1JLG1CQUFPLE1BQU0sS0FBSyxRQUFMLEdBQWdCLE9BQU8sR0FB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bGUiOiJnZW5lcmF0ZWQuanMiLCJzb3VyY2VSb290IjoiIiwic291cmNlc0NvbnRlbnQiOlsiKGZ1bmN0aW9uIGUodCxuLHIpe2Z1bmN0aW9uIHMobyx1KXtpZighbltvXSl7aWYoIXRbb10pe3ZhciBhPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7aWYoIXUmJmEpcmV0dXJuIGEobywhMCk7aWYoaSlyZXR1cm4gaShvLCEwKTt2YXIgZj1uZXcgRXJyb3IoXCJDYW5ub3QgZmluZCBtb2R1bGUgJ1wiK28rXCInXCIpO3Rocm93IGYuY29kZT1cIk1PRFVMRV9OT1RfRk9VTkRcIixmfXZhciBsPW5bb109e2V4cG9ydHM6e319O3Rbb11bMF0uY2FsbChsLmV4cG9ydHMsZnVuY3Rpb24oZSl7dmFyIG49dFtvXVsxXVtlXTtyZXR1cm4gcyhuP246ZSl9LGwsbC5leHBvcnRzLGUsdCxuLHIpfXJldHVybiBuW29dLmV4cG9ydHN9dmFyIGk9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtmb3IodmFyIG89MDtvPHIubGVuZ3RoO28rKylzKHJbb10pO3JldHVybiBzfSkiLCIvKlxuICogVGhpcyBmaWxlIGlzIHBhcnQgb2YgdGhlIEVjb0xlYXJuaWEgcGxhdGZvcm0uXG4gKlxuICogKGMpIFlvdW5nIFN1ayBBaG4gUGFyayA8eXMuYWhucGFya0BtYXRobmlhLmNvbT5cbiAqXG4gKiBGb3IgdGhlIGZ1bGwgY29weXJpZ2h0IGFuZCBsaWNlbnNlIGluZm9ybWF0aW9uLCBwbGVhc2UgdmlldyB0aGUgTElDRU5TRVxuICogZmlsZSB0aGF0IHdhcyBkaXN0cmlidXRlZCB3aXRoIHRoaXMgc291cmNlIGNvZGUuXG4gKi9cblxuLyoqXG4gKiBFY29MZWFybmlhIHYwLjAuMlxuICpcbiAqIEBmaWxlb3ZlcnZpZXdcbiAqICBUaGlzIGZpbGUgaW5jbHVkZXMgdGhlIGRlZmluaXRpb24gb2YgSXRlbVBsYXllciBjbGFzcy5cbiAqXG4gKiBAYXV0aG9yIFlvdW5nIFN1ayBBaG4gUGFya1xuICogQGRhdGUgNS8xMy8xNVxuICovXG5cbmltcG9ydCBfIGZyb20gJ2xvZGFzaCc7XG5cbiAvKipcbiAgKiBAY2xhc3MgQWJzdHJhY3RSZXNvdXJjZVxuICAqXG4gICogQG1vZHVsZSBzdHVkaW9cbiAgKlxuICAqIEBjbGFzc2Rlc2NcbiAgKiAgQSBkZWZhdWx0IHJlc291cmNlIC5cbiAgKlxuICAqL1xuIGV4cG9ydCBkZWZhdWx0IGNsYXNzIEFic3RyYWN0UmVzb3VyY2VcbiB7XG4gICAgIC8qKlxuICAgICAgKiBAcGFyYW0ge29iamVjdH0gY29uZmlnXG4gICAgICAqL1xuICAgICBjb25zdHJ1Y3Rvcihjb25maWcpXG4gICAgIHtcbiAgICAgICAgLyoqXG4gICAgICAgICAqIFRoZSBsb2dnZXJcbiAgICAgICAgICovXG4gICAgICAgIC8vdGhpcy5sb2dnZXJfID0gbG9nZ2VyLmdldExvZ2dlcignQ29tcG9zaXRpb24nKTtcblxuICAgICAgICAvKipcbiAgICAgICAgICogVGhlIGJhc2UgdXJsIG9mIHRoZSBzZXJ2aWNlXG4gICAgICAgICAqIEB0eXBlIHtzdHJpbmd9XG4gICAgICAgICAqL1xuICAgICAgICB0aGlzLmJhc2VVcmxfID0gY29uZmlnLmJhc2VVcmw7XG4gICAgICAgIGlmICh0aGlzLmJhc2VVcmxfLmNoYXJBdCh0aGlzLmJhc2VVcmxfLmxlbmd0aC0xKSAhPSAnLycpIHtcbiAgICAgICAgICAgIHRoaXMuYmFzZVVybF8gKz0gJy8nO1xuICAgICAgICB9XG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIERlZmF1bHQgaHR0cCByZXF1ZXN0IG9wdGlvbnMgKGZldGNoIG9wdGlvbnMpXG4gICAgICAgICAqIEB0eXBlIHtPYmplY3R9IEBzZWUgaHR0cHM6Ly9kZXZlbG9wZXIubW96aWxsYS5vcmcvZW4tVVMvZG9jcy9XZWIvQVBJL0dsb2JhbEZldGNoL2ZldGNoXG4gICAgICAgICAqL1xuICAgICAgICB0aGlzLmRlZmF1bHRSZXF1ZXN0T3B0c18gPSB7XG4gICAgICAgICAgICBoZWFkZXJzOiB7XG4gICAgICAgICAgICAgICAgJ0FjY2VwdCc6ICdhcHBsaWNhdGlvbi9qc29uJyxcbiAgICAgICAgICAgICAgICAnQ29udGVudC1UeXBlJzogJ2FwcGxpY2F0aW9uL2pzb24nXG4gICAgICAgICAgICB9XG4gICAgICAgIH07XG5cbiAgICAgfVxuXG4gICAgIC8qKlxuICAgICAgKiBHZXRzIGEgcmVzb3VyY2VcbiAgICAgICogQHBhcmFtXG4gICAgICAqL1xuICAgICBnZXQocGFyYW1zKVxuICAgICB7XG4gICAgICAgICBpZiAoIXBhcmFtcyB8fCAhcGFyYW1zLl9pZCkge1xuICAgICAgICAgICAgIFByb21pc2UucmVqZWN0KG5ldyBFcnJvcignX2lkIG5vdCBwcm92aWRlZCcpKTtcbiAgICAgICAgIH1cbiAgICAgICAgIHZhciBxc3RyaW5nID0gdGhpcy5idWlsZFF1ZXJ5U3RyaW5nKHBhcmFtcylcbiAgICAgICAgIGxldCBvcHRzICA9IHRoaXMuYnVpbGRDYWxsT3B0cygpO1xuICAgICAgICAgcmV0dXJuIGZldGNoKHRoaXMuYmFzZVVybF8gKyBwYXJhbXMuX2lkLCBvcHRzKVxuICAgICAgICAgICAgLnRoZW4oY2hlY2tTdGF0dXMpXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpO1xuICAgICB9XG5cbiAgICAgLyoqXG4gICAgICAqIFNhdmVzIGEgcmVzb3VyY2UgKFBPU1QpXG4gICAgICAqIEluc2VydHMgbmV3IChQT1NUKSBpZiBpZCB3YXMgbm90IHByb3ZpZGVkLCB1cGRhdGVzIChQVVQpIG90aGVyd2lzZVxuICAgICAgKiBAcGFyYW1cbiAgICAgICovXG4gICAgIHNhdmUocGFyYW1zLCBkYXRhKVxuICAgICB7XG4gICAgICAgICB2YXIgbWV0aG9kID0gJ1BPU1QnO1xuICAgICAgICAgdmFyIGlkID0gJyc7XG4gICAgICAgICBpZiAocGFyYW1zICYmIHBhcmFtcy5faWQpIHtcbiAgICAgICAgICAgICBtZXRob2QgPSAnUFVUJztcbiAgICAgICAgICAgICBpZCA9IHBhcmFtcy5faWQ7XG4gICAgICAgICB9XG4gICAgICAgICBsZXQgb3B0cyAgPSB0aGlzLmJ1aWxkQ2FsbE9wdHMoe21ldGhvZDogbWV0aG9kLCBib2R5OiBkYXRhfSk7XG5cbiAgICAgICAgIHJldHVybiBmZXRjaCh0aGlzLmJhc2VVcmxfICsgaWQsIG9wdHMpXG4gICAgICAgICAgICAudGhlbihjaGVja1N0YXR1cylcbiAgICAgICAgICAgIC50aGVuKHJlc3BvbnNlID0+IHJlc3BvbnNlLmpzb24oKSk7XG4gICAgIH1cblxuICAgICAvKipcbiAgICAgICogUXVlcmllcyByZXNvdXJjZXMgKEdFVCBsaXN0KVxuICAgICAgKiBAcGFyYW1cbiAgICAgICovXG4gICAgIHF1ZXJ5KHBhcmFtcylcbiAgICAge1xuICAgICAgICAgbGV0IG9wdHMgID0gdGhpcy5idWlsZENhbGxPcHRzKCk7XG4gICAgICAgICByZXR1cm4gZmV0Y2godGhpcy5iYXNlVXJsXywgb3B0cylcbiAgICAgICAgICAgIC50aGVuKGNoZWNrU3RhdHVzKVxuICAgICAgICAgICAgLnRoZW4ocmVzcG9uc2UgPT4gcmVzcG9uc2UuanNvbigpKTtcbiAgICAgfVxuXG4gICAgIC8qKlxuICAgICAgKiBEZWxldGVzIGEgcmVzb3VyY2UgKERFTEVURSlcbiAgICAgICogQHBhcmFtXG4gICAgICAqL1xuICAgICBkZWxldGUocGFyYW1zKVxuICAgICB7XG4gICAgICAgICBsZXQgb3B0cyAgPSB0aGlzLmJ1aWxkQ2FsbE9wdHMoe21ldGhvZDonREVMRVRFJ30pO1xuICAgICAgICAgcmV0dXJuIGZldGNoKHRoaXMuYmFzZVVybF8gKyBwYXJhbXMuX2lkLCBvcHRzKVxuICAgICAgICAgICAgLnRoZW4oY2hlY2tTdGF0dXMpXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpO1xuICAgICB9XG5cbiAgICAgLyoqXG4gICAgICAqIFNhbWUgYXMgcmVtb3ZlXG4gICAgICAqL1xuICAgICByZW1vdmUocGFyYW1zKVxuICAgICB7XG4gICAgICAgICByZXR1cm4gdGhpcy5kZWxldGUocGFyYW1zKTtcbiAgICAgfVxuXG4gICAgIC8qKlxuICAgICAgKiBSZXR1cm5zIGEgcXVlcnkgc3RyaW5nICBleGNsdWRpbmcgdGhlIF9pZCB3aGljaCBzaG91bGQgYmUgdXNlZCBpbiB0aGVcbiAgICAgICogVVJMIHBhdGguXG4gICAgICAqIEBwYXJhbSB7TWFwLjxzdHJpbmcsIHN0cmluZz59IGtleSB2YWx1ZSBwYWlyc1xuICAgICAgKi9cbiAgICAgYnVpbGRRdWVyeVN0cmluZyhwYXJhbXMpXG4gICAgIHtcbiAgICAgICAgdmFyIHF1ZXJ5U3RyaW5ncyA9IFtdO1xuICAgICAgICBmb3IodmFyIGtleSBpbiBwYXJhbXMpXG4gICAgICAgIHtcbiAgICAgICAgICAgICBpZiAoa2V5ICE9PSAnX2lkJykge1xuICAgICAgICAgICAgICAgICBxdWVyeVN0cmluZ3MucHVzaChrZXkgKyAnPScgKyBlbmNvZGVVUklDb21wb25lbnQocGFyYW1zW2tleV0pKTtcbiAgICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHF1ZXJ5U3RyaW5ncy5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICByZXR1cm4gJz8nICsgcXVlcnlTdHJpbmdzLmpvaW4oJyYnKTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gJyc7XG4gICAgIH1cblxuICAgICBidWlsZENhbGxPcHRzKG9wdHMpXG4gICAgIHtcbiAgICAgICAgIGxldCBvcHRzTWVyZ2VkICA9IHt9O1xuICAgICAgICAgXy5hc3NpZ24ob3B0c01lcmdlZCwgb3B0cywgdGhpcy5kZWZhdWx0UmVxdWVzdE9wdHNfKTtcbiAgICAgICAgIHJldHVybiBvcHRzTWVyZ2VkO1xuICAgICB9XG5cbiB9XG5cbmZ1bmN0aW9uIGNoZWNrU3RhdHVzKHJlc3BvbnNlKVxue1xuICAgIGlmIChyZXNwb25zZS5zdGF0dXMgPj0gMjAwICYmIHJlc3BvbnNlLnN0YXR1cyA8IDMwMCkge1xuICAgICAgICByZXR1cm4gcmVzcG9uc2U7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgdmFyIGVycm9yID0gbmV3IEVycm9yKHJlc3BvbnNlLnN0YXR1c1RleHQpXG4gICAgICAgIGVycm9yLnJlc3BvbnNlID0gcmVzcG9uc2U7XG4gICAgICAgIHRocm93IGVycm9yO1xuICAgIH1cbn1cbiIsIi8qXG4gKiBUaGlzIGZpbGUgaXMgcGFydCBvZiB0aGUgRWNvTGVhcm5pYSBwbGF0Zm9ybS5cbiAqXG4gKiAoYykgWW91bmcgU3VrIEFobiBQYXJrIDx5cy5haG5wYXJrQG1hdGhuaWEuY29tPlxuICpcbiAqIEZvciB0aGUgZnVsbCBjb3B5cmlnaHQgYW5kIGxpY2Vuc2UgaW5mb3JtYXRpb24sIHBsZWFzZSB2aWV3IHRoZSBMSUNFTlNFXG4gKiBmaWxlIHRoYXQgd2FzIGRpc3RyaWJ1dGVkIHdpdGggdGhpcyBzb3VyY2UgY29kZS5cbiAqL1xuXG4vKipcbiAqIEVjb0xlYXJuaWEgdjAuMC4yXG4gKlxuICogQGZpbGVvdmVydmlld1xuICogIFRoaXMgZmlsZSBpbmNsdWRlcyB0aGUgZGVmaW5pdGlvbiBvZiBJdGVtUGxheWVyIGNsYXNzLlxuICpcbiAqIEBhdXRob3IgWW91bmcgU3VrIEFobiBQYXJrXG4gKiBAZGF0ZSA1LzEzLzE1XG4gKi9cblxuIGltcG9ydCBBYnN0cmFjdFJlc291cmNlIGZyb20gJy4vYWJzdHJhY3QtcmVzb3VyY2UnO1xuXG4gLyoqXG4gICogQGNsYXNzIENvbnRlbnRSZXNvdXJjZVxuICAqXG4gICogQG1vZHVsZSBzdHVkaW9cbiAgKlxuICAqIEBjbGFzc2Rlc2NcbiAgKiAgQ29udGVudCBSZXNvdXJjZS5cbiAgKlxuICAqL1xuIGV4cG9ydCBkZWZhdWx0IGNsYXNzIENvbnRlbnRSZXNvdXJjZSBleHRlbmRzIEFic3RyYWN0UmVzb3VyY2VcbiB7XG4gICAgIC8qKlxuICAgICAgKiBAcGFyYW0ge29iamVjdH0gY29uZmlnXG4gICAgICAqL1xuICAgICBjb25zdHJ1Y3Rvcihjb25maWcpXG4gICAgIHtcbiAgICAgICAgc3VwZXIoY29uZmlnKTtcbiAgICAgfVxuIH1cbiIsIlxudmFyIENvbnRlbnRSZXNvdXJjZSA9IHJlcXVpcmUoJy4vY29udGVudC1yZXNvdXJjZScpLmRlZmF1bHQ7XG5tb2R1bGUuZXhwb3J0cy5Db250ZW50UmVzb3VyY2UgPSBDb250ZW50UmVzb3VyY2U7XG4iXX0=
