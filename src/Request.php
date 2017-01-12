<?php

namespace WebResource;

use GuzzleHttp\Client;

class Request {
  private static $client;

  private static function getClient() {
    if (!self::$client) {
      self::$client = new Client([
        //'debug' => true,
        'headers' => [
          'user-agent' => 'web-resource/0.2',
          'accept-encoding' => 'gzip,deflate',
        ]
      ]);
    }

    return self::$client;
  }

  private static function status($response) {
    switch ($response->getStatusCode()) {
      case 200:
        break;

      case 409:
        // TODO: rate limiting, retries (with middleware?)
      default:
        throw new \Exception($response->getReasonPhrase());
    }
  }

  public static function fetch($url) {
    //print "Fetching $url\n";

    $response = self::getClient()->get($url);

    self::status($response);

    return $response;
  }
}
