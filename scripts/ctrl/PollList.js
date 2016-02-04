/* global angular, baseUrl */

/**
 * PollListCtrl
 *
 * Controller for the list of all polls.
 */
(function () {
    'use strict';

    function PollListCtrl($scope, $http) {
        $http.get(baseUrl + 'services/polls')
            .success(function (data) {
                $scope.polls = data;
            })
            .catch(function (data) {
                $scope.errorMsg = 'Error: Unable to retrieve poll information.'
                        + ' Status: (' + data.status + ') ' + data.statusText;
            });

        /**
         * Send a request to delete the poll with the specified ID.
         * @param {int} id ID of the poll to delete
         * @param {int} index Index of the poll in the scope's poll list
         */
        $scope.deletePoll = function (id, index) {
            if (confirm('Do you really want to delete this poll? ' +
                    'All its votes will be deleted too.')) {
                $http.delete(baseUrl + 'services/polls/' + id)
                    .success(function () {
                        // Remove the poll's representation from our viewmodel
                        $scope.polls.splice(index, 1);
                        $scope.successMsg = 'Deleted poll #' + id;
                    })
                    .catch(function (data) {
                        $scope.errorMsg = 'Failed to delete poll #' + id + '. Status: ('
                                + data.status + ') ' + data.statusText;
                    });
            }
        };
    }

    angular.module('pollsApp').controller('PollListCtrl', PollListCtrl);
    PollListCtrl.$inject = ['$scope', '$http'];
}());
