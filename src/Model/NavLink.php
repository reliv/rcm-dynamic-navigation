<?php

namespace RcmDynamicNavigation\Model;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class NavLink
{
    /** @var string */
    protected $display;

    /** @var string */
    protected $href;

    /** @var string */
    protected $target;

    /** @var array */
    protected $links = [];

    /** @var array */
    protected $class = [];

    /** @var array */
    protected $systemClass = [];

    /** @var string */
    protected $isAllowedService = 'default';

    /** @var array */
    protected $isAllowedServiceOptions = [];
    
    /** @var string */
    protected $renderService = 'default';

    /** @var array */
    protected $renderServiceOptions = [];

    /**
     * @param string $display
     * @param string $href
     * @param string $target
     * @param array  $links
     * @param string $class
     * @param string $renderService
     * @param array  $renderServiceOptions
     * @param string $isAllowedService
     * @param array  $isAllowedServiceOptions
     */
    public function __construct(
        string $display,
        string $href,
        string $target = '',
        array $links = [],
        string $class = '',
        string $isAllowedService = 'default',
        array $isAllowedServiceOptions = [],
        string $renderService = 'default',
        array $renderServiceOptions = []
    ) {
        $this->setDisplay($display);
        $this->setHref($href);
        $this->setTarget($target);
        $this->setLinks($links);
        $this->setClass($class);
        
        $this->setIsAllowedService($isAllowedService);
        $this->setIsAllowedServiceOptions($isAllowedServiceOptions);
        $this->setRenderService($renderService);
        $this->setRenderServiceOptions($renderServiceOptions);
    }

    /**
     * @return string
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set the text to display
     *
     * @param string $display Text to display
     *
     * @return void
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }

    /**
     * Get the link href
     *
     * @return string|null
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Se the link href
     *
     * @param string $href Link Href
     *
     * @return void
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * Get the link Target
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set the link target
     *
     * @param string $target Target for link
     *
     * @return void
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Get Sublinks
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set sublinks
     *
     * @param NavLink[] $links Sublinks to add
     *
     * @return void
     */
    public function setLinks(array $links)
    {
        $this->links = [];

        foreach ($links as &$link) {
            $this->addLink($link);
        }
    }

    /**
     * Add a link
     *
     * @param NavLink|array $link Link to add
     *
     * @return void
     */
    public function addLink(NavLink $link)
    {
        $this->links[] = $link;
    }

    /**
     * Does this object have sub links?
     *
     * @return bool
     */
    public function hasLinks()
    {
        if (!empty($this->links)) {
            return true;
        }

        return false;
    }

    /**
     * get the CSS class
     *
     * @return string
     */
    public function getClass()
    {
        return implode(" ", $this->class);
    }

    /**
     * Set the Css class
     *
     * @param mixed $class Css class
     *
     * @return void
     */
    public function setClass($class)
    {
        $this->class = [];

        $classes = explode(" ", $class);

        foreach ($classes as $classToAdd) {
            $this->addClass($classToAdd);
        }
    }

    /**
     * Add a css class
     *
     * @param string $class Css Class to add
     *
     * @return void
     */
    public function addClass($class)
    {
        if (!empty($class)) {
            $this->class[] = $class;
        }
    }

    /**
     * @return string
     */
    public function getIsAllowedService(): string
    {
        return $this->isAllowedService;
    }

    /**
     * @param string $isAllowedService
     */
    public function setIsAllowedService(string $isAllowedService)
    {
        $this->isAllowedService = $isAllowedService;
    }

    /**
     * @return array
     */
    public function getIsAllowedServiceOptions(): array
    {
        return $this->isAllowedServiceOptions;
    }

    /**
     * @param array $isAllowedServiceOptions
     */
    public function setIsAllowedServiceOptions(array $isAllowedServiceOptions)
    {
        $this->isAllowedServiceOptions = $isAllowedServiceOptions;
    }


    /**
     * @return string
     */
    public function getRenderService(): string
    {
        return $this->renderService;
    }

    /**
     * @param string $renderService
     */
    public function setRenderService(string $renderService)
    {
        $this->renderService = $renderService;
    }

    /**
     * @return array
     */
    public function getRenderServiceOptions(): array
    {
        return $this->renderServiceOptions;
    }

    /**
     * @param array $renderServiceOptions
     */
    public function setRenderServiceOptions(array $renderServiceOptions)
    {
        $this->renderServiceOptions = $renderServiceOptions;
    }
    
    
}
