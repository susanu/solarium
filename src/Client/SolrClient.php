<?php

namespace Solarium\Client;

use Http\Client\HttpClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Solarium\Core\Event\Events;
use Solarium\Core\Query\QueryInterface;
use Solarium\Core\Event;
use Solarium\Core\Query\Result\ResultInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SolrClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var string
     */
    private $defaultCore = null;

    public function __construct(HttpClient $httpClient, RequestBuilder $requestBuilder, EventDispatcherInterface $eventDispatcher)
    {
        $this->httpClient = $httpClient;
        $this->eventDispatcher = $eventDispatcher;
        $this->requestBuilder = $requestBuilder;
    }

    /**
     * Execute a query.
     *
     * @param QueryInterface $query
     * @param string|null    $solrCore
     *
     * @return ResultInterface
     */
    public function execute(QueryInterface $query, string $solrCore = null): ResultInterface
    {
        $event = new Event\PreExecute($query);
        $this->eventDispatcher->dispatch(Events::PRE_EXECUTE, $event);
        if (null !== $event->getResult()) {
            return $event->getResult();
        }

        $request = $this->createRequest($query);
        $response = $this->executeRequest($request, $endpoint);
        $result = $this->createResult($query, $response);

        $this->eventDispatcher->dispatch(
            Events::POST_EXECUTE,
            new PostExecuteEvent($query, $result)
        );

        return $result;
    }

    /**
     * Execute a request and return the response.
     *
     * @param Request              $request
     * @param Endpoint|string|null $endpoint
     *
     * @return Response
     */
    public function executeRequest($request, $endpoint = null)
    {
        // load endpoint by string or by using the default one in case of a null value
        if (!($endpoint instanceof Endpoint)) {
            $endpoint = $this->getEndpoint($endpoint);
        }

        $event = new Event\PreExecuteRequest($request, $endpoint);
        $this->eventDispatcher->dispatch(Events::PRE_EXECUTE_REQUEST, $event);
        if (null !== $event->getResponse()) {
            $response = $event->getResponse(); //a plugin result overrules the standard execution result
        } else {
            $response = $this->getAdapter()->execute($request, $endpoint);
        }

        $this->eventDispatcher->dispatch(
            Events::POST_EXECUTE_REQUEST,
            new PostExecuteRequestEvent($request, $endpoint, $response)
        );

        return $response;
    }


    /**
     * Creates a request based on a query instance.
     *
     *
     * @param QueryInterface $query
     *
     * @throws UnexpectedValueException
     *
     * @return Request
     */
    public function createRequest(QueryInterface $query)
    {
        $event = new PreCreateRequestEvent($query);
        $this->eventDispatcher->dispatch(Events::PRE_CREATE_REQUEST, $event);
        if (null !== $event->getRequest()) {
            return $event->getRequest();
        }

        $requestBuilder = $query->getRequestBuilder();
        if (!$requestBuilder || !($requestBuilder instanceof RequestBuilderInterface)) {
            throw new UnexpectedValueException('No requestbuilder returned by querytype: '.$query->getType());
        }

        $request = $requestBuilder->build($query);

        $this->eventDispatcher->dispatch(
            Events::POST_CREATE_REQUEST,
            new PostCreateRequestEvent($query, $request)
        );

        return $request;
    }


    /**
     * Execute a request and return the response.
     *
     * @param RequestInterface $request
     *
     * @throws \Exception
     * @throws \Http\Client\Exception
     *
     * @return ResponseInterface
     */
    public function executeRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }



}