<?php

namespace Ingresse\Behat\ApiExtension\Context;

use Ingresse\Behat\ApiExtension\Context\RequestContextTrait;
use Ingresse\Behat\ApiExtension\Context\AssertContextTrait;
use GuzzleHttp\ClientInterface;

class ApiContext
{
    /**
     * @var array
     */
    private $headers = [];
    /**
     * @var array
     */
    private $placeHolders = [];
    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    private $request;
    /**
     * @var \Psr\Http\Message\ResponseInterface|ResponseInterface
     */
    private $response;

    use RequestContextTrait;

    use AssertContextTrait;

    /**
     * {@inheritdoc}
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }
}
