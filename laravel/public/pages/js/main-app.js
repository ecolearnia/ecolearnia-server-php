/**
 * The main application module
 */
angular.module('mainApp', ['ngRoute', 'ngCookies', 'ngMaterial', 'ecofy-core', 'ecofy-account'])
.config(['$routeProvider', function($routeProvider) {
    var pathBase = '/pages';
    $routeProvider.when('/login', {
      //template: '<h5>This is the default route</h5>'
      controller: 'SigninController as signinCtrl',
      templateUrl: pathBase + '/partials/signin.html'
    })
    .when('/signup', {
      controller: 'SignupController as signupCtrl',
      templateUrl: pathBase + '/partials/signup.html'
    })
    .when('/home', {
      controller: 'AccountController as accountCtrl',
      templateUrl: pathBase + '/partials/account_details.html'
    })
    .when('/me/profile', {
      controller: 'AccountController as accountCtrl',
      templateUrl: pathBase + '/partials/account_form.html'
    })
    .otherwise({redirectTo: '/login'});
  }])

/**
 * For the Angular matrial design
 */
.config(function($mdThemingProvider) {
  $mdThemingProvider.theme('default')
    .primaryPalette('green')
    .accentPalette('orange');
})

/**
 * Frame controller than handles the account in session
 */
.controller('FrameController', ['$location', 'AuthService'
    , function($location, AuthService)
{
  var self = this;
  AuthService.fetchMyAccount()
  .then(function(account) {
    self.session = account;
  })
  .catch(function(error) {

  })
}]);
