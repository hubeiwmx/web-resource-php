<?php

namespace Web\Resource;

use GuzzleHttp\Client;

class Resource {
    public function __construct($url, $params = []) {
        if ($params) {
            $url .= '?' . http_build_query($params);
        }

        $this->url = $url;
        $this->client = new Client;
    }

    public function get($format) {
        $options = $this->options($format);

        print "Fetching {$this->url}\n";

        $response = $this->client->get($this->url, $options);

        return $this->parse($response, $format);
    }

    protected function options($format) {
        switch ($format) {
            case 'json':
                return [
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ];
        }
    }

    protected function parse($response, $format) {
        switch ($response->getStatusCode()) {
            case 200:
                break;

            case 409:
                // TODO: rate-limiting

            default:
                throw new \Exception($response->getBody());
        }

        switch ($format) {
            case 'json':
                return $response->json();

            default:
                return $response->getBody();
        }
    }
}
