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
      
      return $this->parse($response->getBody(), $response);
  }
  
  protected function parse($data) {
    return json_decode($data, true);
  }
}
