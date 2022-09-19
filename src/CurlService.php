<?php
namespace Lib;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Factory\HttplugFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CurlService implements HttpServiceInterface
{
    /** @var array */
    protected $authMethod;

    /** @var array */
    private $defaultOptions;

    public function __construct()
    {
        $this->setDefaultOptions();
        $this->psr = new Psr17Factory();
        $this->httplug = new HttplugFactory();
    }

    public function setDefaultOptions()
    {
        $this->defaultOptions = [
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_HEADER' => true,
        ];
    }

    public function sendRequest(RequestInterface $request):ResponseInterface
    {
        return $this->execCurl($request);
    }

    protected function prepareCurlOptions(RequestInterface $request)
    {
        $method = $request->getMethod();
        $body = $request->getBody();

        $setCurlOptions = 'set' . ucfirst($method) . "Options";
        if (! method_exists($this, $setCurlOptions)) {
            return null;
        }
        $curlOptions = array_merge($this->defaultOptions, $this->$setCurlOptions($body));
        $curlOptions ['CURLOPT_URL'] =  (string) $request->getUri();;
        return $curlOptions;
    }

    protected function setGetOptions($data)
    {
        return [];
    }

    protected function setPostOptions($data)
    {
        return [
            'CURLOPT_POST' => 1,
            'CURLOPT_POSTFIELDS' => $data,
        ];
    }

    protected function setPutOptions($data)
    {
        return [
            'CURLOPT_CUSTOMREQUEST' => 'PUT',
            'CURLOPT_POSTFIELDS' => $data,
        ];
    }

    protected function setPatchOptions($data)
    {
        return [
            'CURLOPT_CUSTOMREQUEST' => 'PATCH',
            'CURLOPT_POSTFIELDS' => $data,
        ];
    }

    protected function setDeleteOptions($data)
    {
        return [
            'CURLOPT_CUSTOMREQUEST' => 'DELETE',
        ];
    }

    public function setAuthMethod($authMethod)
    {
        $this->authMethod = $authMethod;

        return $this;
    }

    protected function execCurl(RequestInterface $request ):ResponseInterface
    {

        //-- initialize curl
        $curl = curl_init();

        //-- handle curl initializations errors
        //   ...
        //--

        //-- setup options and exec curl
        $curlOptions = $this->prepareCurlOptions($request);

        foreach ($curlOptions as $curlOption => $optionValue) {
            if (! defined($curlOption)) {
                continue;
            }
            curl_setopt($curl, constant($curlOption), $optionValue);
        }
        $curlResponse = curl_exec($curl);

        //-- handle curl exec errors
        //   ...
        //--
        
        $response = $this->buildResponse($curl, $curlResponse);
        curl_close($curl);


        return $response;

    }

    protected function buildResponse($curl, $curlResponse):ResponseInterface
    {
        
        
        $headers = $this->getHeaders($curl, $curlResponse);
        $statusCode = $headers['status']; unset($headers['status']);
        $reason = $headers['status_text'];unset($headers['status_text']);
        $body = $this->getData($curl, $curlResponse);
        
        $response = $this->httplug->createResponse( $statusCode, $reason, $headers, $body , $version = '1.1');
        
        return $response;
    }

    // AUTH methods : setting curl options
    protected function setBasicAuth($params)
    {
        return [
            'CURLOPT_HTTPAUTH' => CURLAUTH_BASIC,
            'CURLOPT_USERPWD' => "{$params['user']}:{$params['password']}",
        ];
    }

    protected function setJwtAuth($params)
    {
        return [
            'CURLOPT_HTTPHEADER' => [
                'Content-Type: application/json',
                "Authorization: {$params['token']}",
            ],
        ];
    }


        protected function getHeaders($curl, $curlResponse)
    {
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headerData = substr($curlResponse, 0, $headerSize);

        $headers = preg_split("/\r\n|\n|\r/", $headerData);

        if ('HTTP' === substr($headers[0], 0, 4)) {
            [, $parsedHeaders['status'], $parsedHeaders['status_text']] = explode(' ', $headers[0]);
            unset($headers[0]);
        }

        foreach ($headers as $header) {

            if (! preg_match('/^([^:]+):(.*)$/', $header, $output)) {
                continue;
            }

            $parsedHeaders[$output[1]] = $output[2];
        }

        return $parsedHeaders;
    }

    protected function getData($curl, $curlResponse)
    {
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $data = substr($curlResponse, $headerSize);

        return $data;
    }

}