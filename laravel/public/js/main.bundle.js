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
    }, {
        key: 'save',
        value: function save(params, data) {
            var opts = this.buildCallOpts({ method: 'POST', body: data });

            return fetch(this.baseUrl_, opts).then(checkStatus).then(function (response) {
                return response.json();
            });
        }
    }, {
        key: 'query',
        value: function query(params) {
            var opts = this.buildCallOpts();
            return fetch(this.baseUrl_, opts).then(checkStatus).then(function (response) {
                return response.json();
            });
        }
    }, {
        key: 'remove',
        value: function remove(params) {
            var opts = this.buildCallOpts();
            return fetch(this.baseUrl_, opts).then(checkStatus).then(function (response) {
                return response.json();
            });
        }
    }, {
        key: 'delete',
        value: function _delete(params) {
            var opts = this.buildCallOpts();
            return fetch(this.baseUrl_, opts).then(checkStatus).then(function (response) {
                return response.json();
            });
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy93YXRjaGlmeS9ub2RlX21vZHVsZXMvYnJvd3NlcmlmeS9ub2RlX21vZHVsZXMvYnJvd3Nlci1wYWNrL19wcmVsdWRlLmpzIiwicmVzb3VyY2VzL2Fzc2V0cy9qcy9hYnN0cmFjdC1yZXNvdXJjZS5qcyIsInJlc291cmNlcy9hc3NldHMvanMvY29udGVudC1yZXNvdXJjZS5qcyIsInJlc291cmNlcy9hc3NldHMvanMvbWFpbi5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUNtQkE7Ozs7Ozs7Ozs7Ozs7Ozs7OztJQVdzQjs7Ozs7QUFLakIsYUFMaUIsZ0JBS2pCLENBQVksTUFBWixFQUNBOzhCQU5pQixrQkFNakI7Ozs7Ozs7Ozs7O0FBVUcsYUFBSyxRQUFMLEdBQWdCLE9BQU8sT0FBUCxDQVZuQjtBQVdHLFlBQUksS0FBSyxRQUFMLENBQWMsTUFBZCxDQUFxQixLQUFLLFFBQUwsQ0FBYyxNQUFkLEdBQXFCLENBQXJCLENBQXJCLElBQWdELEdBQWhELEVBQXFEO0FBQ3JELGlCQUFLLFFBQUwsSUFBaUIsR0FBakIsQ0FEcUQ7U0FBekQ7Ozs7OztBQVhILFlBbUJHLENBQUssbUJBQUwsR0FBMkI7QUFDdkIscUJBQVM7QUFDTCwwQkFBVSxrQkFBVjtBQUNBLGdDQUFnQixrQkFBaEI7YUFGSjtTQURKLENBbkJIO0tBREE7Ozs7Ozs7O2lCQUxpQjs7NEJBc0NiLFFBQ0o7QUFDSSxnQkFBSSxDQUFDLE1BQUQsSUFBVyxDQUFDLE9BQU8sR0FBUCxFQUFZO0FBQ3hCLHdCQUFRLE1BQVIsQ0FBZSxJQUFJLEtBQUosQ0FBVSxrQkFBVixDQUFmLEVBRHdCO2FBQTVCO0FBR0EsZ0JBQUksVUFBVSxLQUFLLGdCQUFMLENBQXNCLE1BQXRCLENBQVYsQ0FKUjtBQUtJLGdCQUFJLE9BQVEsS0FBSyxhQUFMLEVBQVIsQ0FMUjtBQU1JLG1CQUFPLE1BQU0sS0FBSyxRQUFMLEdBQWdCLE9BQU8sR0FBUCxFQUFZLElBQWxDLEVBQ0gsSUFERyxDQUNFLFdBREYsRUFFSCxJQUZHLENBRUU7dUJBQVksU0FBUyxJQUFUO2FBQVosQ0FGVCxDQU5KOzs7OzZCQVdLLFFBQVEsTUFDYjtBQUNJLGdCQUFJLE9BQVEsS0FBSyxhQUFMLENBQW1CLEVBQUMsUUFBTyxNQUFQLEVBQWUsTUFBTSxJQUFOLEVBQW5DLENBQVIsQ0FEUjs7QUFHSSxtQkFBTyxNQUFNLEtBQUssUUFBTCxFQUFlLElBQXJCLEVBQ0gsSUFERyxDQUNFLFdBREYsRUFFSCxJQUZHLENBRUU7dUJBQVksU0FBUyxJQUFUO2FBQVosQ0FGVCxDQUhKOzs7OzhCQVFNLFFBQ047QUFDSSxnQkFBSSxPQUFRLEtBQUssYUFBTCxFQUFSLENBRFI7QUFFSSxtQkFBTyxNQUFNLEtBQUssUUFBTCxFQUFlLElBQXJCLEVBQ0gsSUFERyxDQUNFLFdBREYsRUFFSCxJQUZHLENBRUU7dUJBQVksU0FBUyxJQUFUO2FBQVosQ0FGVCxDQUZKOzs7OytCQU9PLFFBQ1A7QUFDSSxnQkFBSSxPQUFRLEtBQUssYUFBTCxFQUFSLENBRFI7QUFFSSxtQkFBTyxNQUFNLEtBQUssUUFBTCxFQUFlLElBQXJCLEVBQ0gsSUFERyxDQUNFLFdBREYsRUFFSCxJQUZHLENBRUU7dUJBQVksU0FBUyxJQUFUO2FBQVosQ0FGVCxDQUZKOzs7O2dDQU9PLFFBQ1A7QUFDSSxnQkFBSSxPQUFRLEtBQUssYUFBTCxFQUFSLENBRFI7QUFFSSxtQkFBTyxNQUFNLEtBQUssUUFBTCxFQUFlLElBQXJCLEVBQ0gsSUFERyxDQUNFLFdBREYsRUFFSCxJQUZHLENBRUU7dUJBQVksU0FBUyxJQUFUO2FBQVosQ0FGVCxDQUZKOzs7Ozs7Ozs7Ozt5Q0FZaUIsUUFDakI7QUFDRyxnQkFBSSxlQUFlLEVBQWYsQ0FEUDtBQUVHLGlCQUFJLElBQUksR0FBSixJQUFXLE1BQWYsRUFDQTtBQUNLLG9CQUFJLFFBQVEsS0FBUixFQUFlO0FBQ2YsaUNBQWEsSUFBYixDQUFrQixNQUFNLEdBQU4sR0FBWSxtQkFBbUIsT0FBTyxHQUFQLENBQW5CLENBQVosQ0FBbEIsQ0FEZTtpQkFBbkI7YUFGTDtBQU1BLGdCQUFJLGFBQWEsTUFBYixHQUFzQixDQUF0QixFQUF5QjtBQUN6Qix1QkFBTyxNQUFNLGFBQWEsSUFBYixDQUFrQixHQUFsQixDQUFOLENBRGtCO2FBQTdCO0FBR0EsbUJBQU8sRUFBUCxDQVhIOzs7O3NDQWNjLE1BQ2Q7QUFDSSxnQkFBSSxhQUFjLEVBQWQsQ0FEUjtBQUVJLDZCQUFFLE1BQUYsQ0FBUyxVQUFULEVBQXFCLElBQXJCLEVBQTJCLEtBQUssbUJBQUwsQ0FBM0IsQ0FGSjtBQUdJLG1CQUFPLFVBQVAsQ0FISjs7OztXQXhHaUI7Ozs7OztBQWdIdEIsU0FBUyxXQUFULENBQXFCLFFBQXJCLEVBQ0E7QUFDSSxRQUFJLFNBQVMsTUFBVCxJQUFtQixHQUFuQixJQUEwQixTQUFTLE1BQVQsR0FBa0IsR0FBbEIsRUFBdUI7QUFDakQsZUFBTyxRQUFQLENBRGlEO0tBQXJELE1BRU87QUFDSCxZQUFJLFFBQVEsSUFBSSxLQUFKLENBQVUsU0FBUyxVQUFULENBQWxCLENBREQ7QUFFSCxjQUFNLFFBQU4sR0FBaUIsUUFBakIsQ0FGRztBQUdILGNBQU0sS0FBTixDQUhHO0tBRlA7Q0FGSjs7Ozs7Ozs7O0FDM0hDOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7SUFXcUI7Ozs7Ozs7QUFLakIsV0FMaUIsZUFLakIsQ0FBWSxNQUFaLEVBQ0E7MEJBTmlCLGlCQU1qQjs7a0VBTmlCLDRCQU9SLFNBRFQ7R0FEQTs7U0FMaUI7Ozs7Ozs7O0FDN0J0QixJQUFJLGtCQUFrQixRQUFRLG9CQUFSLEVBQThCLE9BQTlCO0FBQ3RCLE9BQU8sT0FBUCxDQUFlLGVBQWYsR0FBaUMsZUFBakMiLCJmaWxlIjoiZ2VuZXJhdGVkLmpzIiwic291cmNlUm9vdCI6IiIsInNvdXJjZXNDb250ZW50IjpbIihmdW5jdGlvbiBlKHQsbixyKXtmdW5jdGlvbiBzKG8sdSl7aWYoIW5bb10pe2lmKCF0W29dKXt2YXIgYT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2lmKCF1JiZhKXJldHVybiBhKG8sITApO2lmKGkpcmV0dXJuIGkobywhMCk7dmFyIGY9bmV3IEVycm9yKFwiQ2Fubm90IGZpbmQgbW9kdWxlICdcIitvK1wiJ1wiKTt0aHJvdyBmLmNvZGU9XCJNT0RVTEVfTk9UX0ZPVU5EXCIsZn12YXIgbD1uW29dPXtleHBvcnRzOnt9fTt0W29dWzBdLmNhbGwobC5leHBvcnRzLGZ1bmN0aW9uKGUpe3ZhciBuPXRbb11bMV1bZV07cmV0dXJuIHMobj9uOmUpfSxsLGwuZXhwb3J0cyxlLHQsbixyKX1yZXR1cm4gbltvXS5leHBvcnRzfXZhciBpPXR5cGVvZiByZXF1aXJlPT1cImZ1bmN0aW9uXCImJnJlcXVpcmU7Zm9yKHZhciBvPTA7bzxyLmxlbmd0aDtvKyspcyhyW29dKTtyZXR1cm4gc30pIiwiLypcbiAqIFRoaXMgZmlsZSBpcyBwYXJ0IG9mIHRoZSBFY29MZWFybmlhIHBsYXRmb3JtLlxuICpcbiAqIChjKSBZb3VuZyBTdWsgQWhuIFBhcmsgPHlzLmFobnBhcmtAbWF0aG5pYS5jb20+XG4gKlxuICogRm9yIHRoZSBmdWxsIGNvcHlyaWdodCBhbmQgbGljZW5zZSBpbmZvcm1hdGlvbiwgcGxlYXNlIHZpZXcgdGhlIExJQ0VOU0VcbiAqIGZpbGUgdGhhdCB3YXMgZGlzdHJpYnV0ZWQgd2l0aCB0aGlzIHNvdXJjZSBjb2RlLlxuICovXG5cbi8qKlxuICogRWNvTGVhcm5pYSB2MC4wLjJcbiAqXG4gKiBAZmlsZW92ZXJ2aWV3XG4gKiAgVGhpcyBmaWxlIGluY2x1ZGVzIHRoZSBkZWZpbml0aW9uIG9mIEl0ZW1QbGF5ZXIgY2xhc3MuXG4gKlxuICogQGF1dGhvciBZb3VuZyBTdWsgQWhuIFBhcmtcbiAqIEBkYXRlIDUvMTMvMTVcbiAqL1xuXG5pbXBvcnQgXyBmcm9tICdsb2Rhc2gnO1xuXG4gLyoqXG4gICogQGNsYXNzIEFic3RyYWN0UmVzb3VyY2VcbiAgKlxuICAqIEBtb2R1bGUgc3R1ZGlvXG4gICpcbiAgKiBAY2xhc3NkZXNjXG4gICogIEEgZGVmYXVsdCByZXNvdXJjZSAuXG4gICpcbiAgKi9cbiBleHBvcnQgZGVmYXVsdCBjbGFzcyBBYnN0cmFjdFJlc291cmNlXG4ge1xuICAgICAvKipcbiAgICAgICogQHBhcmFtIHtvYmplY3R9IGNvbmZpZ1xuICAgICAgKi9cbiAgICAgY29uc3RydWN0b3IoY29uZmlnKVxuICAgICB7XG4gICAgICAgIC8qKlxuICAgICAgICAgKiBUaGUgbG9nZ2VyXG4gICAgICAgICAqL1xuICAgICAgICAvL3RoaXMubG9nZ2VyXyA9IGxvZ2dlci5nZXRMb2dnZXIoJ0NvbXBvc2l0aW9uJyk7XG5cbiAgICAgICAgLyoqXG4gICAgICAgICAqIFRoZSBiYXNlIHVybCBvZiB0aGUgc2VydmljZVxuICAgICAgICAgKiBAdHlwZSB7c3RyaW5nfVxuICAgICAgICAgKi9cbiAgICAgICAgdGhpcy5iYXNlVXJsXyA9IGNvbmZpZy5iYXNlVXJsO1xuICAgICAgICBpZiAodGhpcy5iYXNlVXJsXy5jaGFyQXQodGhpcy5iYXNlVXJsXy5sZW5ndGgtMSkgIT0gJy8nKSB7XG4gICAgICAgICAgICB0aGlzLmJhc2VVcmxfICs9ICcvJztcbiAgICAgICAgfVxuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBEZWZhdWx0IGh0dHAgcmVxdWVzdCBvcHRpb25zIChmZXRjaCBvcHRpb25zKVxuICAgICAgICAgKiBAdHlwZSB7T2JqZWN0fSBAc2VlIGh0dHBzOi8vZGV2ZWxvcGVyLm1vemlsbGEub3JnL2VuLVVTL2RvY3MvV2ViL0FQSS9HbG9iYWxGZXRjaC9mZXRjaFxuICAgICAgICAgKi9cbiAgICAgICAgdGhpcy5kZWZhdWx0UmVxdWVzdE9wdHNfID0ge1xuICAgICAgICAgICAgaGVhZGVyczoge1xuICAgICAgICAgICAgICAgICdBY2NlcHQnOiAnYXBwbGljYXRpb24vanNvbicsXG4gICAgICAgICAgICAgICAgJ0NvbnRlbnQtVHlwZSc6ICdhcHBsaWNhdGlvbi9qc29uJ1xuICAgICAgICAgICAgfVxuICAgICAgICB9O1xuXG4gICAgIH1cblxuICAgICAvKipcbiAgICAgICogR2V0cyBhIHJlc291cmNlXG4gICAgICAqIEBwYXJhbVxuICAgICAgKi9cbiAgICAgZ2V0KHBhcmFtcylcbiAgICAge1xuICAgICAgICAgaWYgKCFwYXJhbXMgfHwgIXBhcmFtcy5faWQpIHtcbiAgICAgICAgICAgICBQcm9taXNlLnJlamVjdChuZXcgRXJyb3IoJ19pZCBub3QgcHJvdmlkZWQnKSk7XG4gICAgICAgICB9XG4gICAgICAgICB2YXIgcXN0cmluZyA9IHRoaXMuYnVpbGRRdWVyeVN0cmluZyhwYXJhbXMpXG4gICAgICAgICBsZXQgb3B0cyAgPSB0aGlzLmJ1aWxkQ2FsbE9wdHMoKTtcbiAgICAgICAgIHJldHVybiBmZXRjaCh0aGlzLmJhc2VVcmxfICsgcGFyYW1zLl9pZCwgb3B0cylcbiAgICAgICAgICAgIC50aGVuKGNoZWNrU3RhdHVzKVxuICAgICAgICAgICAgLnRoZW4ocmVzcG9uc2UgPT4gcmVzcG9uc2UuanNvbigpKTtcbiAgICAgfVxuXG4gICAgIHNhdmUocGFyYW1zLCBkYXRhKVxuICAgICB7XG4gICAgICAgICBsZXQgb3B0cyAgPSB0aGlzLmJ1aWxkQ2FsbE9wdHMoe21ldGhvZDonUE9TVCcsIGJvZHk6IGRhdGF9KTtcblxuICAgICAgICAgcmV0dXJuIGZldGNoKHRoaXMuYmFzZVVybF8sIG9wdHMpXG4gICAgICAgICAgICAudGhlbihjaGVja1N0YXR1cylcbiAgICAgICAgICAgIC50aGVuKHJlc3BvbnNlID0+IHJlc3BvbnNlLmpzb24oKSk7XG4gICAgIH1cblxuICAgICBxdWVyeShwYXJhbXMpXG4gICAgIHtcbiAgICAgICAgIGxldCBvcHRzICA9IHRoaXMuYnVpbGRDYWxsT3B0cygpO1xuICAgICAgICAgcmV0dXJuIGZldGNoKHRoaXMuYmFzZVVybF8sIG9wdHMpXG4gICAgICAgICAgICAudGhlbihjaGVja1N0YXR1cylcbiAgICAgICAgICAgIC50aGVuKHJlc3BvbnNlID0+IHJlc3BvbnNlLmpzb24oKSk7XG4gICAgIH1cblxuICAgICByZW1vdmUocGFyYW1zKVxuICAgICB7XG4gICAgICAgICBsZXQgb3B0cyAgPSB0aGlzLmJ1aWxkQ2FsbE9wdHMoKTtcbiAgICAgICAgIHJldHVybiBmZXRjaCh0aGlzLmJhc2VVcmxfLCBvcHRzKVxuICAgICAgICAgICAgLnRoZW4oY2hlY2tTdGF0dXMpXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpO1xuICAgICB9XG5cbiAgICAgZGVsZXRlKHBhcmFtcylcbiAgICAge1xuICAgICAgICAgbGV0IG9wdHMgID0gdGhpcy5idWlsZENhbGxPcHRzKCk7XG4gICAgICAgICByZXR1cm4gZmV0Y2godGhpcy5iYXNlVXJsXywgb3B0cylcbiAgICAgICAgICAgIC50aGVuKGNoZWNrU3RhdHVzKVxuICAgICAgICAgICAgLnRoZW4ocmVzcG9uc2UgPT4gcmVzcG9uc2UuanNvbigpKTtcbiAgICAgfVxuXG4gICAgIC8qKlxuICAgICAgKiBSZXR1cm5zIGEgcXVlcnkgc3RyaW5nICBleGNsdWRpbmcgdGhlIF9pZCB3aGljaCBzaG91bGQgYmUgdXNlZCBpbiB0aGVcbiAgICAgICogVVJMIHBhdGguXG4gICAgICAqIEBwYXJhbSB7TWFwLjxzdHJpbmcsIHN0cmluZz59IGtleSB2YWx1ZSBwYWlyc1xuICAgICAgKi9cbiAgICAgYnVpbGRRdWVyeVN0cmluZyhwYXJhbXMpXG4gICAgIHtcbiAgICAgICAgdmFyIHF1ZXJ5U3RyaW5ncyA9IFtdO1xuICAgICAgICBmb3IodmFyIGtleSBpbiBwYXJhbXMpXG4gICAgICAgIHtcbiAgICAgICAgICAgICBpZiAoa2V5ICE9PSAnX2lkJykge1xuICAgICAgICAgICAgICAgICBxdWVyeVN0cmluZ3MucHVzaChrZXkgKyAnPScgKyBlbmNvZGVVUklDb21wb25lbnQocGFyYW1zW2tleV0pKTtcbiAgICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgaWYgKHF1ZXJ5U3RyaW5ncy5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICByZXR1cm4gJz8nICsgcXVlcnlTdHJpbmdzLmpvaW4oJyYnKTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gJyc7XG4gICAgIH1cblxuICAgICBidWlsZENhbGxPcHRzKG9wdHMpXG4gICAgIHtcbiAgICAgICAgIGxldCBvcHRzTWVyZ2VkICA9IHt9O1xuICAgICAgICAgXy5hc3NpZ24ob3B0c01lcmdlZCwgb3B0cywgdGhpcy5kZWZhdWx0UmVxdWVzdE9wdHNfKTtcbiAgICAgICAgIHJldHVybiBvcHRzTWVyZ2VkO1xuICAgICB9XG5cbiB9XG5cbmZ1bmN0aW9uIGNoZWNrU3RhdHVzKHJlc3BvbnNlKVxue1xuICAgIGlmIChyZXNwb25zZS5zdGF0dXMgPj0gMjAwICYmIHJlc3BvbnNlLnN0YXR1cyA8IDMwMCkge1xuICAgICAgICByZXR1cm4gcmVzcG9uc2U7XG4gICAgfSBlbHNlIHtcbiAgICAgICAgdmFyIGVycm9yID0gbmV3IEVycm9yKHJlc3BvbnNlLnN0YXR1c1RleHQpXG4gICAgICAgIGVycm9yLnJlc3BvbnNlID0gcmVzcG9uc2U7XG4gICAgICAgIHRocm93IGVycm9yO1xuICAgIH1cbn1cbiIsIi8qXG4gKiBUaGlzIGZpbGUgaXMgcGFydCBvZiB0aGUgRWNvTGVhcm5pYSBwbGF0Zm9ybS5cbiAqXG4gKiAoYykgWW91bmcgU3VrIEFobiBQYXJrIDx5cy5haG5wYXJrQG1hdGhuaWEuY29tPlxuICpcbiAqIEZvciB0aGUgZnVsbCBjb3B5cmlnaHQgYW5kIGxpY2Vuc2UgaW5mb3JtYXRpb24sIHBsZWFzZSB2aWV3IHRoZSBMSUNFTlNFXG4gKiBmaWxlIHRoYXQgd2FzIGRpc3RyaWJ1dGVkIHdpdGggdGhpcyBzb3VyY2UgY29kZS5cbiAqL1xuXG4vKipcbiAqIEVjb0xlYXJuaWEgdjAuMC4yXG4gKlxuICogQGZpbGVvdmVydmlld1xuICogIFRoaXMgZmlsZSBpbmNsdWRlcyB0aGUgZGVmaW5pdGlvbiBvZiBJdGVtUGxheWVyIGNsYXNzLlxuICpcbiAqIEBhdXRob3IgWW91bmcgU3VrIEFobiBQYXJrXG4gKiBAZGF0ZSA1LzEzLzE1XG4gKi9cblxuIGltcG9ydCBBYnN0cmFjdFJlc291cmNlIGZyb20gJy4vYWJzdHJhY3QtcmVzb3VyY2UnO1xuXG4gLyoqXG4gICogQGNsYXNzIENvbnRlbnRSZXNvdXJjZVxuICAqXG4gICogQG1vZHVsZSBzdHVkaW9cbiAgKlxuICAqIEBjbGFzc2Rlc2NcbiAgKiAgQ29udGVudCBSZXNvdXJjZS5cbiAgKlxuICAqL1xuIGV4cG9ydCBkZWZhdWx0IGNsYXNzIENvbnRlbnRSZXNvdXJjZSBleHRlbmRzIEFic3RyYWN0UmVzb3VyY2VcbiB7XG4gICAgIC8qKlxuICAgICAgKiBAcGFyYW0ge29iamVjdH0gY29uZmlnXG4gICAgICAqL1xuICAgICBjb25zdHJ1Y3Rvcihjb25maWcpXG4gICAgIHtcbiAgICAgICAgc3VwZXIoY29uZmlnKTtcbiAgICAgfVxuIH1cbiIsIlxudmFyIENvbnRlbnRSZXNvdXJjZSA9IHJlcXVpcmUoJy4vY29udGVudC1yZXNvdXJjZScpLmRlZmF1bHQ7XG5tb2R1bGUuZXhwb3J0cy5Db250ZW50UmVzb3VyY2UgPSBDb250ZW50UmVzb3VyY2U7XG4iXX0=
