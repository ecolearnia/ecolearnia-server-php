// When second argument (array) is provided then this becomes a definition,
// otherwise it is a loading
var pathBase = '/ecolearnia';
angular.module('mainApp', ['ngRoute', 'ngCookies', 'ecofy-core', 'ecofy-account'])
.config(['$routeProvider', function($routeProvider) {

    $routeProvider.when('/', {
      controller: 'MainController as mainCtrl',
      templateUrl: pathBase + '/partials/main_home.html'
    })
    .when('/home', {
      controller: 'MainController as accountCtrl',
      templateUrl: pathBase + '/partials/main_home.html'
    })
    .otherwise({redirectTo: '/'});
  }])

/**
 * Frame controller than handles the account in session
 */
.controller('FrameController', ['$window', 'AuthService'
    , function($window, AuthService)
{
  var self = this;

  AuthService.fetchMyAccount()
  .then(function(account) {
    self.session = account;
  })
  .catch(function(error) {

  });

  this.showProfile = function() {
    AuthService.fetchMyAccount()
    .then(function(account) {
      $window.location.href = pathBase + '/main.html#/account/' + account.uuid + '/form';
    })
    .catch(function(error) {
      alert(JSON.stringify(error, null, 2));
    });
  }

  this.openPage = function(module, page) {
    AuthService.fetchMyAccount()
    .then(function(account) {
      $window.location.href = pathBase + '/main.html#/' + module + '/' + account.uuid + '/' + page;
    })
    .catch(function(error) {
      alert(JSON.stringify(error, null, 2));
    });
  }

  this.signout = function() {
    AuthService.signout()
    .then(function(data) {
      $window.location.href = pathBase + '/main.html#/login';
    })
    .catch(function(error) {
      alert(JSON.stringify(error, null, 2));
    });
  }

}]);
