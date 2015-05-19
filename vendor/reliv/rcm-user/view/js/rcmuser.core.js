'use strict';

var rcmUser = {};

rcmUser.core = {};

/**
 * Global cache
 * @type {{empty: Function, getCache: Function, roles: {}, selectedRoles: {}}}
 */
rcmUser.cache = {
    empty: function (cacheKey) {
        var key;
        for (key in rcmUser.cache[cacheKey]) {
            if (rcmUser.cache[cacheKey].hasOwnProperty(key)) {
                return false;
            }
        }
        return true;
    },
    getCache: function (cacheKey) {

        if (rcmUser.cache[cacheKey]) {
            return rcmUser.cache[cacheKey];
        }

        return {};
    },
    rolesState: 'Initial',
    roles: null,
    rolesIndex: null,
    selectedRoles: {}
};

/**
 * Global Event Manager
 * @type {{events: {}, on: Function, trigger: Function}}
 */
rcmUser.eventManager = {

    events: {},

    on: function (event, method) {

        if (!this.events[event]) {
            this.events[event] = [];
        }

        this.events[event].push(method);
    },

    trigger: function (event, args) {

        if (this.events[event]) {
            jQuery.each(
                this.events[event],
                function (index, value) {
                    value(args);
                }
            );
        }
    },
    hasEvents: function (event) {

        if (!this.events[event]) {
            return false;
        }

        if (this.events[event].length > 0) {
            return true;
        }

        return false;
    }
};

