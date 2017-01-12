<?php

require __DIR__ . '/../vendor/autoload.php';

use WebResource\Collection;

class SpotifyCollection extends Collection {
  protected function items($data, $response) {
    return $data['items'];
  }

  protected function next($data, $response) {
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
