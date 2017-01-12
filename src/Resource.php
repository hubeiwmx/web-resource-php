<?php

namespace WebResource;

class Resource {
  protected $url;

  public function __construct($url, $params = []) {
    if ($params) {
      $url .= '?' . http_build_query($params);
    }

    $this->url = $url;
  }

  public function get() {
    $response = Request::fetch($this->url);

    return json_decode($response->getBody(), true);
  }
}
