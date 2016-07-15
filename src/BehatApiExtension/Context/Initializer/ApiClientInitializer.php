<?php

namespace Ingresse\Behat\ApiExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use GuzzleHttp\ClientInterface;

class ApiClientInitializer implements ContextInitializer
{
    /**
     * @var GuzzleHttp\ClientInterface
     */
    private $client;

    /**
     * @param GuzzleHttp\ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Initializes provided context.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        $context->setClient($this->client);
    }
}
