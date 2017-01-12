<?php

namespace WebResource;

use GuzzleHttp\Psr7;

class Collection extends Resource implements \IteratorAggregate {
  public function getIterator() {
    $url = $this->url;

    do {
      $response = Request::fetch($url);

      $data = $this->parse($response);

      foreach ($this->items($data, $response) as $item) {
        yield $item;
      }
    } while ($url = $this->next($data, $response));
  }

  protected function parse($response) {
    return json_decode($response->getBody(), true);
  }

  protected function items($data, $response) {
    return $data;
  }

  protected function next($data, $response) {
    $links = Psr7\parse_header($response->getHeader('Link'));

    foreach ($links as $link) {
      if ($link['rel'] == 'next') {
        return trim($link[0], '<>');
      }
    }
  }
}
