<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Report;

/**
 * Request details.
 *
 * @author Carl Alexander <carlalexander@helthe.co>
 */
class Request implements \JsonSerializable
{
    /**
     * Request content.
     *
     * @var string
     */
    private $content;

    /**
     * Cookies.
     *
     * @var array
     */
    private $cookies;

    /**
     * Headers.
     *
     * @var array
     */
    private $headers;

    /**
     * HTTP request method.
     *
     * @var string
     */
    private $method;

    /**
     * Query variables.
     *
     * @var array
     */
    private $query;

    /**
     * HTTP POST variables.
     *
     * @var array
     */
    private $post;

    /**
     * Request URL.
     *
     * @var string
     */
    private $url;

    /**
     * Creates an instance of Request from PHP globals.
     *
     * @return Request
     */
    public static function createFromGlobals()
    {
        return new self(self::getUrl(), $_SERVER['REQUEST_METHOD'], file_get_contents('php://input'), self::getHeaders(), $_COOKIE, $_GET, $_POST);
    }

    /**
     * Constructor.
     *
     * @param string $url
     * @param string $method
     * @param string $content
     * @param array  $headers
     * @param array  $cookies
     * @param array  $query
     * @param array  $post
     */
    public function __construct($url, $method = 'GET', $content = '', array $headers = array(), array $cookies = array(), array $query = array(), array $post = array())
    {
        $this->content = $content;
        $this->cookies = $cookies;
        $this->method = strtoupper($method);
        $this->post = $post;
        $this->query = $query;
        $this->url = $url;

        $this->setHeaders($headers);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return array(
            'content' => $this->content,
            'cookies' => $this->cookies,
            'headers' => $this->headers,
            'method'  => $this->method,
            'post'    => $this->post,
            'query'   => $this->query,
            'url'     => $this->url
        );
    }

    /**
     * Gets the HTTP headers from PHP globals.
     *
     * @return array
     */
    private static function getHeaders()
    {
        $headers = array();

        foreach ($_SERVER as $key => $value) {
            if (0 === strpos($key, 'HTTP_')) {
                $headers[substr($key, 5)] = $value;
            } elseif (in_array($key, array('CONTENT_LENGTH', 'CONTENT_MD5', 'CONTENT_TYPE'))) {
                $headers[$key] = $value;
            }
        }

        return $headers;
    }

    /**
     * Generate the full request URL from PHP environment variables.
     *
     * @return string
     */
    private static function getUrl()
    {
        $scheme = 'http';
        $port = $_SERVER['SERVER_PORT'];

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $scheme = 'https';
        }

        $url = $scheme . '://' . $_SERVER['HTTP_HOST'];

        if (('http' == $scheme && $port != 80) || ('https' == $scheme && $port != 443)) {
            $url .= ':' . $port;
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $url .= $_SERVER['REQUEST_URI'];
        }

        return $url;
    }

    /**
     * Set the headers.
     *
     * @param array $headers
     *
     * @return array
     */
    private function setHeaders(array $headers)
    {
        $this->headers = array();

        foreach ($headers as $name => $value) {
            $name = implode('-', array_map('ucfirst', explode('-', strtr($name, '_', '-'))));
            $this->headers[$name] = $value;
        }
    }
}
