<?php

require __DIR__ . '/../vendor/autoload.php';

use WebResource\Collection;
use WebResource\Request;

class SpotifyCollection extends Collection {
  protected function items($response) {
    $data = Request::json($response);

    return $data['items'];
  }

  protected function next($response) {
    $data = Request::json($response);

    return isset($data['next']) ? $data['next'] : null;
  }
}

// fetch all albums by this artist
$collection = new SpotifyCollection("https://api.spotify.com/v1/artists/0gusqTJKxtU1UTmNRMHZcv/albums", [
  'album_type' => 'single',
]);

foreach ($collection as $album) {
  print_r($album);
}
