/**
 *
 * @type {{'serviceAlias': {function}}}
 */
var RcmDynamicNavigationEditCustomDialogsConfig = {
    'show-if-has-access-role': function (link, options) {
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
                        link.options['show-if-has-access-role'].permissions = roles.join(',');
                        return;
                    }
                    link.options['show-if-has-access-role'].permissions = roles[0];
                }
            );
        };

        if (
            link.options['show-if-has-access-role'] && link.options['show-if-has-access-role'].permissions
        ) {
            initialVal = link.options['show-if-has-access-role'].permissions;
        }

        link.options['show-if-has-access-role'] = {
            permissions: initialVal
        };

        // from rcm-admin
        showPermissionsDialog(initialVal, link);
    },
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
    renderService = 'default';
    self.isAllowedService = 'default';
    self.options = [];
    self.remove = false;
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
    var renderEndpoint = '/rcm-dynamic-navigation/render-links';
    var servicesEndpoint = '/api/rcm-dynamic-navigation/services';
    var containerSelector = pluginHandler.model.getPluginContainerSelector(instanceId);
    /* @BC SUPPORT */
    var currentId = 100;

    var customDialogs = new RcmDynamicNavigationEditCustomDialogs();

    self.saveData = null;

    /**
     * @BC SUPPORT
     * @param links
     * @returns {*}
     */
    var prepareBc = function (links) {
        for (var link in links) {
            links[link].id = prepareId(links[link]);

            if (links[link].class && links[link].class.includes('rcmDynamicNavigationLogout')) {
                links[link].isAllowedService = 'show-if-logged-in';
            }

            if (links[link].class && links[link].class.includes('rcmDynamicNavigationLogin')) {
                links[link].isAllowedService = 'show-if-not-logged-in';
            }

            if (links[link].permissions) {
                links[link].isAllowedService = 'show-if-has-access-role';
                links[link].options = {
                    'show-if-has-access-role': {
                        'permissions': links[link].permissions,
                    }
                };
            }

            if (!links[link].options) {
                links[link].options = {};
            }

            if (!links[link].links) {
                links[link].links = [];
            }

            if (links[link].links && links[link].links.length > 0) {
                links[link].links = prepareBc(links[link].links)
            }
        }

        return links;
    };

    /**
     * @BC SUPPORT
     *
     * @param link
     * @returns {*}
     */
    var prepareId = function (link) {
        if (link.id) {
            return link.id;
        }
        currentId++;
        link.id = currentId;
        return link.id;
    };

    var findOneLinkById = function (links, id) {
        var sublinksResult = null;

        for (var link in links) {
            if (links[link].id == id) {
                return links[link];
            }

            sublinksResult = findOneLinkById(links[link], id);

            if (sublinksResult) {
                return sublinksResult;
            }
        }

        return null;
    };

    var createLink = function () {
        var link = new RcmDynamicNavigationLink(prepareId({}));

        self.saveData.links.push(
            link
        );

        render(self.saveData);
    };

    var createSubLink = function (link) {
        var subLink = new RcmDynamicNavigationLink(prepareId({}));

        link.links.push(
            subLink
        );

        render(self.saveData);
    };

    var deleteLink = function (link) {
        link.remove = true;

        render(self.saveData);
    };

    var prepareLinks = function (links) {

        return links;
    };

    var buildOrder = function () {

    };

    /**
     * @param saveData
     */
    render = function (saveData) {

        saveData.links = prepareLinks(saveData.links);

        // @todo could use pluginHandler.preview()
        jQuery.ajax(
            {
                method: "POST",
                url: renderEndpoint,
                data: saveData
            }
        ).then(
            function (data) {
                var elem = jQuery(pluginHandler.getPluginContainer());

                elem.html(data.html);

                elem.find('.menu-item');

                self.prepareUi(saveData.links);
            }
        ).fail(
            function (error) {
                console.error(error);
                alert('An error occurred while talking to the server');
            }
        );
    };


    /**
     * Called by content management system to make this plugin user-editable
     */
    self.initEdit = function () {
        self.fetchServices().then(
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
     * @returns {Promise}
     */
    self.fetchServices = function () {
        return jQuery.ajax(
            {
                method: "GET",
                url: servicesEndpoint
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
        return self.saveData;
    };

    /**
     *
     */
    self.prepareUi = function (links) {
        self.addRightClickMenu(links, 0);
        jQuery(containerSelector).find('a').click(false);

        try {
            //Prevent links from being arrangeable
            container.find('.menu').sortable('destroy');
        } catch (e) {
            //do nothing
        }

        //Make links arrangeable
        container.find('.menu').sortable(
            {
                connectWith: containerSelector + ' .menu'
            }
        );

        jQuery(containerSelector).find("a").unbind('click');

        jQuery(containerSelector).find('.menu-item').dblclick(
            function () {
                self.showEditDialog(jQuery(this))
            }
        );
    };

    /**
     *
     */
    self.addRightClickMenu = function (links, depth) {
        if (!depth) {
            depth = 0;
        }

        var selector;

        for (var link in links) {
            var adminMenuItems = self.getAdminMenuItems(links[link], depth);
            selector = containerSelector + ' #' + links[link].id;
            self.addRightClickMenuDialog(selector, adminMenuItems);

            if (links[link].links && links[link].links.length > 0) {
                var subDepth = depth + 1;
                self.addRightClickMenu(links[link].links, subDepth)
            }
        }
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
        var createSubMenuItem = {};

        if (depth == 0) {
            createSubMenuItem = {
                createSub: {
                    name: 'Add Sub Menu Link',
                    icon: 'add',
                    callback: function () {
                        createSubLink(link);
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
                    self.deleteItem(this);
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


