<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Plugin\BaseController;
use Rcm\Plugin\PluginInterface;
use RcmDynamicNavigation\Api\Acl\IsAllowedAdmin;
use RcmDynamicNavigation\Api\Acl\IsLinkAllowed;
use RcmDynamicNavigation\Api\LinksFromData;
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
    protected $isLinkAllowed;

    /**
     * @param IsAllowedAdmin $isAllowedAdmin
     * @param IsLinkAllowed  $isLinkAllowed
     * @param array          $config
     */
    public function __construct(
        IsAllowedAdmin $isAllowedAdmin,
        IsLinkAllowed $isLinkAllowed,
        $config
    ) {
        $this->isAllowedAdmin = $isAllowedAdmin;
        $this->isLinkAllowed = $isLinkAllowed;

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
        // NOT USED
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

        $allowedLinks = [];

        /**
         * @var integer $index
         * @var NavLink $link
         */
        foreach ($links as $index => $link) {
            if (!$this->isLinkAllowed->__invoke($request, $link)) {
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
}
