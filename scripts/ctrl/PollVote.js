/* global angular, baseUrl */

/**
 * PollVoteCtrl
 *
 * Controller for the voting page for a poll.
 */
(function () {
    'use strict';

    function PollVoteCtrl($scope, $http, $routeParams) {
        $http.get(baseUrl + 'services/polls/' + $routeParams.pollId)
            .success(function (data) {
                $scope.poll = data;
                // Need to map poll answers to objects because primitives don't
                // work well with ng-repeat.
                $scope.options = $scope.poll.answers.map(function (answer) {
                    return {'text': answer};
                });
            })
            .error(function () {
                $scope.errorMsg = 'Error: Requested nonexistent poll.';
            });

        /**
         * Submits a vote to the server.
         */
        $scope.vote = function () {
            $http.post(baseUrl + 'services/votes/' + $scope.poll.id + '/' + $scope.choice)
                .success(function () {
                    $scope.successMsg = 'Your vote for "' +
                            $scope.poll.answers[$scope.choice] + '" was submitted.';
                })
                .error(function () {
                    $scope.errorMsg = 'There was a problem submitting your vote.' +
                            ' It was not recorded.';
                });
        };
    }

    angular.module('pollsApp').controller('PollVoteCtrl', PollVoteCtrl);
    PollVoteCtrl.$inject = ['$scope', '$http', '$routeParams'];
}());
