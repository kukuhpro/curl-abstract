# curl-abstract
This package is an abstract class for curl use in php. 
Sometimes in our application we like to connect our application with many thirdparty services via Http Connection. This package will help you create http connection more easier, with many different configuration on it. 

### How to use this package 
```
{
  "require" : {
    "kukuhprabowo/curl-abstract" : "dev-master"
  }
}
```

### Example for GCM HTTP Connection
```php
<?php
    required_once("vendor/autoload.php");

    use Kukuhprabowo\AbstractCurl;

    /**
     * This class GCM is for setup configuration and method
     * on Google cloud messaging. Just extends AbstractCurl
     * in your new class. 
     */
    class GCM extends AbstractCurl {
        public function __construct()
        {
            /**
             * Setting naming of your apps 
             * @type {String}
             */
            $this->apps       = 'Google Cloud Messaging Application';
            /**
             * set configuration curl options first
             * @type {Array}
             */
            $curl_set_options = [
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => true,
                CURLOPT_FAILONERROR    => false,
            ];
            /**
             * set your configuration header
             * @type {Array}
             */
            $header = [
                'Authorization: 'key YOUR_API_KEY',
                'Content-Type: application/json',
            ];
            /**
             * set url endPoint for http connection
             * @type {String}
             */
            $this->endPoint = 'https://gcm-http.googleapis.com/gcm';
            /**
             * calling construct parent with curloptions and header, to make 
             * things goes right.
             */
            parent::__construct($curl_set_options, $header);
        }

        /**
         * create method on http connection with a function
         * @param  {Array}  $params Data that you want to send, it must be 
         *                          an array
         * @return ObjectClass return of this result will object class 
         *                     CurlResponse
         */
        public function send($params = [])
        {
            $this->method     = 'POST';
            $this->json       = true;
            $this->http_query = false;
            $service          = '/send;'

            $this->setup($service, $params);

            $data_response = $this->_send();

            return $data_response;
        }
    }
```

#### For complete Tutorial visit [this link](http://kukuhpro.github.io/php/curl/2016/01/09/easy-curl-php.html)
