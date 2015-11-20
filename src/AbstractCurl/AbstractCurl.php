<?php namespace AbstractCurl;

use AbstractCurl\CurlResponse;
use AbstractCurl\TraitCurl;

/**
 * @author Kukuh Prabowo <kukuhpro@gmail.com>
 * @copyright 2015 Kukuh Prabowo
 * @version 1.0
 * @since php 5.4 or greater
 */
abstract class AbstractCurl
{
    use TraitCurl;

    /**
     * Class constructor. Setup primary parameters.
     *
     * @param array $curlOptions Common CURL options.
     */
    protected function __construct($curlOptions = array(), $header = [])
    {
        if (empty($header)) {
            $header[] = "Accept: */*";
            $header[] = "Cache-Control: max-age=0";
            $header[] = "Accept-Charset: utf-8;q=0.7,*;q=0.7";
            $header[] = "Pragma: no-cache";
        }

        if (empty($curlOptions)) {
            $curlOptions = array(
                CURLOPT_CONNECTTIMEOUT => 20,
                CURLOPT_TIMEOUT        => 10,
                CURLOPT_FAILONERROR    => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_HTTPHEADER     => $header,
            );
        } else if (!empty($header)) {
            $curlOptions[CURLOPT_HTTPHEADER] = $header;
        }

        $this->header = $header;
        $this->setCurlOptions($curlOptions);
        /**
         * Generate value lists header that already created on header construct
         */
        foreach ($header as $value) {
            $list = explode(':', $value);
            array_push($this->listsHeader, $list[0]);
        }
    }

    /**
     * Sets CURL options for all requests.
     *
     * @param array $curlOptions CURL options.
     */
    protected function setCurlOptions($curlOptions)
    {
        if (empty($this->curlOptions)) {
            if (!array_key_exists(CURLOPT_FOLLOWLOCATION, $curlOptions)) {
                $curlOptions[CURLOPT_FOLLOWLOCATION] = 1;
            }
            $curlOptions[CURLOPT_RETURNTRANSFER] = 1;
            $this->curlOptions                   = $curlOptions;
        } else {
            $this->curlOptions = $curlOptions + $this->curlOptions;
        }
    }

    /**
     * setting header for curlopt_header
     * @param string $nameHeader header name
     * @param string $value      value of header name
     */
    protected function setHeader($nameHeader = '', $value = '')
    {
        $header = $this->header;
        $flag   = in_array($nameHeader, $this->listsHeader);
        if (!$flag) {
            $header[] = $nameHeader . ': ' . $value;
            array_push($this->listsHeader, $nameHeader);
        } else {
            $key_index          = array_search($nameHeader, $this->listsHeader);
            $header[$key_index] = $nameHeader . ': ' . $value;
        }
        $this->header      = $header;
        $this->curlOptions = [CURLOPT_HTTPHEADER => $header] + $this->curlOptions;
    }

    /**
     * Setting for all variable curl who want to execute.
     * @param  String $service url endpoint for access api
     * @param  array  $params  variabel requirement
     */
    protected function setup($service, $params = [])
    {
        $url = $this->endPoint . $service;
        if ('GET' == $this->method) {
            if (!empty($params)) {
                $url .= '?' . http_build_query($params);
            }
        } else {
            $this->setCurlOptions([CURLOPT_POST => true]);
            if ($this->json) {
                $this->setCurlOptions([CURLOPT_POSTFIELDS => json_encode($params)]);
                $this->setHeader('Content-Type', 'application/json');
            } else {
                if ($this->http_query) {
                    $url .= '?' . http_build_query($params);
                } else {
                    $this->setCurlOptions([CURLOPT_POSTFIELDS => $params]);
                }
            }
        }
        $this->setCurlOptions([CURLOPT_URL => $url]);
    }

    /**
     * execution curl with url
     * @return ObjectClass $response response data
     */
    protected function _send()
    {
        // Init new CURL session
        $ch = curl_init();
        foreach ($this->curlOptions as $option => $value) {
            curl_setopt($ch, $option, $value);
        }

        if (env('APP_ENV') == 'local') {
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $http_response = curl_exec($ch);
        $http_status   = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        $http_error    = curl_error($ch);
        $header_size   = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        /**
         * Generate response data to an Object Class
         * @var CurlResponse
         */
        $response = new CurlResponse($http_response, $header_size, $this->curlOptions[CURLOPT_HEADER]);
        if (200 <= $http_status && 300 > $http_status) {
            return ['Status' => '1', 'Message' => 'HTTP Success: ' . $http_status, 'Response' => $response];
        } else {
            return array('Status' => '88', 'Message' => (empty($http_error) ? 'HTTP Error: ' . $http_status : $http_error), 'Response' => $response);
        }

    }
}
