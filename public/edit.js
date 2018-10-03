/**
 * Returns the editor function for nav items that display based on "user had role" rules.
 *
 * @param optionName The current "display if" option name for the nav link
 * @return {Function}
 * @constructor
 */
var RcmDynamicNavigationAccessRoleEditorFunctionFactory = function (optionName) {
    return function (link, options) {
        var initialVal = '';
        var showPermissionsDialog = function (permissions, link) {
            var selectedRoles = permissions.split(",");

            var selected = {};

            jQuery.each(
                selectedRoles,
                function (i, v) {
                    selected[v] = v;
                }
            );

            rcmShowPermissions(
                selected,
                function (roles) {
                    if (roles.length > 1) {
                        link.options[optionName].permissions = roles.join(',');
                        return;
                    }
                    link.options[optionName].permissions = roles[0];
                }
            );
        };

        if (
            link.options[optionName] && link.options[optionName].permissions
        ) {
            initialVal = link.options[optionName].permissions;
        }

        link.options[optionName] = {
            permissions: initialVal
        };

        // from rcm-admin
        showPermissionsDialog(initialVal, link);
    }
};

/**
 * {RcmDynamicNavigationEditCustomDialogsConfig}
 * @type {{'serviceAlias': {function}}}
 */
var RcmDynamicNavigationEditCustomDialogsConfig = {
    'show-if-has-access-role':
        RcmDynamicNavigationAccessRoleEditorFunctionFactory('show-if-has-access-role'),
    'show-if-has-access-role-without-role-inheritance':
        RcmDynamicNavigationAccessRoleEditorFunctionFactory('show-if-has-access-role-without-role-inheritance')
};

/**
 * {RcmDynamicNavigationEditCustomDialogs}
 * @constructor
 */
var RcmDynamicNavigationEditCustomDialogs = function () {
    self = this;

    self.hasDialog = function (serviceAlias) {
        if (!self.config[serviceAlias]) {
            return false;
        }

        return true;
    };

    self.showServiceDialog = function (serviceAlias, link, options) {
        if (!self.hasDialog(serviceAlias)) {
            return;
        }

        self.config[serviceAlias](link, options)
    };

    self.createEditButton = function (input, link) {
        var serviceAlias = input.val();

        jQuery(input).find('.custom-dialog-edit-button').remove();

        if (!self.hasDialog(serviceAlias)) {
            return;
        }

        var editButton = jQuery(
            '<span class="custom-dialog-edit-button">' +
            '&nbsp;<input type="button" value="Edit" style="width: auto"/>' +
            '</span>'
        );

        editButton.click(
            function () {
                self.showServiceDialog(
                    serviceAlias,
                    link
                )
            }
        );

        input.append(
            editButton
        );
    };

    self.config = RcmDynamicNavigationEditCustomDialogsConfig;
};

var RcmDynamicNavigationLink = function (id) {
    var self = this;
    self.id = id;
    self.display = 'Untitled Link';
    self.href = "#";
    self.class = '';
    self.target = '';
    self.links = [];
    self.renderService = 'default';
    self.isAllowedService = 'default';
    self.options = {};
};

/**
 * @param instanceId
 * @param container
 * @param {RcmAdminPlugin} pluginHandler
 * @constructor
 */
