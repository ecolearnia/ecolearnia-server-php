
var basePath = '/api';

var app = angular.module('adminApp');
app.controller('AccountController', [
    '$routeParams', '$location', 'AuthService', 'ReferenceResource', 'AccountResource', 'RelationResource'
    , function($routeParams, $location, AuthService, ReferenceResource, AccountResource, RelationResource)
{
    var self = this;
    self.accounts = [];
    self.account = null;
    self.queryCriteria = null;
    self.queryResult = null;

    self.temp = {
        email: null,
        dob: {
            month: null,
            day: null,
            year: null
        }
    };

    self.references = {};
    loadReferences();

    function loadReferences()
    {
        self.references.days = new Array(31);
        self.references.months = ReferenceResource.lookup('months');
        self.references.genders = ReferenceResource.lookup('genders');;
    }

    // Loads either the account in session or the list of accounts
    if ($routeParams.accountId && $routeParams.accountId != 'new') {
    	self.account = AccountResource.get({id: $routeParams.accountId}, function(data) {
            // nothing to do, data is updated when async is returned.
            self.temp.dob = decomposeIsoDate(data.profile.dob);
        }, function(error) {
            alert(JSON.stringify(error));
        });
    } else {
	    // initialize
	    query();
	}

    this.go = function(path) {
        return $location.path( path );
    };

    this.goToPage = function(pageIdx) {
        var retval = '/?_page=' + pageIdx;
        if (self.queryResult.limit) {
            retval += '&limit=' + self.queryResult.limit;
        }
        $location.path('/').search('_page', pageIdx).search('_limit', self.queryResult.limit);
    };

    /**
     * Is any user selected?
     */
    this.selectedAccount = function() {
        return self.account;
    };

    /**
     * Removes an account
     */
    this.remove = function(account) {
        AccountResource.remove({id:account.uuid}, function(data) {
            // nothing to do, data is updated when async is returned.
            // temp:
            alert('Account: ' + account.displayName + ' was removed. Please refresh page');
        }, function(error) {
            alert(JSON.stringify(error));
        });
    };

    this.doQuery = function(id) {
        query();
    };

    this.getAccount = function(id) {
    	self.account = AccountResource.get(id);
    	if (!self.account) {
    		alert ('Not found for ' + id);
    	}
    };

    /**
     * Submit for update
     */
    this.submit = function() {
        //self.account.primaryEmail = [self.temp.email];
        var dob = new Date(self.temp.dob.year, self.temp.dob.month, self.temp.dob.day);
        self.account.profile.dob = moment(dob).format();
        if (self.account.uuid) {
            // Update existing
            delete self.account._id;
            AccountResource.update({id: self.account.uuid}, self.account);
            self.retrieveRelations();
        } else {
            // Create new
            var newAccount = new AccountResource(self.account);
            newAccount.kind = 'normal';
            newAccount.auth = {
                authSource: 'local',
                username: 'test',
                security: { password: 'test' }
            };
            newAccount.$save();
        }
    };

    /**
     * Query accounts
     */
    function query(criteria) {
        var qparms = $location.search();
        var queryArgs = {
            _meta: 'true',
            _page: qparms._page,
            _limit: qparms._limit
        };

        // Fetch from remote
        self.accounts = AccountResource.query2(queryArgs, function(data) {
            self.queryResult = data;
            self.queryResult.numPages = Math.ceil(self.queryResult.totalHits / self.queryResult.limit);
            self.accounts = data.documents;
        }, function(error) {
            alert(JSON.stringify(error));
        });
    };


    /**
     * Parses and decomposes date (in ISO8601) into object with
     * year, month, day, hours, minutes, seconds
     */
    function decomposeIsoDate(isoDate)
    {
        var date = new Date(isoDate);
        var dateObj = {};
        if (date) {
            dateObj.year = date.getUTCFullYear();
            dateObj.month = date.getUTCMonth(); // 0-base
            dateObj.day = date.getUTCDate();
            dateObj.hours = date.getUTCHours();
            dateObj.minutes = date.getUTCMinutes();
            dateObj.seconds = date.getUTCSeconds();
        }
        return dateObj;
    }

}]);


app.controller('RelationsController', [
    '$http', '$routeParams', '$location', 'AuthService', 'ReferenceResource', 'AccountResource', 'RelationResource'
    , function($http, $routeParams, $location, AuthService, ReferenceResource, AccountResource, RelationResource)
{
    self = this;
    /* relations */
    self.relations = [];
    self.session = null;

    AuthService.fetchMyAccount()
    .then(function(account) {
      self.session = account;
      retrieveRelations();
    })
    .catch(function(error) {
    });


    /**
     * Retrieve relations
     */
    function retrieveRelations(criteria) {
        var qparms = $location.search();
        var queryArgs = {
            accountId: self.session.uuid,
            _meta: 'true',
            _page: qparms._page,
            _limit: qparms._limit,
            _q: ''
        };

        // Fetch from remote
        RelationResource.query2(queryArgs, function(data) {
            //data.documents;
            self.relations = normalizeResult(self.session.uuid, data.documents);
        }, function(error) {
            alert(JSON.stringify(error));
        });
    };

    /**
     * Normalizes the resut (list of relations) such that includes
     * properties:
     * thisRole : the role of the account in session
     * counterpartAccount : the other account
     * counterpartRole : the role of the other account
     */
    function normalizeResult(thisUuid, result)
    {
        for(i=0; i < result.length; i++) {
            if (thisUuid == result[i].account1Uuid) {
                result[i].thisRole = result[i].role1;
                result[i].counterpartAccount = result[i].account2;
                result[i].counterpartRole = result[i].role2;
            } else {
                result[i].thisRole = result[i].role2;
                result[i].counterpartAccount = result[i].account1;
                result[i].counterpartRole = result[i].role1;
            }
        }
        return result;
    }
}]);

app.controller('ImportController', [
    '$http', '$routeParams', '$location', 'AuthService', 'ReferenceResource', 'AccountResource'
    , function($http, $routeParams, $location, AuthService, ReferenceResource, AccountResource)
{
    var self = this;
    self.dataToImport = null;
    self.validated = {
        errors: null,
        records: null
    };

    /**
     * Submit dataToImport to server for validation
     */
    this.process = function(mode)
    {
        var objs = Papa.parse(self.dataToImport, {header: true});
        preprocessInput(objs.data);

        var payload = {
            type: "account",
            mode: mode,
            onmatch: "skip",
            data: objs.data
        }
        $http.post(basePath + '/import', payload)
        .then(function(response) {
            self.validated = response.data;
        })
        .catch(function(error) {
            // Error wrapped by $http containing config, data, status, statusMessage, etc.
            //if (error.data)
            self.validated.errors = error;
            throw error;
        });
    }

    /**
     *
     * Convert those properties in which names has dot notation
     * into nested object
     *
     * @param objs {Array<Object>}
     */
    function preprocessInput(objs)
    {
        for(var i=0; i < objs.length; i++)
        {
            var obj = objs[i];
            for (var prop in obj) {
                if (prop.indexOf('.') !== -1) {
                    // create nested property
                    var val = obj[prop];
                    dotAccess(obj, prop, val);
                    delete obj[prop];
                }
            }
        }
    }

    /**
     * Converts those properties
     * Precondition: the json should be an array of objects, the objects can
     * contain nested objects but should not contain nested array
     */
    function postprocessResponse(json)
    {
        for(var i=0; i < json.length; i++)
        {
            json[i]
        }
    }

}]);
