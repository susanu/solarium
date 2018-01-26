<?php

namespace Solarium\Core\Query;

use Solarium\Core\ConfigurableInterface;

/**
 * Query interface.
 */
interface QueryInterface extends ConfigurableInterface
{
    /**
     * @return bool
     */
    public function requiresSolrCore(): bool;

    /**
     * Get type for this query.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get the requestbuilder class for this query.
     *
     * @return RequestBuilderInterface
     */
    public function getRequestBuilder(): RequestBuilderInterface;

    /**
     * Get the response parser class for this query.
     *
     * @return ResponseParserInterface
     */
    public function getResponseParser(): ResponseParserInterface;

    /**
     * Set handler option.
     *
     * @param string $handler
     *
     * @return self
     */
    public function setHandler(string $handler): self;

    /**
     * Get handler option.
     *
     * @return string
     */
    public function getHandler(): string;

    /**
     * Set resultclass option.
     *
     * If you set a custom result class it must be available through autoloading
     * or a manual require before calling this method. This is your
     * responsibility.
     *
     * Also you need to make sure this class implements the ResultInterface
     *
     * @param string $className
     *
     * @return self
     */
    public function setResultClass(string $className): self;

    /**
     * Get resultclass option.
     *
     * @return string
     */
    public function getResultClass(): string;

    /**
     * Get a helper instance.
     *
     * @return Helper
     */
    //public function getHelper(): Helper;

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
    public function addParam(string $name, string $value): self;

    /**
     * Get extra params.
     *
     * @return array
     */
    public function getParams(): array;
}
