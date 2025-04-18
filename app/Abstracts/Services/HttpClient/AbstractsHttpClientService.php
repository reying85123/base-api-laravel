<?php

namespace App\Abstracts\Services\HttpClient;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;

abstract class AbstractsHttpClientService
{
    protected $httpClient;

    /**
     * 
     * @param array $opt httpClientOpt
     */
    public function setHttpClient(array $opt = [])
    {
        $this->httpClient = new HttpClient($opt);
    }

    /**
     * Request Handlder
     *
     * @param string $method    GET、POST、PATCH、DELETE
     * @param string $uri       api網址
     * @param array $optBody    請求資料body
     * @param array $optHeaders 請求header
     * @return Response
     */
    protected function requestHandler($method, $uri, array $optBody = [], array $optHeaders = [])
    {
        $options = [];

        $options = array_merge($options, $optBody);

        $options['headers'] = array_merge(($options['headers'] ?? []), $optHeaders);

        try {
            $response = $this->httpClient->request($method, $uri, $options);
        } catch (RequestException $e) {
            $response = $e->getResponse();
        }

        return new Response($response);
    }
}
