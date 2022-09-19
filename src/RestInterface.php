<?php
namespace Lib;

use Psr\Http\Message\ResponseInterface;

interface RestInterface
{


    public function setBaseUrl($baseUrl);

    public function setAuth(array $conf);

    public function options(array $data, string $requestUrl):ResponseInterface;

    public function head(array $data, string $requestUrl):ResponseInterface;

    /* retrieve data */
    public function get( array $data, string $requestUrl):ResponseInterface;
    /* create new item */
    public function post(array $data, string $requestUrl):ResponseInterface;

    /* update an item */
    public function put(array $data, string $requestUrl):ResponseInterface;

    /* update item's attributes  */
    public function patch(array $data, string $requestUrl):ResponseInterface;

    /* delete an item */
    public function delete(array $data, string $requestUrl):ResponseInterface;

}