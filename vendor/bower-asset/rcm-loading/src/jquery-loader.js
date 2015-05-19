jQuery(document).ready(
    function () {

        var url = {
            template: rcmLoading.getTemplateUrl('loading.html'),
            css: rcmLoading.getTemplateUrl('loading.css')
        };

        jQuery.get(
            url.template,
            function (data) {

                var loaderElm = jQuery(data);

                // remove angular interference
                loaderElm.removeAttr('ng-show');

                loaderElm.find('.loading-message').html('');
                var cssElm = loaderElm.find("[type='text/css']");
                cssElm.attr('href', url.css);
                // remove angular interference
                cssElm.removeAttr('ng-href');

                loaderElm.hide();

                jQuery(document.body).append(loaderElm);

                rcmLoading.onLoadingStart(
                    function (loadingParams) {
                        loaderElm.find('.loading-message').html(
                            rcmLoading.getConfigValue('loadingMessage')
                        );
                        loaderElm.show();
                    },
                    'rcmGlobalLoader'
                );

                rcmLoading.onLoadingChange(
                    function (loadingParams) {
                        var percentMsg = '';
                        var percent = loadingParams.tracker.getPercent();
                        if(percent > 0){
                            percentMsg = ' ' + loadingParams.tracker.getPercent() + '%';
                        }
                        loaderElm.find('.loading-message').html(
                            //'-' +
                            rcmLoading.getConfigValue('loadingMessage')
                            + percentMsg
                            //+ '-'
                        );
                        loaderElm.show();
                    },
                    'rcmGlobalLoader'
                );

                rcmLoading.onLoadingComplete(
                    function (loadingParams) {
                        loaderElm.find('.loading-message').html(
                            rcmLoading.getConfigValue('loadingCompleteMessage')
                        );
                        loaderElm.hide();
                    },
                    'rcmGlobalLoader'
                );
            }
        );


    }
);
