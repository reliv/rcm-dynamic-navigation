<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Plugin\BaseController;
use Rcm\Plugin\PluginInterface;
use RcmDynamicNavigation\Api\Acl\IsAllowed;
use RcmDynamicNavigation\Api\Acl\IsAllowedAdmin;
use RcmDynamicNavigation\Api\GetIsAllowedServiceConfig;
use RcmDynamicNavigation\Api\LinksFromData;
use RcmDynamicNavigation\Api\Options;
use RcmDynamicNavigation\Model\NavLink;
use Zend\Diactoros\ServerRequestFactory;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class PluginController extends BaseController implements PluginInterface
{
    /**
     * @var IsAllowedAdmin
     */
    protected $isAllowedAdmin;
    protected $getIsAllowedServiceConfig;

    /**
     * @param IsAllowedAdmin            $isAllowedAdmin
     * @param GetIsAllowedServiceConfig $getIsAllowedServiceConfig
     * @param array                     $config
     */
    public function __construct(
        IsAllowedAdmin $isAllowedAdmin,
        GetIsAllowedServiceConfig $getIsAllowedServiceConfig,
        $config
    ) {
        $this->isAllowedAdmin = $isAllowedAdmin;
        $this->getIsAllowedServiceConfig = $getIsAllowedServiceConfig;

        parent::__construct($config, 'RcmDynamicNavigation');
    }

    /**
     * Render the plugin
     *
     * @param int   $instanceId     Instance ID
     * @param array $instanceConfig Instance Config
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function renderInstance($instanceId, $instanceConfig)
    {
        $links = LinksFromData::invoke($instanceConfig['links']);

        $request = ServerRequestFactory::fromGlobals();

        $allowedLinks = $this->filterAllowedLinks($request, $links);

        $view = parent::renderInstance(
            $instanceId,
            $instanceConfig
        );

        $view->setVariable('links', $allowedLinks);
        $view->setVariable('isAdmin', $this->isAllowedAdmin->__invoke($request));

        return $view;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array                  $links
     *
     * @return array
     */
    protected function filterAllowedLinks(
        ServerRequestInterface $request,
        array $links
    ) {
        if (empty($links)) {
            return [];
        }

        // no restrictions for admin
        if ($this->isAllowedAdmin->__invoke($request)) {
            return $links;
        }

        $allowedLinks = [];

        /**
         * @var integer $index
         * @var NavLink $link
         */
        foreach ($links as $index => $link) {
            if (!$this->isLinkAllowed($request, $link)) {
                continue;
            }

            $allowedLinks[] = $link;

            if ($link->hasLinks()) {
                $allowedSubLinks = $this->filterAllowedLinks(
                    $request,
                    $link->getLinks()
                );

                $link->setLinks($allowedSubLinks);
            }
        }

        return $allowedLinks;
    }

    /**
     * Check an individual link
     *
     * @param ServerRequestInterface $request
     * @param NavLink                $link
     *
     * @return bool
     */
    protected function isLinkAllowed(
        ServerRequestInterface $request,
        NavLink $link
    ) {
        $serviceContainer = $this->getServiceLocator();

        $isAllowedServiceAlias = $link->getIsAllowedService();

        $isAllowedServiceConfig = $this->getIsAllowedServiceConfig->__invoke(
            $isAllowedServiceAlias
        );

        $isAllowedServiceName = Options::getRequired(
            $isAllowedServiceConfig,
            'service'
        );

        $isAllowedServiceOptions = $link->getIsAllowedServiceOptions();

        /** @var IsAllowed $isAllowedService */
        $isAllowedService = $serviceContainer->get($isAllowedServiceName);

        return $isAllowedService->__invoke(
            $request,
            $isAllowedServiceOptions
        );
    }
}
