<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CPWFreeVendor\Monolog\Handler;

use CPWFreeVendor\Monolog\Formatter\NormalizerFormatter;
use CPWFreeVendor\Monolog\Logger;
/**
 * Handler sending logs to Zend Monitor
 *
 * @author  Christian Bergau <cbergau86@gmail.com>
 * @author  Jason Davis <happydude@jasondavis.net>
 */
class ZendMonitorHandler extends \CPWFreeVendor\Monolog\Handler\AbstractProcessingHandler
{
    /**
     * Monolog level / ZendMonitor Custom Event priority map
     *
     * @var array
     */
    protected $levelMap = array();
    /**
     * Construct
     *
     * @param  int                       $level
     * @param  bool                      $bubble
     * @throws MissingExtensionException
     */
    public function __construct($level = \CPWFreeVendor\Monolog\Logger::DEBUG, $bubble = \true)
    {
        if (!\function_exists('CPWFreeVendor\\zend_monitor_custom_event')) {
            throw new \CPWFreeVendor\Monolog\Handler\MissingExtensionException('You must have Zend Server installed with Zend Monitor enabled in order to use this handler');
        }
        //zend monitor constants are not defined if zend monitor is not enabled.
        $this->levelMap = array(\CPWFreeVendor\Monolog\Logger::DEBUG => \CPWFreeVendor\ZEND_MONITOR_EVENT_SEVERITY_INFO, \CPWFreeVendor\Monolog\Logger::INFO => \CPWFreeVendor\ZEND_MONITOR_EVENT_SEVERITY_INFO, \CPWFreeVendor\Monolog\Logger::NOTICE => \CPWFreeVendor\ZEND_MONITOR_EVENT_SEVERITY_INFO, \CPWFreeVendor\Monolog\Logger::WARNING => \CPWFreeVendor\ZEND_MONITOR_EVENT_SEVERITY_WARNING, \CPWFreeVendor\Monolog\Logger::ERROR => \CPWFreeVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \CPWFreeVendor\Monolog\Logger::CRITICAL => \CPWFreeVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \CPWFreeVendor\Monolog\Logger::ALERT => \CPWFreeVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \CPWFreeVendor\Monolog\Logger::EMERGENCY => \CPWFreeVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR);
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $this->writeZendMonitorCustomEvent(\CPWFreeVendor\Monolog\Logger::getLevelName($record['level']), $record['message'], $record['formatted'], $this->levelMap[$record['level']]);
    }
    /**
     * Write to Zend Monitor Events
     * @param string $type Text displayed in "Class Name (custom)" field
     * @param string $message Text displayed in "Error String"
     * @param mixed $formatted Displayed in Custom Variables tab
     * @param int $severity Set the event severity level (-1,0,1)
     */
    protected function writeZendMonitorCustomEvent($type, $message, $formatted, $severity)
    {
        zend_monitor_custom_event($type, $message, $formatted, $severity);
    }
    /**
     * {@inheritdoc}
     */
    public function getDefaultFormatter()
    {
        return new \CPWFreeVendor\Monolog\Formatter\NormalizerFormatter();
    }
    /**
     * Get the level map
     *
     * @return array
     */
    public function getLevelMap()
    {
        return $this->levelMap;
    }
}