angular.module('rcmuserCore', [])

    .factory(
    'rcmUserConfig', function () {

        var self = this;

        self.url = {
            defaultUserRoles: "<?php echo $this->url('RcmUserAdminApiAclDefaultUserRole', array()); ?>",
            resources: "<?php echo $this->url('RcmUserAdminApiAclResources', array()); ?>",
            role: "<?php echo $this->url('RcmUserAdminApiAclRole', array()); ?>",
            rulesByroles: "<?php echo $this->url('RcmUserAdminApiAclRulesByRoles', array()); ?>",
            rule: "<?php echo $this->url('RcmUserAdminApiAclRule', array()); ?>",
            user: "<?php echo $this->url('RcmUserAdminApiUser', array()); ?>",
            users: "<?php echo $this->url('RcmUserAdminApiUser', array()); ?>",
            validUserStates: "<?php echo $this->url('RcmUserAdminApiUserValidUserStates', array()); ?>"
        };

        return self;

    }
)
    .factory(
    'RcmUserHttp',
    [
        '$log',
        '$http',
        'RcmUserResult',
        'RcmResults',
        function ($log, $http, RcmUserResult, RcmResults) {

            var RcmUserHttp = function () {

                var self = this;
                self.http = $http;
                self.comErrorMessage = 'There was an error talking to the server: ';
                self.includeSuccessAlerts = false;
                self.loading = 0;
                self.alerts = new RcmResults();

                self.loadingOn = function () {

                    self.loading++;
                };

                self.loadingOff = function () {

                    if (self.loading > 0) {

                        self.loading--;
                    }
                };

                self.execute = function (config, onSuccess, onFail) {

                    self.loadingOn();
                    self.alerts.clear();

                    self.http(config)
                        .success(
                        function (data, status, headers, config) {

                            //$log.log('call-success');
                            // if is result object
                            if (typeof(data.code) !== 'undefined' && typeof(data.messages) !== 'undefined') {

                                if (data.code < 1) {

                                    //$log.log('result-fail');
                                    self.alerts.add(data);

                                    if (typeof(onFail) === 'function') {

                                        onFail(data);
                                    }

                                    self.loadingOff();
                                    return;
                                }

                                if (self.includeSuccessAlerts) {

                                    if (data.messages.length == 0) {
                                        //$log.log('default-success-alert');
                                        data.messages.push("Success!")
                                    }

                                    self.alerts.add(data);
                                }
                            } else {

                                $log.error('Result object not returned: ', data);
                                var failResult = new RcmUserResult(
                                    0,
                                    ['Error: Invalid result returned from server.'],
                                    data
                                );

                                self.alerts.add(failResult);
                            }

                            if ((typeof(onSuccess) === 'function')) {

                                onSuccess(data, config);
                            }

                            self.loadingOff();
                        }
                    )
                        .error(
                        function (data, status, headers, config) {

                            //$log.log('call-error');

                            var failResult = new RcmUserResult(
                                0,
                                [self.comErrorMessage + status],
                                data
                            );

                            $log.error(failResult);

                            self.alerts.add(failResult);

                            if (typeof(onFail) === 'function') {

                                onFail(failResult);
                            }

                            self.loadingOff();
                        }
                    );
                }
            };

            return RcmUserHttp;
        }
    ]
)
    .factory(
    'RcmUserResult', function () {

        var RcmUserResult = function (code, messages, data) {

            var self = this;

            self.code = code;
            self.messages = messages;
            self.data = data;
        };

        return RcmUserResult;
    }
)
    .factory(
    'RcmUser', function () {

        var RcmUser = function () {

            var self = this;

            self.username = '';
            self.password = null;
            self.state = 'disabled';
            self.email = null;
            self.name = null;
            self.properties = {};
        };

        return RcmUser;
    }
)
    .factory(
    'RcmResults', function () {

        var RcmResults = function () {

            var self = this;

            self.results = [];

            self.add = function (result) {

                self.results.push(result);
            };

            self.remove = function (index) {

                self.results.splice(index, 1);
            };

            self.clear = function () {

                self.results = [];
            }
        };

        return RcmResults;
    }
)
    .factory(
    'getNamespaceRepeatString', function () {

        return function (namespace, repeatStr, namspaceDelimiter) {
            if (!namspaceDelimiter) {
                namspaceDelimiter = ".";
            }

            var n = (namespace.split(namspaceDelimiter).length - 1);
            var a = [];
            while (a.length < n) {
                a.push(repeatStr);
            }
            return a.join('');
        }
    }
)
    .directive(
    'rcmAlerts', function () {
        /*
         * Example:
         * <div rcm-alerts rcm-results="alerts" alert-title="'An error occured:'"></div>
         */

        var thislink = function (scope, element, attrs) {

            var self = this;

            scope.closeAlert = function (index) {
                scope.rcmResults.clear();
            };

            scope.type = {
                0: 'warning',
                1: 'info'
            };

            scope.title = {
                0: scope.alertTitleError,
                1: scope.alertTitleSuccess
            }

        };

        return {
            link: thislink,
            restrict: 'A',
            replace: true,
            scope: {
                'rcmResults': '=',
                'alertTitleError': '=',
                'alertTitleSuccess': '='
            },
            template: '' +
            '<alert class="alert alert-{{type[alert.code]}}" ng-repeat="alert in rcmResults.results track by $index" type="type[alert.code]" close="closeAlert($index)">' +
            '    <div class="alert-header">' +
            '        <i class="glyphicon glyphicon-{{type[alert.code]}}-sign"></i>' +
            '        <span class="alert-title"><strong>{{title[alert.code]}}</strong></span>' +
            '    </div>' +
            '    <div class="alert-messages">' +
            '        <ul>' +
            '            <li ng-repeat="msg in alert.messages">{{msg}}</li>' +
            '        </ul>' +
            '    </div>' +
            '</alert>'
        };
    }
);

/**
 * Exposes Angular service to global scope for use by other libraries
 * - This is to support jQuery and native JavaScript modules and code
 * Angular injector to get Module services
 */
angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'rcmUserConfig',
        function (rcmUserConfig) {
            rcmUser.core.rcmUserConfig = rcmUserConfig;
        }
    ]
);

angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'RcmUserHttp',
        function (RcmUserHttp) {
            rcmUser.core.RcmUserHttp = RcmUserHttp;
        }
    ]
);

angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'RcmUserResult',
        function (RcmUserResult) {
            rcmUser.core.RcmUserResult = RcmUserResult;
        }
    ]
);

angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'RcmUser',
        function (RcmUser) {
            rcmUser.core.RcmUser = RcmUser;
        }
    ]
);

angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'RcmResults',
        function (RcmResults) {
            rcmUser.core.RcmResults = RcmResults;
        }
    ]
);

angular.injector(['ng', 'rcmuserCore']).invoke(
    [
        'getNamespaceRepeatString',
        function (getNamespaceRepeatString) {
            rcmUser.core.getNamespaceRepeatString = getNamespaceRepeatString;
        }
    ]
);
