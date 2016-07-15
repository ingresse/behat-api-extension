<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Ingresse\Behat\ApiExtension\Context\ApiContext as IngresseApiContext;

class FeatureContext extends IngresseApiContext implements Context, SnippetAcceptingContext
{
    /**
     * @Then /^client must be set$/
     */
    public function checkIfClientIsSet()
    {
        PHPUnit_Framework_Assert::assertInstanceOf(
            'GuzzleHttp\Client',
            $this->client
        );
    }
}
