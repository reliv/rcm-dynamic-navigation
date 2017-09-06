<?php

namespace RcmDynamicNavigation\Api\Render;

use Psr\Http\Message\ServerRequestInterface;
use RcmDynamicNavigation\Model\NavLink;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class RenderLinks
{
    const OPTION_ADMIN_MODE = 'adminMode';
    const OPTION_ID = 'id';

    /**
     * @param ServerRequestInterface $request
     * @param NavLink[]              $links
     * @param array                  $options
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        ServerRequestInterface $request,
        array $links,
        array $options = []
    ): string
    {
        $adminMode = (array_key_exists(self::OPTION_ADMIN_MODE, $options) ? $options[self::OPTION_ADMIN_MODE] : false);

        if (!array_key_exists(self::OPTION_ID, $options)) {
            throw new \Exception('Option is required: ' . self::OPTION_ID);
        }

        $id = $options[self::OPTION_ID];

        return $this->render(
            $links,
            $adminMode,
            $id
        );
    }

    /**
     * Render Method
     *
     * @param array   $links     Array of NavLinks
     * @param boolean $adminMode Render in admin mode
     * @param string  $id        Id to pass to container
     *
     * @return string
     */
    public function render($links, $adminMode, $id)
    {
        $navHtml = '<nav class="navbar navbar-default">';
        $navHtml
            .= '
          <div class="navbar-header">
            <button
                type="button"
                class="navbar-toggle collapsed"
                data-toggle="collapse"
                data-target="#' . $id . '"
                aria-expanded="false"
                aria-controls="navbar"
                >
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          </div>
          <div id="' . $id . '" class="navbar-collapse collapse">
          ';
        $navHtml .= $this->getUl($links, $adminMode, $id);
        $navHtml .= '</div></nav>';

        return $navHtml;
    }

    /**
     * Get the UL container for links
     *
     * @param array   $links     Array of NavLinks
     * @param boolean $adminMode Render in admin mode
     * @param string  $id        Id to pass to container
     *
     * @return string
     */
    protected function getUl($links, $adminMode, $id = null)
    {
        $html = '';

        if (!empty($id)) {
            $html .= '<ul class="nav navbar-nav">';
        } else {
            $html .= '<ul class="dropdown-menu" role="menu">';
        }

        foreach ($links as $link) {
            $html .= $this->getLi($link, $adminMode);
        }

        $html .= '</ul>' . "\n";

        return $html;
    }

    /**
     * Get the li and link html for a link
     *
     * @param \RcmDynamicNavigation\Model\NavLink $link      Link to render
     * @param boolean                             $adminMode Render in admin mode
     *
     * @return string
     */
    protected function getLi(NavLink $link, $adminMode)
    {
        $target = $link->getTarget();

        $objectClass = $link->getClass();
        $systemClass = $link->getSystemClass();

        if ($link->hasLinks()) {
            $objectClass .= ' dropdown';
        }

        $permissionsArray = $link->getPermissions();

        $html = '<li';

        if (!empty($objectClass) || !empty($systemClass)) {
            $html .= ' class="' . $objectClass . ' ' . $systemClass . '"';
        }

        if ($adminMode) {
            $html .= ' data-permissions="' . implode(',', $permissionsArray) . '"';
        }

        $html .= '>' . "\n";
        $html .= '<a href="' . $link->getHref() . '"';

        if ($link->hasLinks()) {
            $html .= 'class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"';
        }

        if (!empty($target)) {
            $html .= ' target="' . $target . '"';
        }

        $html .= '>';
        $html .= '<span class="linkText">' . $link->getDisplay() . '</span>';

        if ($link->hasLinks()) {
            $html .= '<span class="caret"></span>';
        }

        $html .= '</a>' . "\n";

        if ($link->hasLinks()) {
            $html .= $this->getUl($link->getLinks(), $adminMode);
        }

        $html .= '</li>' . "\n";

        return $html;
    }

    /**
     * @return string
     */
    protected function getMobileMenu()
    {
        $html = '<div class="glyphicon glyphicon-menu-hamburger mobileMenuIcon" aria-hidden="true"></div>';

        return $html;
    }
}
