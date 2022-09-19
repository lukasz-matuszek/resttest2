<?php

namespace Lib;

use Psr\Http\Message\ResponseInterface;

interface RestInterface
{

    public function setHeaders();

    public function setHttpService(HttpServiceInterface $httpService);

    public function setBaseUrl(string $baseUrl);

    public function setAuth(array $conf);

    /* retrieve data */
    public function get(array $data, string $requestUrl): ResponseInterface;

    /* create new item */
    public function post(array $data, string $requestUrl): ResponseInterface;

    /* update an item */
    public function put(array $data, string $requestUrl): ResponseInterface;

    /* update item's attributes  */
    public function patch(array $data, string $requestUrl): ResponseInterface;

    /* delete an item */
    public function delete(array $data, string $requestUrl): ResponseInterface;

    /* retrieve availbale options  information */
    public function options( string $requestUrl): ResponseInterface;

     /* retrieve headers information */
    public function head( string $requestUrl): ResponseInterface;

}