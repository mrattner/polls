/* global angular, baseUrl */

/**
 * PollCreateCtrl
 *
 * Controller for the poll creation page.
 */
(function () {
    'use strict';

    function PollCreateCtrl($scope, $http) {
        // Start with two answer textboxes.
        // Note that the options must be objects in order to use ng-repeat.
        $scope.options = [{text: ''}, {text: ''}];

        /**
         * Submits the new poll data to the server via POST.
         */
        $scope.createPoll = function () {
            var pollData = {
                'title': $scope.title,
                'question': $scope.question
            };

            // Map scope options to correct format
            pollData.answers = $scope.options.map(function (option) {
                return option.text;
            });

            $http.post(baseUrl + 'services/polls', pollData)
                .success(function () {
                    $scope.successMsg = 'Poll successfully created.';
                })
                .catch(function (data) {
                    $scope.errorMsg = 'There was a problem creating the poll.' +
                            ' Status: (' + data.status + ') ' + data.statusText;
                });
        };
    }

    angular.module('pollsApp').controller('PollCreateCtrl', PollCreateCtrl);
    PollCreateCtrl.$inject = ['$scope', '$http'];
}());