var RcmDynamicNavigationEdit = function (instanceId, container, pluginHandler) {
    var self = this;
    var services = null;
    var renderEndpoint = '/rcm-dynamic-navigation/render-links/';
    var servicesEndpoint = '/api/rcm-dynamic-navigation/services';
    var containerSelector = pluginHandler.model.getPluginContainerSelector(instanceId);
    var customDialogs = new RcmDynamicNavigationEditCustomDialogs();
    var namespace = 'rcmDynamicNavigationEdit.' + instanceId;

    /**
     * NOTE: we default with null so we can check if it has been initialized
     *
     * @type {null}
     */
    self.saveData = null;

    /**
     * @BC SUPPORT
     * @param links
     * @returns {*}
     */
    var prepareBc = function (links) {
        for (var index in links) {
            var link = links[index];

            if (!link.id) {
                link.id = generateId();
            }

            if (!link.target) {
                link.target = '';
            }

            if (!link.class) {
                link.class = '';
            }
            // Clean out old and erroneous classes
            link.class = link.class.replace('ui-sortable-handle', '');

            // Clean out old permissions
            if (link.permissions || link.hasOwnProperty('permissions')) {
                delete link.permissions;
            }

            if (!link.links) {
                link.links = [];
            }

            if (!link.isAllowedService) {
                link.isAllowedService = 'default';
            }

            if (!link.renderService) {
                link.renderService = 'default';
            }

            if (!link.options) {
                link.options = {};
            }

            if (link.links && link.links.length > 0) {
                link.links = prepareBc(link.links)
            }
        }

        return links;
    };

    /**
     * @param name
     * @param amount
     */
    var setLoading = function (name, amount) {
        if (rcmLoading) {
            rcmLoading.setLoading(name, amount);
        }
    };

    /**
     * @returns {string}
     */
    var generateId = function () {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }

        var guid = function () {
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                s4() + '-' + s4() + s4() + s4();
        };

        return guid();
    };


    /**
     * @returns {Promise}
     */
    var fetchServices = function () {
        return jQuery.ajax(
            {
                method: "GET",
                url: servicesEndpoint
            }
        );
    };

    /**
     * @param saveData
     * @param onComplete
     */
    var render = function (saveData, onComplete) {
        // @todo pluginHandler.preview issue due to calling initEdit after each render
        // pluginHandler.preview(
        //     function (data) {
        //         self.prepareUi(saveData.links);
        //     }
        // );

        setLoading(namespace + '.render', 0);

        jQuery.ajax(
            {
                method: "POST",
                url: renderEndpoint + instanceId,
                data: saveData
            }
        ).then(
            function (data) {
                var elem = jQuery(containerSelector);

                elem.html(data.html);

                elem.find('.menu-item');

                self.prepareUi(saveData.links);

                if (typeof onComplete === 'function') {
                    onComplete();
                }
                setLoading(namespace + '.render', 1);
            }
        ).fail(
            function (error) {
                console.error(error);
                alert('An error occurred while talking to the server');
            }
        );

    };

    /**
     * createLink
     */
    var createLink = function () {
        setLoading(namespace + '.createLink', 0);

        var link = new RcmDynamicNavigationLink(generateId());

        self.saveData.links.push(
            link
        );

        render(
            self.saveData,
            function () {
                self.showEditDialog(link);
                setLoading(namespace + '.createLink', 1);
            }
        );
    };

    /**
     * @param parentLink
     * @param subLink
     * @param onComplete
     */
    var createSubLink = function (parentLink, subLink, onComplete) {
        parentLink.links.push(
            subLink
        );

        render(self.saveData, onComplete);
    };

    /**
     * @param linkToRemove
     * @param onComplete
     */
    var deleteLink = function (linkToRemove, onComplete) {
        self.saveData.links = removeLink(self.saveData.links, linkToRemove);

        render(self.saveData, onComplete);
    };

    /**
     * @param links
     * @param linkToRemove
     * @returns {Array}
     */
    var removeLink = function (links, linkToRemove) {
        var cleanLinks = [];

        for (var index in links) {
            var link = links[index];

            if (link.links && link.links.length > 0) {
                link.links = removeLink(link.links, linkToRemove);
            }

            if (link.id !== linkToRemove.id) {
                cleanLinks.push(link);
            }
        }

        return cleanLinks
    };

    /**
     * @param links
     * @returns {*}
     */
    var indexLinks = function (links) {

        var indexedLinks = {};

        for (var index in links) {
            var link = links[index];

            indexedLinks[link.id] = link;
        }

        return indexedLinks;
    };

    /**
     * @param links
     * @param orderIndex
     * @returns {Array}
     */
    var orderLinks = function (links, orderIndex) {
        var indexedLinks = indexLinks(links);
        var orderedLinks = [];

        for (var linkId in orderIndex) {
            if (typeof indexedLinks[linkId] === 'undefined') {
                continue;
            }
            var link = indexedLinks[linkId];
            if (link.links && link.links.length > 0) {
                link.links = orderLinks(link.links, orderIndex);
            }

            orderedLinks.push(link);
        }

        return orderedLinks;
    };

    /**
     * @returns {{}}
     */
    var buildOrderFromDom = function () {
        var elem = jQuery(containerSelector);

        var menuItems = elem.find('.menu-item');

        var orderIndex = {};

        menuItems.each(
            function (index) {
                orderIndex[jQuery(this).attr('id')] = index;
            }
        );

        return orderIndex;
    };

    /**
     * Called by content management system to make this plugin user-editable
     */
    self.initEdit = function () {
        fetchServices().then(
            function (result) {
                services = result;
                pluginHandler.getInstanceConfig(
                    function (instanceConfig, defaultInstanceConfig) {
                        self.saveData = instanceConfig;
                        self.saveData.links = prepareBc(self.saveData.links);
                        render(self.saveData);
                    }
                );
            }
        ).catch(
            function (error) {
                console.error(error);
                alert('An error occurred while talking to the server');

            }
        );
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    self.getSaveData = function () {
        return new Promise(
            function (resolve, reject) {
                if (self.saveData !== null) {
                    resolve(self.saveData);
                    return;
                }

                pluginHandler.getInstanceConfig().then(
                    function (instanceConfig, defaultInstanceConfig) {
                        self.saveData = instanceConfig;
                        self.saveData.links = prepareBc(self.saveData.links);
                        resolve(self.saveData);
                    }
                );
            }
        );
    };

    /** UI ==================================== **/

    /**
     *
     * @param links
     */
    self.prepareUi = function (links) {
        setLoading(namespace + '.prepareUi', 0);

        var container = jQuery(containerSelector);

        var actionElems = container.find('a');
        actionElems.click(false);

        actionElems.each(
            function (index) {
                var thisElem = jQuery(this);
                var href = thisElem.attr('href');
                thisElem.attr('href', "javascript:void('" + href + "')");
            }
        );

        try {
            //Prevent links from being arrangeable
            container.find('.menu').sortable('destroy');
        } catch (e) {
            //do nothing
        }

        //Make links arrangeable
        var menuElems = container.find('.menu');
        menuElems.sortable(
            {
                connectWith: containerSelector + ' .menu',
                stop: function (event, ui) {
                    var orderIndex = buildOrderFromDom();
                    self.saveData.links = orderLinks(self.saveData.links, orderIndex);
                }
            }
        );

        container.find("a").unbind('click');

        self.addRightClickMenu(links, 0);

        setLoading(namespace + '.prepareUi', 1);
    };

    /**
     * @param links
     * @param depth
     */
    self.addRightClickMenu = function (links, depth) {
        if (!depth) {
            depth = 0;
        }
        setLoading(namespace + '.addRightClickMenu.' + depth, 0);

        var selector;

        for (var index in links) {
            var link = links[index];
            var adminMenuItems = self.getAdminMenuItems(link, depth);
            selector = containerSelector + ' #' + link.id; // OR + ' a';
            self.addRightClickMenuDialog(selector, adminMenuItems);

            if (link.links && link.links.length > 0) {
                var subDepth = depth + 1;
                self.addRightClickMenu(link.links, subDepth)
            }
        }

        setLoading(namespace + '.addRightClickMenu.' + depth, 1);
    };

    /**
     *
     * @param selector
     * @param adminMenuItems
     */
    self.addRightClickMenuDialog = function (selector, adminMenuItems) {
        jQuery.contextMenu('destroy', selector);

        jQuery.contextMenu(
            {
                selector: selector,
                items: adminMenuItems
            }
        );
    };

    /**
     * @param link
     * @param depth
     * @returns {{}}
     */
    self.getAdminMenuItems = function (link, depth) {
        setLoading(namespace + '.getAdminMenuItems.' + depth, 0);
        var createSubMenuItem = {};

        if (depth == 0) {
            createSubMenuItem = {
                createSub: {
                    name: 'Add Sub Menu Link',
                    icon: 'add',
                    callback: function () {
                        var subLink = new RcmDynamicNavigationLink(generateId());

                        createSubLink(
                            link,
                            subLink,
                            function () {
                                self.showEditDialog(
                                    subLink
                                );
                            }
                        );
                    }
                },
            };
        }

        var editLinkPropertiesMenuItem = {
            edit: {
                name: 'Edit Link Properties',
                icon: 'edit',
                callback: function () {
                    self.showEditDialog(
                        link
                    );
                }
            }
        };

        var createNewLinkMenuItem = {
            createNew: {
                name: 'Create New Link',
                icon: 'add',
                callback: function () {
                    createLink();
                }
            },
        };

        var deleteLinkMenuItem = {
            deleteLink: {
                name: 'Delete Link',
                icon: 'delete',
                callback: function () {
                    deleteLink(link);
                }
            }
        };

        var adminMenuItems = {};

        jQuery.extend(
            adminMenuItems,
            editLinkPropertiesMenuItem,
            {separator: '-'},
            editLinkPropertiesMenuItem,
            createNewLinkMenuItem,
            createSubMenuItem,
            deleteLinkMenuItem
        );
        setLoading(namespace + '.getAdminMenuItems.' + depth, 1);
        return adminMenuItems;
    };

    /**
     * Displays a dialog box to edit or add links
     *
     * @param {Object} link the link that we are editing
     */
    self.showEditDialog = function (link) {
        var tempLink = jQuery.extend({}, link);

        var text = jQuery.dialogIn('text', 'Text', tempLink.display);
        var href = jQuery.dialogIn('url', 'Link Url', tempLink.href);

        var aTarget = jQuery.dialogIn(
            'select',
            'Open in new window',
            {
                '': 'No',
                '_blank': 'Yes'
            },
            (tempLink.target ? tempLink.target : ''),
            true
        );

        var isAllowedServicesConfig = services.isAllowedServices;
        var isAllowedServiceOptions = {};

        for (var isAllowedServiceAlias in isAllowedServicesConfig) {
            isAllowedServiceOptions[isAllowedServiceAlias] = isAllowedServicesConfig[isAllowedServiceAlias].displayName;
        }

        var isAllowedServiceInput = jQuery.dialogIn(
            'select',
            'Display Rule',
            isAllowedServiceOptions,
            (tempLink.isAllowedService ? tempLink.isAllowedService : 'default'),
            false
        );

        customDialogs.createEditButton(
            isAllowedServiceInput,
            tempLink
        );

        isAllowedServiceInput.change(
            function () {
                customDialogs.createEditButton(
                    isAllowedServiceInput,
                    tempLink
                );
            }
        );

        var renderServicesConfig = services.renderServices;
        var renderServiceOptions = {};

        for (var renderServiceAlias in renderServicesConfig) {
            renderServiceOptions[renderServiceAlias] = renderServicesConfig[renderServiceAlias].displayName;
        }

        var renderServiceInput = jQuery.dialogIn(
            'select',
            'Display Type',
            renderServiceOptions,
            (tempLink.renderService ? tempLink.renderService : 'default'),
            false
        );

        customDialogs.createEditButton(
            renderServiceInput,
            tempLink
        );

        renderServiceInput.change(
            function () {
                customDialogs.createEditButton(
                    renderServiceInput,
                    tempLink
                );
            }
        );

        var cssClassInput = jQuery.dialogIn(
            'text',
            'Custom CSS Class',
            tempLink.class
        );

        //Create and show our edit dialog
        var form = jQuery('<form></form>')
            .addClass('simple')
            .append(
                text,
                href,
                aTarget,
                isAllowedServiceInput,
                renderServiceInput,
                cssClassInput
            )
            .dialog(
                {
                    title: 'Properties',
                    modal: true,
                    width: 620,
                    close: function () {
                        render(self.saveData);
                    },
                    buttons: {
                        Cancel: function () {
                            jQuery(this).dialog("close");
                        },
                        Ok: function () {
                            link.display = text.val();
                            link.href = href.val();
                            link.target = aTarget.val();

                            link.isAllowedService = isAllowedServiceInput.val();
                            link.renderService = renderServiceInput.val();
                            link.class = cssClassInput.val();
                            // For the custom dialogs
                            link.options = tempLink.options;

                            var button = this;
                            jQuery(button).dialog("close");
                            render(self.saveData);
                        }
                    }
                }
            );
    };
};


