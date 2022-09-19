<?php

namespace Lib;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

class RestClient
{

    private string $baseUrl;

    private string $authMethod;
    private string $authUser;
    private string $authPassword;
    private string $authUrl;
    private string $token;

    protected Config $config;
    protected HttpServiceInterface $service;
    protected Psr17Factory $psrfactory;

    public function __construct(HttpServiceInterface $httpService)
    {
        $this->service = $httpService;
        $this->config = new Config();
        $this->psrfactory = new Psr17Factory();
        $this->loadConfig();
    }

    protected function loadConfig()
    {
        $this->setBaseUrl($this->config->get('resttest.baseUrl'));
        $this->setAuth($this->config->getSection('resttestauth'));
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

    protected function setHeaders($headers = [])
    {

        // prepare headers

        // prepare headers auth method

        return $headers;
    }
    /* REST methods */

    /* retrieve data */
    public function get(array $data, string $requestUrl): ResponseInterface
    {
        $requestUrl = sprintf("%s?%s", $requestUrl, http_build_query($data));
        $headers = $this->setHeaders();
        $uri = $this->psrfactory->createUri($this->baseUrl . $requestUrl);
        $request = $this->psrfactory->createRequest('GET', $uri, $headers);

        return $this->service->sendRequest($request);
    }

    /* create new item */
    public function post(array $data, string $requestUrl): ResponseInterface
    {
        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psrfactory->createUri($this->baseUrl . $requestUrl);
        $request = $this->psrfactory->createRequest('POST', $uri, $headers, $body);

        return $this->service->sendRequest($request);
    }

    /* update an item */
    public function put(array $data, string $requestUrl): ResponseInterface
    {
        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psrfactory->createUri($this->baseUrl . $requestUrl);
        $request = $this->psrfactory->createRequest('PUT', $uri, $headers, $body);

        return $this->service->sendRequest($request);
    }

    /* update item's attributes  */
    public function patch(array $data, string $requestUrl): ResponseInterface
    {
        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psrfactory->createUri($this->baseUrl . $requestUrl);
        $request = $this->psrfactory->createRequest('PATCH', $uri, $headers, $body);

        return $this->service->sendRequest($request);
    }

    /* delete an item */
    public function delete(array $data, string $requestUrl): ResponseInterface
    {
        $body = $data;
        $headers = $this->setHeaders();
        $uri = $this->psrfactory->createUri($this->baseUrl . $requestUrl);
        $request = $this->psrfactory->createRequest('DELETE', $uri, $headers);

        return $this->service->sendRequest($request);
    }

    /* retrieve headers information */
    public function head(string $requestUrl): ResponseInterface
    {

        $headers = $this->setHeaders();
        $uri = $this->psrfactory->createUri($this->baseUrl . $requestUrl);
        $request = $this->psrfactory->createRequest('HEAD', $uri, $headers);

        return $this->service->sendRequest($request);
    }

    /* retrieve available options */
    public function options(array $data, string $requestUrl): ResponseInterface
    {

        $headers = $this->setHeaders();
        $uri = $this->psrfactory->createUri($this->baseUrl . $requestUrl);
        $request = $this->psrfactory->createRequest('OPTIONS', $uri, $headers);

        return $this->service->sendRequest($request);
    }

}