<?php

require_once(__DIR__.'/../vendor/autoload.php');

$configurator = (new \Solarium\Client\HttpClientConfigurator())
    ->addEndpoint('http://localhost:8983/solr');

$requestBuilder = new \Solarium\Client\RequestBuilder();
$eventDispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();

$solrClient = new \Solarium\Client\SolrClient($configurator->createConfiguredClient(), $requestBuilder, $eventDispatcher);
$selectQuery = new Solarium\QueryType\Select\Query\Query();

$solrClient->execute($selectQuery);