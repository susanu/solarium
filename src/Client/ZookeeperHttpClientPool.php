<?php

namespace Solarium\Client;

use Http\Client\Common\Exception\HttpClientNotFoundException;
use Http\Client\Common\HttpClientPool;

class ZookeeperHttpClientPool extends HttpClientPool {

    protected function chooseHttpClient()
    {
        throw new HttpClientNotFoundException();
    }

    public function addHttpClient($client)
    {
        throw new \RuntimeException('ZookeeperHttpClientPool does not accept clients!');
    }
}