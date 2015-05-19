/**
 * Angular JS module for creating UI for rcm-loading
 * @require:
 *  AngularJS
 */
angular.module('RcmLoading', [])

    .factory(
    'rcmLoading',
    function () {

        return rcmLoading;
    }
)
    .directive(
    'rcmGlobalLoader',
    [
        'rcmLoading',
        function (rcmLoading) {

            var url = {
                template: rcmLoading.getTemplateUrl('loading.html'),
                css: rcmLoading.getTemplateUrl('loading.css')
            };

            var compile = function (tElm, tAttr) {

                return function (scope, element, attrs) {

                    scope.safeApply = function (fn) {
                        var phase = this.$root.$$phase;
                        if (phase == '$apply' || phase == '$digest')
                            this.$eval(fn);
                        else
                            this.$apply(fn);
                    };

                    scope.cssUrl = url.css;

                    scope.isLoading = false;

                    rcmLoading.onLoadingStart(
                        function (loadingParams) {
                            scope.loadingPercent = '';
                            scope.loadingMessage = rcmLoading.getConfigValue(
                                'loadingMessage'
                            );
                            scope.isLoading = true;

                            scope.safeApply();
                        },
                        'rcmGlobalLoader'
                    );

                    rcmLoading.onLoadingChange(
                        function (loadingParams) {

                            scope.loadingPercent = '';
                            
                            var percent = loadingParams.tracker.getPercent();

                            if(percent > 0){
                                scope.loadingPercent = ' ' + loadingParams.tracker.getPercent() + '%';
                            }

                            scope.loadingMessage = rcmLoading.getConfigValue(
                                'loadingMessage'
                            );
                            scope.isLoading = true;

                            scope.safeApply();
                        },
                        'rcmGlobalLoader'
                    );

                    rcmLoading.onLoadingComplete(
                        function (loadingParams) {
                            scope.loadingPercent = '';
                            scope.loadingMessage = rcmLoading.getConfigValue(
                                'loadingCompleteMessage'
                            );
                            scope.isLoading = false;

                            scope.safeApply();
                        },
                        'rcmGlobalLoader'
                    );
                };


            };

            return {
                compile: compile,
                scope: [],
                templateUrl: url.template
            }
        }


    ]
);