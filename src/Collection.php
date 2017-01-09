<?php

namespace WebResource;

use GuzzleHttp\Psr7;

class Collection extends Resource implements \IteratorAggregate {
  public function getIterator() {
    $url = $this->url;

    do {
      $response = Request::fetch($url);

      foreach ($this->items($response) as $item) {
        yield $item;
      }
    } while ($url = $this->next($response));
  }

  protected function items($response) {
    return Request::json($response);
  }

  protected function next($response) {
    $links = Psr7\parse_header($response->getHeader('Link'));

    foreach ($links as $link) {
      if ($link['rel'] == 'next') {
        return trim($link[0], '<>');
      }
    }
  }
}
