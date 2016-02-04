/* global angular */

/**
 * App module
 */
(function () {
    'use strict';

    // Define app module with dependency on ng-route module
    var pollsApp = angular.module('pollsApp', ['ngRoute']);

    // Define view routes
    pollsApp.config(['$routeProvider', function ($routeProvider) {
        $routeProvider
            .when('/polls', {
                templateUrl: 'partials/poll-list.html',
                controller: 'PollListCtrl'
            })
            .when('/admin', {
                templateUrl: 'partials/admin-list.html',
                controller: 'PollListCtrl'
            })
            .when('/admin/create', {
                templateUrl: 'partials/poll-create.html',
                controller: 'PollCreateCtrl'
            })
            .when('/admin/:pollId', {
                templateUrl: 'partials/poll-detail.html',
                controller: 'PollAdminCtrl'
            })
            .when('/vote/:pollId', {
                templateUrl: 'partials/poll-vote.html',
                controller: 'PollVoteCtrl'
            })
            .when('/about', {
                templateUrl: 'partials/about.html'
            })
            .when('/about/details', {
                templateUrl: 'partials/about-detail.html'
            })
            .otherwise('/polls');
    }]);
}());
