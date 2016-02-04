/* global angular, baseUrl */
/**
 * PollAdminCtrl
 *
 * Controller for a single poll as seen by an admin.
 */
(function () {
    'use strict';

    function PollAdminCtrl($scope, $http, $routeParams) {
        // Retrieve votes
        $http.get(baseUrl + 'services/votes/' + $routeParams.pollId)
            .success(function (data) {
                // Create objects from the vote counts using a map operation
                $scope.voteCounts = data.votes.map(
                    function (value) {
                        return {
                            count: value
                        };
                    }
                );

                // Add up all the votes using a reduce operation
                $scope.numVotes = data.votes.reduce(
                    function (previousValue, currentValue) {
                        return previousValue + currentValue;
                    }
                );
            })
            .error(function () {
                $scope.errorMsg = 'Error: Could not retrieve votes information.';
            });

        // Retrieve poll
        $http.get(baseUrl + 'services/polls/' + $routeParams.pollId)
            .success(function (data) {
                $scope.poll = data;
            })
            .error(function () {
                $scope.errorMsg = 'Error: Requested nonexistent poll.';
            });

        /**
         * Deletes all votes for this poll.
         */
        $scope.resetVotes = function () {
            $http.delete(baseUrl + 'services/votes/' + $scope.poll.id)
                .success(function () {
                    $scope.successMsg = 'All votes for "' +
                            $scope.poll.title + '" were reset.';
                })
                .catch(function (data) {
                    $scope.errorMsg = 'There was a problem resetting votes.' +
                            ' Status: (' + data.status + ') ' + data.statusText;
                });
        };
    }

    angular.module('pollsApp').controller('PollAdminCtrl', PollAdminCtrl);
    PollAdminCtrl.$inject = ['$scope', '$http', '$routeParams'];
}());
