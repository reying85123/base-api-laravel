<?php

namespace App\Abstracts\Services\HttpClient;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Response implements Arrayable, Jsonable
{
    /**
     * @var \GuzzleHttp\Psr7\Response $response
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

    const FORMAT_JSON = 'json';
    const FORMAT_MULTIPART = 'multipart';
    const FORMAT_BINARY = 'binary';
    const FORMAT_OTHER = 'other';

    /**
     * Constructor
     *
     * @param \GuzzleHttp\Psr7\Response $response
     */
    function __construct(\GuzzleHttp\Psr7\Response $response)
    {
        $this->response = $response;
    }

    /**
     * Check if the response is successful
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() < 300;
    }

    /**
     * Get response body format
     *
     * @return string
     */
    public function getContentFormat(): string
    {
        $contentType = $this->response->getHeaderLine('Content-Type');

        // Mapping of content types to formats
        $formats = [
            'application/json' => self::FORMAT_JSON,
            'application/octet-stream' => self::FORMAT_BINARY,
            'multipart/form-data' => self::FORMAT_MULTIPART,
        ];

        // Use array_filter to check if any of the formats match the content type
        $matchedFormat = array_filter($formats, fn($format) => strpos($contentType, $format) !== false);

        // Return the first matched format or FORMAT_OTHER if none match
        return $matchedFormat ? reset($matchedFormat) : self::FORMAT_OTHER;
    }

    /**
     * Get response body as array
     *
     * @return array|string|null
     */
    public function toArray()
    {
        return $this->getBodyContent('array');
    }

    /**
     * Get response body as object
     *
     * @return object|string|null
     */
    public function toObject()
    {
        return $this->getBodyContent('object');
    }

    /**
     * Convert the response body to JSON
     *
     * @param int $options JSON encoding options
     * @return string
     */
    public function toJson($options = 0)
    {
        if (!$this->bodyObjectCache) {
            $this->toObject();
        }

        return json_encode($this->bodyObjectCache, $options);
    }

    /**
     * Get content based on format
     *
     * @param string $type 'array' or 'object'
     * @return array|string|null
     */
    protected function getBodyContent(string $type)
    {
        $format = $this->getContentFormat();

        $handlers = [
            self::FORMAT_BINARY => fn() => $this->getBinaryContent(),
            self::FORMAT_JSON => fn() => $this->getContent('array', true),
            self::FORMAT_MULTIPART => fn() => $this->getContent('array', true),
            self::FORMAT_OTHER => fn() => $this->getOtherContent(),
        ];

        return $handlers[$format]();
    }

    /**
     * Cache content based on the type
     *
     * @param string $type 'array' or 'object'
     * @param bool $isJson
     * @return mixed
     */
    protected function getContent(string $type, bool $isJson)
    {
        $cacheKey = $isJson ? 'bodyArrayCache' : 'bodyObjectCache';

        if (!$this->$cacheKey) {
            $this->$cacheKey = json_decode($this->response->getBody(), !$isJson);
        }

        return $this->$cacheKey;
    }

    /**
     * Get content for other formats
     *
     * @return mixed
     */
    protected function getOtherContent()
    {
        $content = $this->response->getBody()->getContents();
        return (object)[
            'data' => $content,
        ];
    }

    /**
     * Get binary content, returns base64 encoded binary content
     *
     * @return array
     */
    protected function getBinaryContent()
    {
        $content = $this->response->getBody()->getContents();
        return (object)[
            'data' => $content,
            'base64' => base64_encode($content),
        ];
    }
}
