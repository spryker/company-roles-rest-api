<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payolution\Business\Api\Adapter\Http;

use Spryker\Zed\Payolution\Business\Exception\ApiHttpRequestException;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Message\RequestInterface as GuzzleRequestInterface;

class Guzzle extends AbstractHttpAdapter
{

    const DEFAULT_TIMEOUT = 45;

    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @param string $gatewayUrl
     * @param string $contentType
     */
    public function __construct($gatewayUrl, $contentType)
    {
        parent::__construct($gatewayUrl, $contentType);

        $this->client = new GuzzleClient([
            'timeout' => self::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @param array|string $data
     *
     * @return GuzzleRequestInterface
     */
    protected function buildRequest($data)
    {
        return $this->client->post(
            $this->gatewayUrl,
            ['Content-Type' => self::$requestContentTypes[$this->contentType]],
            $data
        );
    }

    /**
     * @param GuzzleRequestInterface $request
     * @param string $user
     * @param string $password
     *
     * @return void
     */
    protected function authorizeRequest($request, $user, $password)
    {
        $request->setAuth($user, $password);
    }

    /**
     * @param GuzzleRequestInterface $request
     *
     * @throws ApiHttpRequestException
     *
     * @return string
     */
    protected function send($request)
    {
        try {
            $response = $request->send();
        } catch (RequestException $requestException) {
            throw new ApiHttpRequestException($requestException->getMessage());
        }

        return $response->getbody(true);
    }

}