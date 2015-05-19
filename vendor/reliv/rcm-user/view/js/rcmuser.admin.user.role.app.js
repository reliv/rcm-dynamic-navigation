'use strict';

angular.module('rcmuserAdminUserRoleApp', ['ui.bootstrap', 'rcmuserCore'])

    .controller('rcmuserAdminUserRole', ['$scope', '$modal', 'RcmUserHttp', 'RcmUserResult', 'RcmResults',
        function ($scope, $modal, RcmUserHttp, RcmUserResult, RcmResults) {
            var self = this;
            self.url = {
                user: "<?php echo $this->url('RcmUserAdminApiUser', array()); ?>",
                roles: "<?php echo $this->url('RcmUserAdminApiAclRulesByRoles', array()); ?>"
            };

            self.rcmUserHttp = new RcmUserHttp();

            $scope.alerts = self.rcmUserHttp.alerts;
            $scope.loading = self.rcmUserHttp.loading;

            eval('var user = <?php echo json_encode(userResult.getData()); ?>');

            $scope.user = user;

            $scope.oneAtATime = false;
        }
    ]);
