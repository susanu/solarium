<?php

namespace Solarium\Core\Query;

use Solarium\Core\Configurable;
use Solarium\QueryType\Select\Query\Query;

/**
 * Base class for all query types, not intended for direct usage.
 */
abstract class AbstractQuery extends Configurable implements QueryInterface
{
    const WT_JSON = 'json';

    const WT_PHPS = 'phps';

    /**
     * Helper instance.
     *
     * @var Helper
     */
    protected $helper;

    /**
     * Extra query params (e.g. dereferenced params).
     *
     * @var array
     */
    protected $params = [];

    public function requiresSolrCore(): bool
    {
        return false;
    }


    public function setHandler(string $handler): QueryInterface
    {
        $this->setOption('handler', $handler);

        return $this;
    }

    public function getHandler(): string
    {
        return $this->getOption('handler');
    }

    public function setResultClass(string $className): QueryInterface
    {
        $this->setOption('resultclass', $className);

        return $this;
    }

    public function getResultClass(): string
    {
        return $this->getOption('resultclass');
    }

    /**
     * Set timeAllowed option.
     *
     * @param int $value
     *
     * @return self Provides fluent interface
     */
    public function setTimeAllowed($value)
    {
        return $this->setOption('timeallowed', $value);
    }

    /**
     * Get timeAllowed option.
     *
     * @return int|null
     */
    public function getTimeAllowed()
    {
        return $this->getOption('timeallowed');
    }

    /**
     * Set omitHeader option.
     *
     * @param bool $value
     *
     * @return self Provides fluent interface
     */
    public function setOmitHeader($value)
    {
        return $this->setOption('omitheader', $value);
    }

    /**
     * Get omitHeader option.
     *
     * @return bool
     */
    public function getOmitHeader()
    {
        return $this->getOption('omitheader');
    }

    /**
     * Get a helper instance.
     *
     * Uses lazy loading: the helper is instantiated on first use
     *
     * @return Helper
     */
    public function getHelper(): Helper
    {
        if (null === $this->helper) {
            $this->helper = new Helper($this);
        }

        return $this->helper;
    }

    /**
     * Add extra params to the request.
     *
     * Only intended for internal use, for instance with dereferenced params.
     * Therefore the params are limited in functionality. Only add and get
     *
     * @param string $name
     * @param string $value
     *
     * @return self
     */
    public function addParam(string $name, string $value): QueryInterface
    {
        $this->params[$name] = $value;

        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Set responsewriter option.
     *
     * @param string $value
     *
     * @return self Provides fluent interface
     */
    public function setResponseWriter($value)
    {
        return $this->setOption('responsewriter', $value);
    }

    /**
     * Get responsewriter option.
     *
     * Defaults to json for backwards compatibility and security.
     *
     * If you can fully trust the Solr responses (phps has a security risk from untrusted sources) you might consider
     * setting the responsewriter to 'phps' (serialized php). This can give a performance advantage,
     * especially with big resultsets.
     *
     * @return string
     */
    public function getResponseWriter()
    {
        $responseWriter = $this->getOption('responsewriter');
        if (null === $responseWriter) {
            $responseWriter = self::WT_JSON;
        }

        return $responseWriter;
    }
}
