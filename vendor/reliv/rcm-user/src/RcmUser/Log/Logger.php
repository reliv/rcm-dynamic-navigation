<?php
/**
 * Logger.php
 *
 * Logger
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Log
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmUser\Log;

use
    Zend\Log\LoggerInterface;

/**
 * Class Logger
 *
 * Logger
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Log
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class Logger implements LoggerInterface
{
    /**
     * @var int $logLevel
     */
    protected $logLevel = \Zend\Log\Logger::ERR;

    /**
     * __construct
     *
     * @param int $logLevel logLevel
     */
    public function __construct($logLevel = \Zend\Log\Logger::ERR)
    {
        $this->setLogLevel($logLevel);
    }

    /**
     * setLogLevel
     *
     * @param int $logLevel logLevel
     *
     * @return void
     */
    protected function setLogLevel($logLevel)
    {
        $this->logLevel = $logLevel;
    }

    /**
     * getLogLevel
     *
     * @return int
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * getLevel - get the level int from string
     *
     * @param string $type type
     *
     * @return mixed
     */
    protected function getLevel($type)
    {
        if (defined('\Zend\Log\Logger::' . $type)) {
            return constant('\Zend\Log\Logger::' . $type);
        }

        return $this->logLevel;
    }

    /**
     * canLog
     *
     * @param int $type type
     *
     * @return bool
     */
    public function canLog($type)
    {
        $level = $this->getLevel($type);

        if ($level > $this->getLogLevel()) {
            // no logging
            return false;
        }

        return true;
    }

    /**
     * log
     *
     * @param string $type    type
     * @param string $message message
     * @param array  $extra   extra
     *
     * @return LoggerInterface
     */
    protected function log(
        $type,
        $message,
        $extra = []
    ) {
        if ($this->canLog($type)) {

            // override with logging logic here
        }

        return $this;
    }

    /**
     * emerg
     *
     * @param string            $message message
     * @param array|Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function emerg(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * alert
     *
     * @param string            $message message
     * @param array|Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function alert(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * crit
     *
     * @param string            $message message
     * @param array|Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function crit(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * err
     *
     * @param string            $message message
     * @param array|Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function err(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * warn
     *
     * @param string            $message message
     * @param array|Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function warn(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * notice
     *
     * @param string            $message message
     * @param array|Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function notice(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * info
     *
     * @param string            $message message
     * @param array|Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function info(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }

    /**
     * debug
     *
     * @param string            $message message
     * @param array|Traversable $extra   extra
     *
     * @return LoggerInterface
     */
    public function debug(
        $message,
        $extra = []
    ) {
        return $this->log(
            __FUNCTION__,
            $message,
            $extra
        );
    }
}
