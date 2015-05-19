/**
 * rcmLoading.Params Standard class for loading params
 * @param options
 * @constructor
 */
rcmLoading.Params = function (options) {

    var self = this;
    self.name = null;
    self.amount = 0;
    self.startMessage = '';
    self.options = null;

    /**
     * onChange
     * @param params
     */
    self.onChange = function (params) {
    };

    /**
     * onComplete
     * @param params
     */
    self.onComplete = function (params) {
    };

    /**
     * init
     * @param options
     */
    self.init = function (options) {
        self.setOptions(options);
    };

    /**
     * setOptions
     * @param options
     */
    self.setOptions = function (options) {

        if (typeof options.name === 'string') {
            self.name = options.name;
        }
        if (typeof options.amount === 'number') {
            self.amount = options.amount;
        }
        if (typeof options.onChange === 'function') {
            self.onChange = options.onChange;
        }
        if (typeof options.onComplete === 'function') {
            self.onComplete = options.onComplete;
        }
        if (typeof options.startMessage === 'string') {
            self.startMessage = options.startMessage;
        }

        self.options = options;
    };

    /**
     * getPercent
     * @returns {number}
     */
    self.getPercent = function () {
        // null indicates complete
        if(self.amount === null){
            return 100;
        }
        return Math.round((self.amount * 100))
    };

    /**
     * isLoading
     * @returns {boolean}
     */
    self.isLoading = function () {
        return (self.amount < 1);
    };

    /**
     * init call
     */
    self.init(options);
};
