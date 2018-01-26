<?php

namespace Solarium\Client;

use Http\Client\Common\HttpClientPool;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\UriFactoryDiscovery;
use Http\Message\RequestFactory;
use Http\Message\UriFactory;
use Psr\Http\Message\UriInterface;

class HttpClientConfigurator
{
    /**
     * @var UriInterface[]
     */
    private $endpoints = [];

    /**
     * @var UriFactory
     */
    private $uriFactory;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Plugin[]
     */
    private $prependPlugins = [];

    /**
     * @var Plugin[]
     */
    private $appendPlugins = [];

    /**
     * @param HttpClient|null     $httpClient
     * @param UriFactory|null     $uriFactory
     * @param RequestFactory|null $requestFactory
     */
    public function __construct(HttpClient $httpClient = null, UriFactory $uriFactory = null, RequestFactory $requestFactory = null)
    {
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->uriFactory = $uriFactory ?? UriFactoryDiscovery::find();
    }

    /**
     * Add a Solr endpoint.
     *
     * @param string $endpoint The Solr endpoint
     *
     * @return self
     */
    public function addEndpoint(string $endpoint): self
    {
        $this->endpoints[] = $this->uriFactory->createUri($endpoint);

        return $this;
    }

    /**
     * Return a configured HttpClient.
     *
     * @todo: use use Http\Client\Common\HttpClientRouter for master-slave routing?
     *
     * @return HttpClient
     */
    public function createConfiguredClient(): HttpClient
    {
        if (empty($this->endpoints)) {
            throw new \RuntimeException('No endpoints defined!');
        }

        if (1 === count($this->endpoints)) {
            return $this->createCondifuredClientForEndpoint(current($this->endpoints));
        }

        $clientPool = $this->getClientPool();
        foreach ($this->endpoints as $uri) {
            $clientPool->addHttpClient($this->createCondifuredClientForEndpoint($uri));
        }

        return $clientPool;
    }


    private function createCondifuredClientForEndpoint(UriInterface $uri): HttpClient
    {
        $plugins = $this->prependPlugins;
        $plugins[] = new Plugin\AddHostPlugin($uri);
        $plugins[] = new Plugin\HeaderDefaultsPlugin([
            'User-Agent' => 'PHP Solarium (https://github.com/solariumphp/solarium)',
        ]);

        $plugins = array_merge($plugins, $this->appendPlugins);

        return new PluginClient($this->httpClient, $plugins);
    }

    /**
     * @param Plugin[] $plugin
     *
     * @return self
     */
    public function appendPlugin(Plugin ...$plugin): self
    {
        foreach ($plugin as $p) {
            $this->appendPlugins[] = $p;
        }
        return $this;
    }
    /**
     * @param Plugin[] $plugin
     *
     * @return self
     */
    public function prependPlugin(Plugin ...$plugin): self
    {
        $plugin = array_reverse($plugin);
        foreach ($plugin as $p) {
            array_unshift($this->prependPlugins, $p);
        }
        return $this;
    }

    /**
     * Returns a client pool, by using the configured strategy.
     *
     * @return HttpClientPool
     */
    private function getClientPool(): HttpClientPool
    {
        // @todo: allow the strategy to be defined by the external
        //        - random
        //        - round robin
        //        - zookeeper?
        //        - HttpClientRouter?
        return new HttpClientPool\RoundRobinClientPool();
    }
}