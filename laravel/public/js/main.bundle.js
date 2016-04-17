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
     * @param
     */

  }, {
    key: 'save',
    value: function save(params, data) {
      var opts = this.buildCallOpts({ method: 'POST', body: data });

      return fetch(this.baseUrl_, opts).then(checkStatus).then(function (response) {
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm5vZGVfbW9kdWxlcy93YXRjaGlmeS9ub2RlX21vZHVsZXMvYnJvd3NlcmlmeS9ub2RlX21vZHVsZXMvYnJvd3Nlci1wYWNrL19wcmVsdWRlLmpzIiwicmVzb3VyY2VzL2Fzc2V0cy9qcy9hYnN0cmFjdC1yZXNvdXJjZS5qcyIsInJlc291cmNlcy9hc3NldHMvanMvY29udGVudC1yZXNvdXJjZS5qcyIsInJlc291cmNlcy9hc3NldHMvanMvbWFpbi5qcyJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQTs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUNtQkE7Ozs7Ozs7Ozs7Ozs7Ozs7OztJQVdzQjs7Ozs7QUFLakIsV0FMaUIsZ0JBS2pCLENBQVksTUFBWixFQUNBOzBCQU5pQixrQkFNakI7Ozs7Ozs7Ozs7O0FBVUcsU0FBSyxRQUFMLEdBQWdCLE9BQU8sT0FBUCxDQVZuQjtBQVdHLFFBQUksS0FBSyxRQUFMLENBQWMsTUFBZCxDQUFxQixLQUFLLFFBQUwsQ0FBYyxNQUFkLEdBQXFCLENBQXJCLENBQXJCLElBQWdELEdBQWhELEVBQXFEO0FBQ3JELFdBQUssUUFBTCxJQUFpQixHQUFqQixDQURxRDtLQUF6RDs7Ozs7O0FBWEgsUUFtQkcsQ0FBSyxtQkFBTCxHQUEyQjtBQUN2QixlQUFTO0FBQ0wsa0JBQVUsa0JBQVY7QUFDQSx3QkFBZ0Isa0JBQWhCO09BRko7S0FESixDQW5CSDtHQURBOzs7Ozs7OztlQUxpQjs7d0JBc0NiLFFBQ0o7QUFDSSxVQUFJLENBQUMsTUFBRCxJQUFXLENBQUMsT0FBTyxHQUFQLEVBQVk7QUFDeEIsZ0JBQVEsTUFBUixDQUFlLElBQUksS0FBSixDQUFVLGtCQUFWLENBQWYsRUFEd0I7T0FBNUI7QUFHQSxVQUFJLFVBQVUsS0FBSyxnQkFBTCxDQUFzQixNQUF0QixDQUFWLENBSlI7QUFLSSxVQUFJLE9BQVEsS0FBSyxhQUFMLEVBQVIsQ0FMUjtBQU1JLGFBQU8sTUFBTSxLQUFLLFFBQUwsR0FBZ0IsT0FBTyxHQUFQLEVBQVksSUFBbEMsRUFDSCxJQURHLENBQ0UsV0FERixFQUVILElBRkcsQ0FFRTtlQUFZLFNBQVMsSUFBVDtPQUFaLENBRlQsQ0FOSjs7Ozs7Ozs7Ozt5QkFlSyxRQUFRLE1BQ2I7QUFDSSxVQUFJLE9BQVEsS0FBSyxhQUFMLENBQW1CLEVBQUMsUUFBTyxNQUFQLEVBQWUsTUFBTSxJQUFOLEVBQW5DLENBQVIsQ0FEUjs7QUFHSSxhQUFPLE1BQU0sS0FBSyxRQUFMLEVBQWUsSUFBckIsRUFDSCxJQURHLENBQ0UsV0FERixFQUVILElBRkcsQ0FFRTtlQUFZLFNBQVMsSUFBVDtPQUFaLENBRlQsQ0FISjs7Ozs7Ozs7OzswQkFZTSxRQUNOO0FBQ0ksVUFBSSxPQUFRLEtBQUssYUFBTCxFQUFSLENBRFI7QUFFSSxhQUFPLE1BQU0sS0FBSyxRQUFMLEVBQWUsSUFBckIsRUFDSCxJQURHLENBQ0UsV0FERixFQUVILElBRkcsQ0FFRTtlQUFZLFNBQVMsSUFBVDtPQUFaLENBRlQsQ0FGSjs7Ozs7Ozs7Ozs0QkFXTyxRQUNQO0FBQ0ksVUFBSSxPQUFRLEtBQUssYUFBTCxDQUFtQixFQUFDLFFBQU8sUUFBUCxFQUFwQixDQUFSLENBRFI7QUFFSSxhQUFPLE1BQU0sS0FBSyxRQUFMLEdBQWdCLE9BQU8sR0FBUCxFQUFZLElBQWxDLEVBQ0gsSUFERyxDQUNFLFdBREYsRUFFSCxJQUZHLENBRUU7ZUFBWSxTQUFTLElBQVQ7T0FBWixDQUZULENBRko7Ozs7Ozs7OzsyQkFVTyxRQUNQO0FBQ0ksYUFBTyxLQUFLLE1BQUwsQ0FBWSxNQUFaLENBQVAsQ0FESjs7Ozs7Ozs7Ozs7cUNBU2lCLFFBQ2pCO0FBQ0csVUFBSSxlQUFlLEVBQWYsQ0FEUDtBQUVHLFdBQUksSUFBSSxHQUFKLElBQVcsTUFBZixFQUNBO0FBQ0ssWUFBSSxRQUFRLEtBQVIsRUFBZTtBQUNmLHVCQUFhLElBQWIsQ0FBa0IsTUFBTSxHQUFOLEdBQVksbUJBQW1CLE9BQU8sR0FBUCxDQUFuQixDQUFaLENBQWxCLENBRGU7U0FBbkI7T0FGTDtBQU1BLFVBQUksYUFBYSxNQUFiLEdBQXNCLENBQXRCLEVBQXlCO0FBQ3pCLGVBQU8sTUFBTSxhQUFhLElBQWIsQ0FBa0IsR0FBbEIsQ0FBTixDQURrQjtPQUE3QjtBQUdBLGFBQU8sRUFBUCxDQVhIOzs7O2tDQWNjLE1BQ2Q7QUFDSSxVQUFJLGFBQWMsRUFBZCxDQURSO0FBRUksdUJBQUUsTUFBRixDQUFTLFVBQVQsRUFBcUIsSUFBckIsRUFBMkIsS0FBSyxtQkFBTCxDQUEzQixDQUZKO0FBR0ksYUFBTyxVQUFQLENBSEo7Ozs7U0FwSGlCOzs7Ozs7QUE0SHRCLFNBQVMsV0FBVCxDQUFxQixRQUFyQixFQUNBO0FBQ0ksTUFBSSxTQUFTLE1BQVQsSUFBbUIsR0FBbkIsSUFBMEIsU0FBUyxNQUFULEdBQWtCLEdBQWxCLEVBQXVCO0FBQ2pELFdBQU8sUUFBUCxDQURpRDtHQUFyRCxNQUVPO0FBQ0gsUUFBSSxRQUFRLElBQUksS0FBSixDQUFVLFNBQVMsVUFBVCxDQUFsQixDQUREO0FBRUgsVUFBTSxRQUFOLEdBQWlCLFFBQWpCLENBRkc7QUFHSCxVQUFNLEtBQU4sQ0FIRztHQUZQO0NBRko7Ozs7Ozs7OztBQ3ZJQzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0lBV3FCOzs7Ozs7O0FBS2pCLFdBTGlCLGVBS2pCLENBQVksTUFBWixFQUNBOzBCQU5pQixpQkFNakI7O2tFQU5pQiw0QkFPUixTQURUO0dBREE7O1NBTGlCOzs7Ozs7OztBQzdCdEIsSUFBSSxrQkFBa0IsUUFBUSxvQkFBUixFQUE4QixPQUE5QjtBQUN0QixPQUFPLE9BQVAsQ0FBZSxlQUFmLEdBQWlDLGVBQWpDIiwiZmlsZSI6ImdlbmVyYXRlZC5qcyIsInNvdXJjZVJvb3QiOiIiLCJzb3VyY2VzQ29udGVudCI6WyIoZnVuY3Rpb24gZSh0LG4scil7ZnVuY3Rpb24gcyhvLHUpe2lmKCFuW29dKXtpZighdFtvXSl7dmFyIGE9dHlwZW9mIHJlcXVpcmU9PVwiZnVuY3Rpb25cIiYmcmVxdWlyZTtpZighdSYmYSlyZXR1cm4gYShvLCEwKTtpZihpKXJldHVybiBpKG8sITApO3ZhciBmPW5ldyBFcnJvcihcIkNhbm5vdCBmaW5kIG1vZHVsZSAnXCIrbytcIidcIik7dGhyb3cgZi5jb2RlPVwiTU9EVUxFX05PVF9GT1VORFwiLGZ9dmFyIGw9bltvXT17ZXhwb3J0czp7fX07dFtvXVswXS5jYWxsKGwuZXhwb3J0cyxmdW5jdGlvbihlKXt2YXIgbj10W29dWzFdW2VdO3JldHVybiBzKG4/bjplKX0sbCxsLmV4cG9ydHMsZSx0LG4scil9cmV0dXJuIG5bb10uZXhwb3J0c312YXIgaT10eXBlb2YgcmVxdWlyZT09XCJmdW5jdGlvblwiJiZyZXF1aXJlO2Zvcih2YXIgbz0wO288ci5sZW5ndGg7bysrKXMocltvXSk7cmV0dXJuIHN9KSIsIi8qXG4gKiBUaGlzIGZpbGUgaXMgcGFydCBvZiB0aGUgRWNvTGVhcm5pYSBwbGF0Zm9ybS5cbiAqXG4gKiAoYykgWW91bmcgU3VrIEFobiBQYXJrIDx5cy5haG5wYXJrQG1hdGhuaWEuY29tPlxuICpcbiAqIEZvciB0aGUgZnVsbCBjb3B5cmlnaHQgYW5kIGxpY2Vuc2UgaW5mb3JtYXRpb24sIHBsZWFzZSB2aWV3IHRoZSBMSUNFTlNFXG4gKiBmaWxlIHRoYXQgd2FzIGRpc3RyaWJ1dGVkIHdpdGggdGhpcyBzb3VyY2UgY29kZS5cbiAqL1xuXG4vKipcbiAqIEVjb0xlYXJuaWEgdjAuMC4yXG4gKlxuICogQGZpbGVvdmVydmlld1xuICogIFRoaXMgZmlsZSBpbmNsdWRlcyB0aGUgZGVmaW5pdGlvbiBvZiBJdGVtUGxheWVyIGNsYXNzLlxuICpcbiAqIEBhdXRob3IgWW91bmcgU3VrIEFobiBQYXJrXG4gKiBAZGF0ZSA1LzEzLzE1XG4gKi9cblxuaW1wb3J0IF8gZnJvbSAnbG9kYXNoJztcblxuIC8qKlxuICAqIEBjbGFzcyBBYnN0cmFjdFJlc291cmNlXG4gICpcbiAgKiBAbW9kdWxlIHN0dWRpb1xuICAqXG4gICogQGNsYXNzZGVzY1xuICAqICBBIGRlZmF1bHQgcmVzb3VyY2UgLlxuICAqXG4gICovXG4gZXhwb3J0IGRlZmF1bHQgY2xhc3MgQWJzdHJhY3RSZXNvdXJjZVxuIHtcbiAgICAgLyoqXG4gICAgICAqIEBwYXJhbSB7b2JqZWN0fSBjb25maWdcbiAgICAgICovXG4gICAgIGNvbnN0cnVjdG9yKGNvbmZpZylcbiAgICAge1xuICAgICAgICAvKipcbiAgICAgICAgICogVGhlIGxvZ2dlclxuICAgICAgICAgKi9cbiAgICAgICAgLy90aGlzLmxvZ2dlcl8gPSBsb2dnZXIuZ2V0TG9nZ2VyKCdDb21wb3NpdGlvbicpO1xuXG4gICAgICAgIC8qKlxuICAgICAgICAgKiBUaGUgYmFzZSB1cmwgb2YgdGhlIHNlcnZpY2VcbiAgICAgICAgICogQHR5cGUge3N0cmluZ31cbiAgICAgICAgICovXG4gICAgICAgIHRoaXMuYmFzZVVybF8gPSBjb25maWcuYmFzZVVybDtcbiAgICAgICAgaWYgKHRoaXMuYmFzZVVybF8uY2hhckF0KHRoaXMuYmFzZVVybF8ubGVuZ3RoLTEpICE9ICcvJykge1xuICAgICAgICAgICAgdGhpcy5iYXNlVXJsXyArPSAnLyc7XG4gICAgICAgIH1cblxuICAgICAgICAvKipcbiAgICAgICAgICogRGVmYXVsdCBodHRwIHJlcXVlc3Qgb3B0aW9ucyAoZmV0Y2ggb3B0aW9ucylcbiAgICAgICAgICogQHR5cGUge09iamVjdH0gQHNlZSBodHRwczovL2RldmVsb3Blci5tb3ppbGxhLm9yZy9lbi1VUy9kb2NzL1dlYi9BUEkvR2xvYmFsRmV0Y2gvZmV0Y2hcbiAgICAgICAgICovXG4gICAgICAgIHRoaXMuZGVmYXVsdFJlcXVlc3RPcHRzXyA9IHtcbiAgICAgICAgICAgIGhlYWRlcnM6IHtcbiAgICAgICAgICAgICAgICAnQWNjZXB0JzogJ2FwcGxpY2F0aW9uL2pzb24nLFxuICAgICAgICAgICAgICAgICdDb250ZW50LVR5cGUnOiAnYXBwbGljYXRpb24vanNvbidcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICB9XG5cbiAgICAgLyoqXG4gICAgICAqIEdldHMgYSByZXNvdXJjZVxuICAgICAgKiBAcGFyYW1cbiAgICAgICovXG4gICAgIGdldChwYXJhbXMpXG4gICAgIHtcbiAgICAgICAgIGlmICghcGFyYW1zIHx8ICFwYXJhbXMuX2lkKSB7XG4gICAgICAgICAgICAgUHJvbWlzZS5yZWplY3QobmV3IEVycm9yKCdfaWQgbm90IHByb3ZpZGVkJykpO1xuICAgICAgICAgfVxuICAgICAgICAgdmFyIHFzdHJpbmcgPSB0aGlzLmJ1aWxkUXVlcnlTdHJpbmcocGFyYW1zKVxuICAgICAgICAgbGV0IG9wdHMgID0gdGhpcy5idWlsZENhbGxPcHRzKCk7XG4gICAgICAgICByZXR1cm4gZmV0Y2godGhpcy5iYXNlVXJsXyArIHBhcmFtcy5faWQsIG9wdHMpXG4gICAgICAgICAgICAudGhlbihjaGVja1N0YXR1cylcbiAgICAgICAgICAgIC50aGVuKHJlc3BvbnNlID0+IHJlc3BvbnNlLmpzb24oKSk7XG4gICAgIH1cblxuICAgICAvKipcbiAgICAgICogU2F2ZXMgYSByZXNvdXJjZSAoUE9TVClcbiAgICAgICogQHBhcmFtXG4gICAgICAqL1xuICAgICBzYXZlKHBhcmFtcywgZGF0YSlcbiAgICAge1xuICAgICAgICAgbGV0IG9wdHMgID0gdGhpcy5idWlsZENhbGxPcHRzKHttZXRob2Q6J1BPU1QnLCBib2R5OiBkYXRhfSk7XG5cbiAgICAgICAgIHJldHVybiBmZXRjaCh0aGlzLmJhc2VVcmxfLCBvcHRzKVxuICAgICAgICAgICAgLnRoZW4oY2hlY2tTdGF0dXMpXG4gICAgICAgICAgICAudGhlbihyZXNwb25zZSA9PiByZXNwb25zZS5qc29uKCkpO1xuICAgICB9XG5cbiAgICAgLyoqXG4gICAgICAqIFF1ZXJpZXMgcmVzb3VyY2VzIChHRVQgbGlzdClcbiAgICAgICogQHBhcmFtXG4gICAgICAqL1xuICAgICBxdWVyeShwYXJhbXMpXG4gICAgIHtcbiAgICAgICAgIGxldCBvcHRzICA9IHRoaXMuYnVpbGRDYWxsT3B0cygpO1xuICAgICAgICAgcmV0dXJuIGZldGNoKHRoaXMuYmFzZVVybF8sIG9wdHMpXG4gICAgICAgICAgICAudGhlbihjaGVja1N0YXR1cylcbiAgICAgICAgICAgIC50aGVuKHJlc3BvbnNlID0+IHJlc3BvbnNlLmpzb24oKSk7XG4gICAgIH1cblxuICAgICAvKipcbiAgICAgICogRGVsZXRlcyBhIHJlc291cmNlIChERUxFVEUpXG4gICAgICAqIEBwYXJhbVxuICAgICAgKi9cbiAgICAgZGVsZXRlKHBhcmFtcylcbiAgICAge1xuICAgICAgICAgbGV0IG9wdHMgID0gdGhpcy5idWlsZENhbGxPcHRzKHttZXRob2Q6J0RFTEVURSd9KTtcbiAgICAgICAgIHJldHVybiBmZXRjaCh0aGlzLmJhc2VVcmxfICsgcGFyYW1zLl9pZCwgb3B0cylcbiAgICAgICAgICAgIC50aGVuKGNoZWNrU3RhdHVzKVxuICAgICAgICAgICAgLnRoZW4ocmVzcG9uc2UgPT4gcmVzcG9uc2UuanNvbigpKTtcbiAgICAgfVxuXG4gICAgIC8qKlxuICAgICAgKiBTYW1lIGFzIHJlbW92ZVxuICAgICAgKi9cbiAgICAgcmVtb3ZlKHBhcmFtcylcbiAgICAge1xuICAgICAgICAgcmV0dXJuIHRoaXMuZGVsZXRlKHBhcmFtcyk7XG4gICAgIH1cblxuICAgICAvKipcbiAgICAgICogUmV0dXJucyBhIHF1ZXJ5IHN0cmluZyAgZXhjbHVkaW5nIHRoZSBfaWQgd2hpY2ggc2hvdWxkIGJlIHVzZWQgaW4gdGhlXG4gICAgICAqIFVSTCBwYXRoLlxuICAgICAgKiBAcGFyYW0ge01hcC48c3RyaW5nLCBzdHJpbmc+fSBrZXkgdmFsdWUgcGFpcnNcbiAgICAgICovXG4gICAgIGJ1aWxkUXVlcnlTdHJpbmcocGFyYW1zKVxuICAgICB7XG4gICAgICAgIHZhciBxdWVyeVN0cmluZ3MgPSBbXTtcbiAgICAgICAgZm9yKHZhciBrZXkgaW4gcGFyYW1zKVxuICAgICAgICB7XG4gICAgICAgICAgICAgaWYgKGtleSAhPT0gJ19pZCcpIHtcbiAgICAgICAgICAgICAgICAgcXVlcnlTdHJpbmdzLnB1c2goa2V5ICsgJz0nICsgZW5jb2RlVVJJQ29tcG9uZW50KHBhcmFtc1trZXldKSk7XG4gICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIGlmIChxdWVyeVN0cmluZ3MubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgcmV0dXJuICc/JyArIHF1ZXJ5U3RyaW5ncy5qb2luKCcmJyk7XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuICcnO1xuICAgICB9XG5cbiAgICAgYnVpbGRDYWxsT3B0cyhvcHRzKVxuICAgICB7XG4gICAgICAgICBsZXQgb3B0c01lcmdlZCAgPSB7fTtcbiAgICAgICAgIF8uYXNzaWduKG9wdHNNZXJnZWQsIG9wdHMsIHRoaXMuZGVmYXVsdFJlcXVlc3RPcHRzXyk7XG4gICAgICAgICByZXR1cm4gb3B0c01lcmdlZDtcbiAgICAgfVxuXG4gfVxuXG5mdW5jdGlvbiBjaGVja1N0YXR1cyhyZXNwb25zZSlcbntcbiAgICBpZiAocmVzcG9uc2Uuc3RhdHVzID49IDIwMCAmJiByZXNwb25zZS5zdGF0dXMgPCAzMDApIHtcbiAgICAgICAgcmV0dXJuIHJlc3BvbnNlO1xuICAgIH0gZWxzZSB7XG4gICAgICAgIHZhciBlcnJvciA9IG5ldyBFcnJvcihyZXNwb25zZS5zdGF0dXNUZXh0KVxuICAgICAgICBlcnJvci5yZXNwb25zZSA9IHJlc3BvbnNlO1xuICAgICAgICB0aHJvdyBlcnJvcjtcbiAgICB9XG59XG4iLCIvKlxuICogVGhpcyBmaWxlIGlzIHBhcnQgb2YgdGhlIEVjb0xlYXJuaWEgcGxhdGZvcm0uXG4gKlxuICogKGMpIFlvdW5nIFN1ayBBaG4gUGFyayA8eXMuYWhucGFya0BtYXRobmlhLmNvbT5cbiAqXG4gKiBGb3IgdGhlIGZ1bGwgY29weXJpZ2h0IGFuZCBsaWNlbnNlIGluZm9ybWF0aW9uLCBwbGVhc2UgdmlldyB0aGUgTElDRU5TRVxuICogZmlsZSB0aGF0IHdhcyBkaXN0cmlidXRlZCB3aXRoIHRoaXMgc291cmNlIGNvZGUuXG4gKi9cblxuLyoqXG4gKiBFY29MZWFybmlhIHYwLjAuMlxuICpcbiAqIEBmaWxlb3ZlcnZpZXdcbiAqICBUaGlzIGZpbGUgaW5jbHVkZXMgdGhlIGRlZmluaXRpb24gb2YgSXRlbVBsYXllciBjbGFzcy5cbiAqXG4gKiBAYXV0aG9yIFlvdW5nIFN1ayBBaG4gUGFya1xuICogQGRhdGUgNS8xMy8xNVxuICovXG5cbiBpbXBvcnQgQWJzdHJhY3RSZXNvdXJjZSBmcm9tICcuL2Fic3RyYWN0LXJlc291cmNlJztcblxuIC8qKlxuICAqIEBjbGFzcyBDb250ZW50UmVzb3VyY2VcbiAgKlxuICAqIEBtb2R1bGUgc3R1ZGlvXG4gICpcbiAgKiBAY2xhc3NkZXNjXG4gICogIENvbnRlbnQgUmVzb3VyY2UuXG4gICpcbiAgKi9cbiBleHBvcnQgZGVmYXVsdCBjbGFzcyBDb250ZW50UmVzb3VyY2UgZXh0ZW5kcyBBYnN0cmFjdFJlc291cmNlXG4ge1xuICAgICAvKipcbiAgICAgICogQHBhcmFtIHtvYmplY3R9IGNvbmZpZ1xuICAgICAgKi9cbiAgICAgY29uc3RydWN0b3IoY29uZmlnKVxuICAgICB7XG4gICAgICAgIHN1cGVyKGNvbmZpZyk7XG4gICAgIH1cbiB9XG4iLCJcbnZhciBDb250ZW50UmVzb3VyY2UgPSByZXF1aXJlKCcuL2NvbnRlbnQtcmVzb3VyY2UnKS5kZWZhdWx0O1xubW9kdWxlLmV4cG9ydHMuQ29udGVudFJlc291cmNlID0gQ29udGVudFJlc291cmNlO1xuIl19
