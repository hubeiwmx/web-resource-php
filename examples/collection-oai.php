<?php

require 'vendor/autoload.php';

use WebResource\Collection;

class OaiCollection extends Collection {
  protected $verb;

  public function __construct($url, $params) {
    $this->verb = $params['verb'];

    parent::__construct($url, $params);
  }

  public function parse ($response) {
    $doc = new \DOMDocument;
    $doc->loadXML($response->getBody(), LIBXML_DTDLOAD | LIBXML_NONET);

    return $doc;
  }

  protected function items ($data, $response) {
    $xpath = $this->xpath($data);

    // https://github.com/nikic/iter

    return \iter\map(function (\DOMNode $node) use ($xpath) {
      return [
        'spec' => $xpath->evaluate('string(oai:setSpec)', $node),
        'name' => $xpath->evaluate('string(oai:setName)', $node),
      ];
    }, $xpath->query("oai:{$this->verb}/oai:set"));
  }

  protected function next ($data, $response) {
    $xpath = $this->xpath($data);

    if (!$token = $xpath->evaluate("string(oai:{$this->verb}/oai:resumptionToken)")) {
      return null;
    }

    return $this->url . '&' . http_build_query([
      'resumptionToken' => $token
    ]);
  }

  private function xpath (\DOMDocument $doc) {
    $xpath = new \DOMXPath($doc);
    $xpath->registerNamespace('oai', 'http://www.openarchives.org/OAI/2.0/');

    return $xpath;
  }
}

////////////

$output = fopen('sets.csv', 'w');

//$server = 'http://oai.crossref.org/OAIHandler';
$server = 'https://oai.datacite.org/oai';

$collection = new OaiCollection($server, [
  'verb' => 'ListSets',
]);

foreach ($collection as $item) {
  fputcsv($output, $item);
}
