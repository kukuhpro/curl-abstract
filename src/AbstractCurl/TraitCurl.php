<?php namespace Kukuhprabowo;

/**
 * @author Kukuh Prabowo <kukuhpro@gmail.com>
 * @copyright 2015 Kukuh Prabowo
 * @version 1.0
 * @since php 5.4 or greater
 */
trait TraitCurl
{
    protected $header             = [];
    protected $method             = 'POST';
    protected $json               = true;
    protected $connection_timeout = 10;
    protected $timeout            = 10;
    protected $listsHeader        = [];
    protected $curlOptions;
    protected $endPoint;
    protected $apps;
    protected $http_query = false;
    protected $https      = false;

    /**
     * Setting method http for curl
     * @param string $method
     */
    protected function setMethod($method = '')
    {
        $this->method = $method;
    }
    /**
     * Setting json boolean false or true
     * @param Boolean $json
     */
    protected function setJson($json)
    {
        $this->json = $json;
    }
}
