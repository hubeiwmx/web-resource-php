## Composer

```bash
composer require hubgit/web-resource
```

## Usage

See [examples](https://github.com/hubgit/web-resource-php/tree/master/examples).

### Fetch a single resource

```php
use WebResource\Resource;

$resource = new Resource('https://api.spotify.com/v1/artists/0gusqTJKxtU1UTmNRMHZcv');

$artist = $resource->get(); // `$artist` is an array
```

### Fetch a paginated collection

```php
use WebResource\Collection;

$collection = new Collection('https://api.github.com/users/hubgit/repos');

foreach ($collection as $repo) {
  // do something with the `$repo` array
}
```

### Fetch a paginated collection with custom processing and pagination

```php
use WebResource\Collection;
use WebResource\Request;

class SpotifyCollection extends Collection {
  // find the array of items in the JSON response
  protected function items($response) {
    $data = Request::json($response);
    
    return $data['items'];
  }
  
  // find the URL of the next page in the JSON response
  protected function next($response) {
    $data = Request::json($response);
    
    return isset($data['next']) ? $data['next'] : null;
  }
}

// first parameter is a URL, second parameter is an array of query parameters
$collection = new SpotifyCollection('https://api.spotify.com/v1/artists/0gusqTJKxtU1UTmNRMHZcv/albums', [
  'album_type' => 'single',
]);

foreach ($collection as $album) {
  // do something with the `$album` array
}
```

