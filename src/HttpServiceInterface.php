<?php

namespace Lib;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;

interface HttpServiceInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface;
}