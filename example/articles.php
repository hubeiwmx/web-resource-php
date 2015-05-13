<?php

require __DIR__ . '/../vendor/autoload.php';

use Web\Resource\Collection;

$output = fopen(__DIR__ . '/output.csv', 'w');

$collection = new Collection('https://peerj.com/articles/index');

$collection->get('json', [
    'items' => function($data) {
        return $data['_items'];
    },
    'emit' => function($item) use ($output) {
        $authors = count($item['author']);

        foreach ($item['subjects'] as $subject) {
            fputcsv($output, [
                $item['doi'],
                $subject,
                $authors,
            ]);
        }
    }
]);
