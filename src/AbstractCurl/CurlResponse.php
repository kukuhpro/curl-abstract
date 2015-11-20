<?php namespace AbstractCurl;

/**
 * @author Kukuh Prabowo <kukuhpro@gmail.com>
 * @copyright 2015 Kukuh Prabowo
 * @version 1.0
 * @since php 5.4 or greater
 */

class CurlResponse
{
    /**
     * Body data description response from hyrbis application api
     * @var array
     */
    public $body = [];

    /**
     * Header data desscription response from hybris application api
     * @var array
     */
    public $header = [];

    /**
     * this function split response api to two part, body and header.
     * @param String  $data
     * @param Integer  $header_size
     * @param Boolean $header
     */
    public function __construct($data, $header_size, $header = false)
    {
        if ($header) {
            $headers     = array();
            $header_text = substr($data, 0, $header_size);
            $array       = explode("\r\n", $header_text);
            $len         = count($array);
            for ($x = 0; $x < ($len - 2); $x++) {
                if ($x == 0) {
                    $headers['http_code'] = $array[$x];
                } else {
                    list($key, $value) = explode(': ', $array[$x]);
                    $headers[$key]     = $value;
                }
            }
            $this->header = $headers;
            $this->body   = json_decode(substr($data, $header_size));
        } else {
            $this->body = json_decode($data);
        }
    }
}
