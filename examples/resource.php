<?php

require __DIR__ . '/../vendor/autoload.php';

use WebResource\Resource;

// fetch this artist
$resource = new Resource('https://api.spotify.com/v1/artists/0gusqTJKxtU1UTmNRMHZcv');

print_r($resource->get());
