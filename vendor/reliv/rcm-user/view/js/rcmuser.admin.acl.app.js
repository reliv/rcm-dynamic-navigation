'use strict';

angular.module('rcmuserAdminAclApp', ['ui.bootstrap', 'rcmuserCore'])

    .factory('rcmuserAdminAclData', ['rcmUserConfig', function (rcmUserConfig) {

        var self = this;

        self.url = rcmUserConfig.url;

        self.superAdminRole = '<?php echo $superAdminRoleId; ?>';
        self.guestRole = '<?php echo $guestRoleId; ?>';

        return self;

    }])
    .controller('rcmuserAdminAclRoles', ['$scope', '$log', '$modal', 'RcmUserHttp', 'RcmUserResult', 'RcmResults', 'getNamespaceRepeatString', 'rcmuserAdminAclData',
        function ($scope, $log, $modal, RcmUserHttp, RcmUserResult, RcmResults, getNamespaceRepeatString, rcmuserAdminAclData) {

            var getRoles;
            var self = this;
            self.url = rcmuserAdminAclData.url;

            $scope.rcmUserHttp = new RcmUserHttp();

            $scope.roleData = {};

            $scope.superAdminRole = rcmuserAdminAclData.superAdminRole;
            $scope.guestRole = rcmuserAdminAclData.guestRole;

            $scope.oneAtATime = true;

            $scope.resourceCount = 0;

            $scope.levelRepeat = getNamespaceRepeatString;

            /* <ADD_RULE> */
            /**
             * Open add rule modal
             *
             * @param size
             * @param roleData
             * @param resources
             */
            $scope.openAddRule = function (size, roleData, resources) {

                var self = this;

                self.controller = function ($scope, $modalInstance) {

                    $scope.rcmUserHttp = new RcmUserHttp();
                    $scope.rcmUserHttp.test = true;

                    $scope.status = {
                        isopen: false
                    };

                    $scope.toggleDropdown = function ($event, isopen) {
                        $event.preventDefault();
                        $event.stopPropagation();
                        $scope.status.isopen = isopen;
                    };

                    $scope.roleData = roleData;
                    $scope.resources = resources;
                    $scope.ruleData = {
                        rule: 'allow',
                        roleId: roleData.role.roleId,
                        resourceId: '',
                        privilege: ''
                    };

                    $scope.cancel = function () {
                        $modalInstance.dismiss('cancel');
                    };

                    $scope.close = function () {
                        $modalInstance.close();
                    };

                    var onSuccess = function (data, status) {

                        $scope.close();
                    };

                    var isValid = function () {

                        if (!$scope.resources[$scope.ruleData.resourceId]) {

                            $scope.rcmUserHttp.alerts.add(new RcmUserResult(0, ['Resource is not valid.'], null));
                            return false;
                        }

                        return true;
                    };

                    $scope.addRule = function () {

                        if (!isValid()) {
                            return;
                        }

                        addRule(
                            $scope.rcmUserHttp,
                            $scope.ruleData,
                            onSuccess
                        );
                    };

                };

                var modal = $modal.open({
                    templateUrl: 'addRule.html',
                    controller: self.controller,
                    size: size
                });

            };

            /**
             * Add Rule
             *
             * @param ruleData
             */
            var addRule = function (rcmUserHttp, ruleData, onSuccess, onFail) {

                var apiSuccess = function (data, status) {

                    self.getRoles(onSuccess, onFail);
                };

                var config = {
                    method: 'POST',
                    url: self.url.rule,
                    data: ruleData
                };

                rcmUserHttp.execute(config, apiSuccess);
            };
            /* </ADD_RULE> */

            /* <REMOVE_RULE> */
            $scope.openRemoveRule = function (size, ruleData, resourceData) {

                var self = this;

                self.controller = function ($scope, $modalInstance) {

                    $scope.rcmUserHttp = new RcmUserHttp();

                    $scope.ruleData = ruleData;
                    $scope.resourceData = resourceData;

                    $scope.cancel = function () {
                        $modalInstance.dismiss('cancel');
                    };

                    $scope.close = function () {
                        $modalInstance.close();
                    };

                    var onSuccess = function (data, status) {

                        $scope.close();
                    };

                    $scope.removeRule = function () {

                        removeRule(
                            $scope.rcmUserHttp,
                            $scope.ruleData,
                            onSuccess
                        );
                    };
                };

                var modal = $modal.open({
                    templateUrl: 'removeRule.html',
                    controller: self.controller,
                    size: size
                });
            };

            var removeRule = function (rcmUserHttp, ruleData, onSuccess, onFail) {

                var apiSuccess = function (data, status) {

                    self.getRoles(onSuccess, onFail);
                };

                var config = {
                    method: 'DELETE',
                    url: self.url.rule + "/" + JSON.stringify(ruleData),
                    data: ruleData
                };

                rcmUserHttp.execute(config, apiSuccess, onFail);
            };
            /* </REMOVE_RULE> */

            /* <ADD_ROLE> */
            $scope.openAddRole = function (size, roles) {
                var self = this;

                self.controller = function ($scope, $modalInstance) {

                    $scope.rcmUserHttp = new RcmUserHttp();

                    $scope.roles = roles;
                    $scope.roleData = {
                        roleId: '',
                        parentRoleId: '',
                        description: ''
                    };

                    $scope.cancel = function () {
                        $modalInstance.dismiss('cancel');
                    };

                    $scope.close = function () {
                        $modalInstance.close();
                    };

                    $scope.getNamespaceRepeatString = getNamespaceRepeatString;

                    var onSuccess = function (data, status) {

                        $scope.close();
                    };

                    var isValid = function () {

                        return true;
                    };

                    $scope.addRole = function () {

                        if (!isValid()) {
                            return;
                        }

                        addRole(
                            $scope.rcmUserHttp,
                            $scope.roleData,
                            onSuccess
                        );
                    };
                };

                var modal = $modal.open({
                    templateUrl: 'addRole.html',
                    controller: self.controller,
                    size: size
                });
            };

            var addRole = function (rcmUserHttp, roleData, onSuccess, onFail) {

                var apiSuccess = function (data, status) {

                    self.getRoles(onSuccess, onFail);
                };

                var config = {
                    method: 'POST',
                    url: self.url.role,
                    data: roleData
                };

                rcmUserHttp.execute(config, apiSuccess, onFail);
            };
            /* </ADD_ROLE> */

            /* <REMOVE_ROLE> */
            $scope.openRemoveRole = function (size, roleData) {

                var self = this;

                self.controller = function ($scope, $modalInstance) {

                    $scope.rcmUserHttp = new RcmUserHttp();

                    $scope.roleData = roleData;

                    $scope.cancel = function () {
                        $modalInstance.dismiss('cancel');
                    };

                    $scope.close = function () {
                        $modalInstance.close();
                    };

                    var onSuccess = function (data, status) {

                        $scope.close();
                    };

                    $scope.removeRole = function () {

                        removeRole(
                            $scope.rcmUserHttp,
                            $scope.roleData.role,
                            onSuccess
                        );
                    };
                };

                var modal = $modal.open({
                    templateUrl: 'removeRole.html',
                    controller: self.controller,
                    size: size
                });
            };

            var removeRole = function (rcmUserHttp, roleData, onSuccess, onFail) {

                var apiSuccess = function (data, status) {

                    self.getRoles(onSuccess, onFail);
                };

                var config = {
                    method: 'DELETE',
                    url: self.url.role + "/" + roleData.roleId
                };

                rcmUserHttp.execute(config, apiSuccess, onFail);
            };
            /* </REMOVE_ROLE> */

            /* <ROLES> */
            self.getRoles = function (onSuccess, onFail) {

                //$log.log('getRoles');

                var apiSuccess = function (data, status) {

                    //$log.log(data);
                    $scope.roles = data.data;

                    if (typeof(onSuccess) === 'function') {

                        onSuccess(data, status);
                    }
                };

                var config = {
                    method: 'GET',
                    url: self.url.rulesByroles
                };

                $scope.rcmUserHttp.execute(config, apiSuccess, onFail);
            };


            self.getRoles(
                function (data, status) {

                    $scope.roles = data.data;
                }
            );
            /* </ROLES> */

            /* <RESOURCES> */
            self.getResources = function (onSuccess, onFail) {

                var config = {
                    method: 'GET',
                    url: self.url.resources
                };

                $scope.rcmUserHttp.execute(config, onSuccess, onFail);
            };

            self.getResources(
                function (data, status) {

                    $scope.resources = data.data;
                    $scope.resourceCount = $scope.resources.length;
                }
            );
            /* </RESOURCES> */
        }])
    .filter('resourceFilter', function () {

        var compareStr = function (stra, strb) {
            stra = ("" + stra).toLowerCase();
            strb = ("" + strb).toLowerCase();

            return stra.indexOf(strb) !== -1;
        };

        return function (input, query) {
            if (!query) {
                return input
            }
            var result = [];

            angular.forEach(input, function (resource) {
                if (compareStr(resource.resource.resourceId, query)
                    || compareStr(resource.resource.name, query)) {
                    result.push(resource);
                }
            });

            return result;
        };
    });
