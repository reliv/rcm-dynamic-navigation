<?php

namespace RcmDynamicNavigation\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Rcm\Plugin\BaseController;
use Rcm\Plugin\PluginInterface;
use RcmDynamicNavigation\Api\Acl\IsAllowedAdmin;
use RcmDynamicNavigation\Api\Acl\IsAllowedIfLoggedIn;
use RcmDynamicNavigation\Api\Acl\IsAllowedRoles;
use RcmDynamicNavigation\Model\NavLink;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Plugin Controller
 *
 * This is the main controller for this plugin
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2015 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class PluginController extends BaseController implements PluginInterface
{
    /**
     * @var IsAllowedAdmin
     */
    protected $isAllowedAdmin;

    /**
     * @var IsAllowedRoles
     */
    protected $isAllowedRoles;

    /**
     * @var IsAllowedIfLoggedIn
     */
    protected $isAllowedIfLoggedIn;

    /**
     * @param IsAllowedAdmin      $isAllowedAdmin
     * @param IsAllowedRoles      $isAllowedRoles
     * @param IsAllowedIfLoggedIn $isAllowedIfLoggedIn
     * @param                     $config
     */
    public function __construct(
        IsAllowedAdmin $isAllowedAdmin,
        IsAllowedIfLoggedIn $isAllowedIfLoggedIn,
        IsAllowedRoles $isAllowedRoles,
        $config
    ) {
        $this->isAllowedAdmin = $isAllowedAdmin;
        $this->isAllowedIfLoggedIn = $isAllowedIfLoggedIn;
        $this->isAllowedRoles = $isAllowedRoles;

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
        $links = array();

        if (!empty($instanceConfig['links']) && is_array($instanceConfig['links'])) {
            foreach ($instanceConfig['links'] as $link) {
                $links[] = new NavLink($link);
            }
        }

        $request = ServerRequestFactory::fromGlobals();

        $this->checkLinks($request, $links);

        $view = parent::renderInstance(
            $instanceId,
            $instanceConfig
        );

        $view->setVariable('links', $links);
        $view->setVariable('isAdmin', $this->isAllowedAdmin->__invoke($request));

        return $view;
    }

    /**
     * Check the links for display
     *
     * @param array $links Array of links to check
     *
     * @return void
     */
    /**
     * @param ServerRequestInterface $request
     * @param NavLink[]              $links
     *
     * @return void
     */
    protected function checkLinks(
        ServerRequestInterface $request,
        array &$links
    ) {
        if (empty($links)) {
            return;
        }

        /**
         * @var integer $index
         * @var NavLink $link
         */
        foreach ($links as $index => $link) {
            if (!$this->checkLink($request, $link)) {
                unset($links[$index]);
            }

            if ($link->hasLinks()) {
                $subLinks = $link->getLinks();
                $this->checkLinks($request, $subLinks);
                $link->setLinks($subLinks);
            }
        }
    }

    /**
     * Check an individual link
     *
     * @param ServerRequestInterface $request
     * @param NavLink                $link
     *
     * @return bool
     */
    protected function checkLink(
        ServerRequestInterface $request,
        NavLink $link
    ) {
        $siteAdmin = $this->isAllowedAdmin->__invoke($request);
        $userHasPermissions = $this->isAllowedRoles->__invoke(
            $request,
            [IsAllowedRoles::OPTION_PERMITTED_ROLES => $link->getPermissions()]
        );

        $userIsLoggedIn = $this->isAllowedIfLoggedIn->__invoke(
            $request
        );

        if ($link->isLoginLink() && $userIsLoggedIn) {
            $link->addSystemClass('HiddenLink');
        } elseif ($link->isLogoutLink() && !$userIsLoggedIn) {
            $link->addSystemClass('HiddenLink');
        } elseif ($siteAdmin && !$userHasPermissions) {
            $link->addSystemClass('HiddenLink');
        }

        if ($siteAdmin || $userHasPermissions) {
            return true;
        }

        return false;
    }
}
