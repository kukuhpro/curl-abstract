# curl-abstract
This package is an abstract class for curl use in php.

```
{
  "require" : {
    "kukuhprabowo/curl-abstract" : "dev-master"
  }
}
```

## Example for GCM HTTP Connection
```
<?php
    required_once("vendor/autoload.php");

    use Kukuhprabowo/AbstractCurl;

    class GCM extends AbstractCurl {
        public function __construct()
        {
            $this->apps       = 'Google Cloud Messaging Application';
            $curl_set_options = [
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => true,
                CURLOPT_FAILONERROR    => false,
            ];
            $header = [
                'Authorization: 'key YOUR_API_KEY',
                'Content-Type: application/json',
            ];
            $this->endPoint = 'https://gcm-http.googleapis.com/gcm';
            parent::__construct($curl_set_options, $header);
        }

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
