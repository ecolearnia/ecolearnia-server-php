var coreModule = angular.module('ecofy-core');

/**
 * Service that provides REST access for Account resource
 */
coreModule.factory('ReferenceResource', ['$resource', 'AuthService',
	function($resource, AuthService)
{
	var basePath = '/api/references';
	var token = AuthService.getToken();

	var self = this;
	this.db = {
        genders: [],
        days: [],
        months: [],
    };
	this.db.months = [
		{value: '0', name:'Januray'},
		{value: '1', name:'February'},
		{value: '2', name:'March'},
		{value: '3', name:'April'},
		{value: '4', name:'May'},
		{value: '5', name:'June'},
		{value: '6', name:'July'},
		{value: '7', name:'August'},
		{value: '8', name:'September'},
		{value: '9', name:'October'},
		{value: '10', name:'November'},
		{value: '11', name:'December'},
	];

	this.db.genders = [
		{value: '1', name:'Female'},
		{value: '2', name:'Male'},
		{value: '3', name:'Other'},
	];

	var resource = $resource(basePath + '/:id', {},
		{
			'get': { method:'GET', headers: { 'Authorization': token } },
			'save': { method:'POST', headers: { 'Authorization': token } },
	        'query': { method:'GET', isArray: true, headers: { 'Authorization': token } },
	        'remove': { method:'DELETE', headers: { 'Authorization': token } },
	        'deleve': { method:'DELETE', headers: { 'Authorization': token } },
	        'update': { method:'PUT', headers: { 'Authorization': token } },
	        'query2': { method:'GET', headers: { 'Authorization': token }, paramDefaults: { 'meta': 'true'} }
	    });

	resource.lookup = function(name, locale)
	{
		return self.db[name];
	}

	return resource;

}]);
