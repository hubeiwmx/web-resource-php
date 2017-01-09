<?php

require __DIR__ . '/../vendor/autoload.php';

use WebResource\Collection;

$collection = new Collection('https://api.github.com/users/hubgit/repos');

foreach ($collection as $repo) {
  print_r($repo);
}
