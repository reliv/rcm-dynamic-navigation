<?php
/**
 * AbstractListener
 *
 * AbstractListener
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Event;

use
    Zend\EventManager\EventManagerInterface;
use
    Zend\EventManager\ListenerAggregateInterface;

/**
 * AbstractListener
 *
 * AbstractListener
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Event
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AbstractListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = [];
    protected $id = 'RcmUser\Service\RcmUserService';
    protected $event = 'someEvent';
    protected $priority = 100;

    /**
     * attach
     *
     * @param EventManagerInterface $events events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();
        $this->listeners[] = $sharedEvents->attach(
            $this->id,
            $this->event,
            [
                $this,
                'onEvent'
            ],
            $this->priority
        );
    }

    /**
     * detach
     *
     * @param EventManagerInterface $events events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * onEvent
     *
     * @param Event $e e
     *
     * @return void
     */
    public function onEvent($e)
    {
        var_dump($e);
    }
}
