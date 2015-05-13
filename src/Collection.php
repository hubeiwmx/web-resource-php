<?php

namespace Web\Resource;

use GuzzleHttp\Message\Request;

class Collection extends Resource {
    public function get($format, $callbacks = []) {
        foreach (['items', 'next', 'emit'] as $callback) {
            if (!isset($callbacks[$callback])) {
                $callbacks[$callback] = [$this, $callback];
            }
        }

        $this->data = []; // default emit adds to this array

        $options = $this->options($format);

        $url = $this->url;

        do {
            print "Fetching $url\n";

            $response = $this->client->get($url, $options);

            $data = $this->parse($response, $format);

            $items = call_user_func($callbacks['items'], $data, $response);

            foreach ($items as $item) {
                call_user_func($callbacks['emit'], $item);
            }

            $url = call_user_func($callbacks['next'], $data, $response);
        } while ($url);

        return $this->data;
    }

    protected function items($data, $response) {
        return $data;
    }

    protected function next($data, $response) {
        $links = Request::parseHeader($response, 'Link');

        foreach ($links as $link) {
            if ($link['rel'] == 'next') {
                return trim($link[0], '<>');
            }
        }
    }

    protected function emit($item) {
        $this->data[] = $item;
    }
}
