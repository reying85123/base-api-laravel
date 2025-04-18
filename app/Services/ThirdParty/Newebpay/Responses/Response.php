<?php

namespace App\Services\ThirdParty\Newebpay\Responses;

class Response
{
    /**
     * @var object \GuzzleHttp\Psr7\Response $response
     */
    public $response;

    /**
     * @var array Cache data for body with array data type
     */
    protected $bodyArrayCache = null;

    /**
     * @var object Cache data for body with array object type
     */
    protected $bodyObjectCache = null;

    /**
     * Constructor
     *
     * @param object \GuzzleHttp\Psr7\Response $response
     */
    function __construct(\GuzzleHttp\Psr7\Response $response) 
    {
        $this->response = $response;
    }

    /**
     * Get LINE Pay response body as array
     *
     * @return array
     */
    public function toArray()
    {
        // Cache
        if (!$this->bodyArrayCache) {
            $this->bodyArrayCache = json_decode($this->response->getBody(), true);
        }

        return $this->bodyArrayCache;
    }

    /**
     * Get LINE Pay response body as object
     *
     * @return object
     */
    public function toObject()
    {
        // Cache
        if (!$this->bodyObjectCache) {
            $this->bodyObjectCache = json_decode($this->response->getBody(), false);
        }

        return $this->bodyObjectCache;
    }

    public function isSuccess()
    {
        return $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() < 300 && $this->toArray()['Status'] === 'SUCCESS';
    }
}