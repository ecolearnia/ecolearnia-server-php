var pathBase = '/pages';
var app = angular.module('mainApp');
app.controller('SigninController', [
    '$window', '$location', 'AuthService'
    , function($window, $location, AuthService)
{
    var self = this;
    self.account;

    self.credentials = {
        username:'',
        password:''
    };

    this.redir = function(path) {
        //$location.path( path );
        $window.location.href = path;
    };

    this.signin = function() {
        AuthService.signin(self.credentials)
        .then(function(authenticated) {
            // authenticated
            if (authenticated) {
                self.errorMessage = null;
                self.redir( pathBase + '/admin.html' );
            } else {
                self.errorMessage = 'Invalid username or password';
            }
        })
        .catch(function(error) {
            if (error instanceof Error) {
                self.errorMessage = error.toString();
            }
            self.errorMessage = JSON.stringify(error, null, 2);
        });
        //$location.path( path );
    };

}]);

app.controller('SignupController', [
    '$window', '$location', 'AuthService', 'ReferenceResource'
    , function($window, $location, AuthService, ReferenceResource)
{

    var self = this;
    self.account;
    // Temporary staging used prior submission
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

    this.redir = function(path) {
        //$location.path( path );
        $window.location.href = path;
    };

    /**
     * Calls signup remote endpoint
     */
    this.signup = function() {
        self.account.primaryEmails = [self.temp.email];
        self.account.profile.dob = new Date(self.temp.dob.year, self.temp.dob.month, self.temp.dob.day);
        self.account.auth.username = self.temp.email;
        AuthService.signup(self.account)
        .then(function(account) {
            if (account) {
                self.errorMessage = null;
                self.redir( pathBase + '/admin.html' );
            } else {
                self.errorMessage = 'Invalid username or password';
            }
        })
        .catch(function(error) {
            if (error instanceof Error) {
                self.errorMessage = error.toString();
            }
            self.errorMessage = JSON.stringify(error, null, 2);
        });
    };

}]);
