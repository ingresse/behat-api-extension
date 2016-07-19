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
    protected $headers = [];
    /**
     * @var array
     */
    protected $placeholders = [];
    /**
     * @var \Psr\Http\Message\RequestInterface
     */
    protected $request;
    /**
     * @var \Psr\Http\Message\ResponseInterface|ResponseInterface
     */
    protected $response;

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
