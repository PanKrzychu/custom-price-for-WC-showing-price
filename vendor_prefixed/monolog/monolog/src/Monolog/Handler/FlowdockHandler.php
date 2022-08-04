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

use CPWFreeVendor\Monolog\Logger;
use CPWFreeVendor\Monolog\Utils;
use CPWFreeVendor\Monolog\Formatter\FlowdockFormatter;
use CPWFreeVendor\Monolog\Formatter\FormatterInterface;
/**
 * Sends notifications through the Flowdock push API
 *
 * This must be configured with a FlowdockFormatter instance via setFormatter()
 *
 * Notes:
 * API token - Flowdock API token
 *
 * @author Dominik Liebler <liebler.dominik@gmail.com>
 * @see https://www.flowdock.com/api/push
 */
class FlowdockHandler extends \CPWFreeVendor\Monolog\Handler\SocketHandler
{
    /**
     * @var string
     */
    protected $apiToken;
    /**
     * @param string   $apiToken
     * @param bool|int $level    The minimum logging level at which this handler will be triggered
     * @param bool     $bubble   Whether the messages that are handled can bubble up the stack or not
     *
     * @throws MissingExtensionException if OpenSSL is missing
     */
    public function __construct($apiToken, $level = \CPWFreeVendor\Monolog\Logger::DEBUG, $bubble = \true)
    {
        if (!\extension_loaded('openssl')) {
            throw new \CPWFreeVendor\Monolog\Handler\MissingExtensionException('The OpenSSL PHP extension is required to use the FlowdockHandler');
        }
        parent::__construct('ssl://api.flowdock.com:443', $level, $bubble);
        $this->apiToken = $apiToken;
    }
    /**
     * {@inheritdoc}
     */
    public function setFormatter(\CPWFreeVendor\Monolog\Formatter\FormatterInterface $formatter)
    {
        if (!$formatter instanceof \CPWFreeVendor\Monolog\Formatter\FlowdockFormatter) {
            throw new \InvalidArgumentException('The FlowdockHandler requires an instance of Monolog\\Formatter\\FlowdockFormatter to function correctly');
        }
        return parent::setFormatter($formatter);
    }
    /**
     * Gets the default formatter.
     *
     * @return FormatterInterface
     */
    protected function getDefaultFormatter()
    {
        throw new \InvalidArgumentException('The FlowdockHandler must be configured (via setFormatter) with an instance of Monolog\\Formatter\\FlowdockFormatter to function correctly');
    }
    /**
     * {@inheritdoc}
     *
     * @param array $record
     */
    protected function write(array $record)
    {
        parent::write($record);
        $this->closeSocket();
    }
    /**
     * {@inheritdoc}
     *
     * @param  array  $record
     * @return string
     */
    protected function generateDataStream($record)
    {
        $content = $this->buildContent($record);
        return $this->buildHeader($content) . $content;
    }
    /**
     * Builds the body of API call
     *
     * @param  array  $record
     * @return string
     */
    private function buildContent($record)
    {
        return \CPWFreeVendor\Monolog\Utils::jsonEncode($record['formatted']['flowdock']);
    }
    /**
     * Builds the header of the API Call
     *
     * @param  string $content
     * @return string
     */
    private function buildHeader($content)
    {
        $header = "POST /v1/messages/team_inbox/" . $this->apiToken . " HTTP/1.1\r\n";
        $header .= "Host: api.flowdock.com\r\n";
        $header .= "Content-Type: application/json\r\n";
        $header .= "Content-Length: " . \strlen($content) . "\r\n";
        $header .= "\r\n";
        return $header;
    }
}
