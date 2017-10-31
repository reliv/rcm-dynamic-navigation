/**
 * @param instanceId
 * @param container
 * @param {RcmAdminPlugin} pluginHandler
 * @constructor
 */
var RcmDynamicNavigationEdit = function (instanceId, container, pluginHandler) {
    var self = this;

    var saveData = null;
    var services = null;

    var renderEndpoint = '/rcm-dynamic-navigation/render-links';
    var servicesEndpoint = '/api/rcm-dynamic-navigation/services';


    var containerSelector = pluginHandler.model.getPluginContainerSelector(instanceId);

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
                        self.saveData.links = self.prepareBc(self.saveData.links);
                        self.render(self.saveData);
                    }
                );
            }
        ).catch(
            function (a, b, c) {
                //alert('An error occurred while talking to the server');
                console.error(a, b, c)
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
     * @param saveData
     */
    self.render = function (saveData) {
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
                self.prepareUi(saveData.links);
            }
        ).fail(
            function (a, b, c) {
                //alert('An error occurred while talking to the server');
                console.error(a, b, c)
            }
        );
    };

    /**
     * @param links
     * @returns {*}
     */
    self.prepareBc = function (links) {
        for (link in links) {
            links[link].id = getId(links[link]);
            if (links[link].links && links[link].links.length > 0) {
                links[link].links = self.prepareBc(links[link].links)
            }
        }

        return links;
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
                self.showEditDialog(jQuery(this), false)
            }
        )
    };

    /**
     *
     */
    self.addRightClickMenu = function (links, depth) {
        if (!depth) {
            depth = 0;
        }

        var selector;
        var id;

        for (link in links) {
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

    self.getAdminMenuItems = function (link, depth) {

        self.seperatorCount = 0;

        var createSubMenuItem = {};

        if (depth == 0) {
            createSubMenuItem = {
                createSub: {
                    name: 'Add Sub Menu Link',
                    icon: 'add',
                    callback: function () {
                        self.addSubMenu(this);
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
                        link,
                        false
                    );
                }
            }
        };

        var createNewLinkMenuItem = {
            createNew: {
                name: 'Create New Link',
                icon: 'add',
                callback: function () {
                    self.addItem(this);
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
        var text = $.dialogIn('text', 'Text', link.display);
        var href = $.dialogIn('url', 'Link Url', link.href);

        var aTarget = $.dialogIn(
            'select',
            'Open in new window',
            {
                '': 'No',
                '_blank': 'Yes'
            },
            (link.target ? link.target : ''),
            true
        );

        var isAllowedServicesConfig = services.isAllowedServices;
        var isAllowedServiceOptions = {};

        for (var isAllowedServiceAlias in isAllowedServicesConfig) {
            isAllowedServiceOptions[isAllowedServiceAlias] = isAllowedServicesConfig[isAllowedServiceAlias].displayName;
        }

        var isAllowedServiceInput = $.dialogIn(
            'select',
            'Display Rule',
            isAllowedServiceOptions,
            (link.isAllowedService ? link.isAllowedService : 'default'),
            false
        );

        var renderServicesConfig = services.renderServices;
        var renderServiceOptions = {};

        for (var renderServiceAlias in renderServicesConfig) {
            renderServiceOptions[renderServiceAlias] = renderServicesConfig[renderServiceAlias].displayName;
        }

        var renderServiceInput = $.dialogIn(
            'select',
            'Display Type',
            renderServiceOptions,
            (link.renderService ? link.renderService : 'default'),
            false
        );

        var cssClassInput = $.dialogIn(
            'text',
            'Custom CSS Class',
            link.class
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
                        self.render(self.saveData);
                    },
                    buttons: {
                        Cancel: function () {
                            jQuery(this).dialog("close");
                        },
                        Ok: function () {
                            //Get user-entered data from form
                            link.display = text.val();
                            link.href = href.val();
                            link.target = aTarget.val();

                            link.isAllowedService = isAllowedServiceInput.val();
                            link.renderService = renderServiceInput.val();
                            link.class = cssClassInput.val();

                            var button = this;
                            jQuery(button).dialog("close");
                            self.render(self.saveData);
                        }
                    }
                }
            );
    };

    /**
     *
     * @param link
     */
    self.showPermissionsDialog = function (link) {

        var permissions = link.isAllowedServiceOptions;
        var selectedRoles = permissions.split(",");

        var selected = {};

        $.each(
            selectedRoles, function (i, v) {
                selected[v] = v;
            }
        );

        rcmShowPermissions(
            selected, function (roles) {
                if (roles.length > 1) {
                    li.attr('data-permissions', roles.join(','));
                } else {
                    li.attr('data-permissions', roles[0]);
                }
            }
        );
    };

    var currentId = 100;

    /**
     * BC SUPPORT
     * @param link
     * @returns {*}
     */
    var getId = function (link) {
        if (link.id) {
            return link.id;
        }
        currentId++;
        link.id = currentId;
        return link.id;
    }
};


