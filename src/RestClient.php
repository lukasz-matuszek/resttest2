<?php

namespace Lib;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

class RestClient
{

    private $baseUrl;
    private $authMethod;
    private $authUser;
    private $authPassword;
    private $authUrl;
    private $token;

    protected $service;
    protected $psrfactory;

    public function __construct(HttpServiceInterface $httpService)
    {
        $this->service = $httpService;
        $this->loadConfig();
        $this->psrfactory = new Psr17Factory();
    }

    protected function loadConfig()
    {
        $conf = Config::section("resttest");
        $this->setBaseUrl($conf['baseUrl']);

        $confAuth = Config::section("resttestauth");
        $this->setAuth($confAuth);
    }

    public function setHttpService(HttpServiceInterface $httpService)
    {
        $this->service = $httpService;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function setAuth(array $conf)
    {
        //  Authorization method config
        $this->authMethod = $conf['authMethod'];
        $this->authUser = $conf['authUser'];
        $this->authPassword = $conf['authPassword'];
        $this->authUrl = $conf['authUrl'];
    }

    protected function setHeaders()
    {
        $headers = [];

        // prepare headers
        // based on auth method

        return $headers;
    }
    /* REST methods */

    /* retrieve headers information */
    public function head(array $data, string $requestUrl): ResponseInterface
    {
        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psr->createUri( $this->baseUrl.$requestUrl);
        $request = $this->psr->createRequest('HEAD', $uri, $headers);

        return $this->service->sendRequest($request);
    }

    /* retrieve available options */
    public function options(array $data, string $requestUrl): ResponseInterface
    {
        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psr->createUri($this->baseUrl.$requestUrl);
        $request = $this->psr->createRequest('OPTIONS', $uri, $headers);

        return $this->service->sendRequest($request);
    }

    /* retrieve data */
    public function get(array $data, string $requestUrl): ResponseInterface
    {
        $requestUrl = sprintf("%s?%s", $requestUrl, http_build_query($data));

        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psr->createUri($this->baseUrl.$requestUrl);
        $request = $this->psr->createRequest('GET', $uri, $headers);

        return $this->service->sendRequest($request);
    }

    /* create new item */
    public function post(array $data, string $requestUrl): ResponseInterface
    {
        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psr->createUri($this->baseUrl.$requestUrl);
        $request = $this->psr->createRequest('POST', $uri, $headers, $body);

        return $this->service->sendRequest($request);
    }

    /* update an item */
    public function put(array $data, string $requestUrl): ResponseInterface
    {
        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psr->createUri($this->baseUrl.$requestUrl);
        $request = $this->psr->createRequest('PUT', $uri, $headers, $body);

        return $this->service->sendRequest($request);
    }

    /* update item's attributes  */
    public function patch(array $data, string $requestUrl): ResponseInterface
    {
        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psr->createUri($this->baseUrl.$requestUrl);
        $request = $this->psr->createRequest('PATCH', $uri, $headers, $body);

        return $this->service->sendRequest($request);
    }

    /* delete an item */
    public function delete(array $data, string $requestUrl): ResponseInterface
    {
        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psr->createUri($this->baseUrl.$requestUrl);
        $request = $this->psr->createRequest('DELETE', $uri, $headers);

        return $this->service->sendRequest($request);
    }

}