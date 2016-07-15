<?php

namespace Ingresse\Behat\ApiExtension\Context;

use GuzzleHttp\Psr7\Request;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Exception\RequestException;

trait RequestContextTrait
{
    /**
     * @param string $url    relative url
     *
     * @When I make request to :arg1
     */
    public function iMakeRequest($uri)
    {
        $uri           = $this->prepareUrl($uri);
        $this->request = new Request('GET', $uri, $this->headers);
        $this->sendRequest();
    }

    /**
     * @param string       $method request method
     * @param string       $url    relative url
     * @param PyStringNode $post   request body
     *
     * @When /^I make "([A-Z]+)" request to "([^"]+)" with json data:$/
     */
    public function iMakeRequestWithJson($method, $url, PyStringNode $json)
    {
        $url           = $this->prepareUrl($url);
        $string        = $this->replacePlaceHolder(trim($string));
        $this->request = new Request($method, $url, $this->headers, $string);
        $this->sendRequest();
    }

    /**
     * @param string    $method request method
     * @param string    $url    relative url
     * @param TableNode $post   table of post values
     *
     * @When /^I make "([A-Z]+)" request to "([^"]+)" with form data:$/
     */
    public function iMakeRequestWithForm($method, $url, TableNode $post)
    {
        $url        = $this->prepareUrl($url);
        $formHeader = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $headers    = array_merge($this->headers, $formHeader);
        $fields     = '';

        foreach ($post as $item) {
            $fields .= sprintf('%s=%s', $item['field'], $item['value']);
        }

        $this->request = new Request($method, $url, $headers, $fields);
        $this->sendRequest();
    }

    /**
     * @param string $name  header name
     * @param string $value header value
     *
     * @Given /^I set header "([^"]*)" with value "([^"]*)"$/
     */
    public function iSetHeaderWithValue($name, $value)
    {
        $this->addHeader($name, $value);
    }

    private function sendRequest()
    {
        try {
            $this->response = $this->client->send($this->request);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();

            if (null === $this->response) {
                throw $e;
            }
        }
    }

    /**
     * @param  string $url
     * @return string
     */
    private function prepareUrl($url)
    {
        foreach ($this->placeHolders as $key => $val) {
            $url = str_replace($key, $val, $url);
        }

        return ltrim($url, '/');
    }

    /**
     * @param string $key   token name
     * @param string $value replace value
     */
    public function setPlaceHolder($key, $value)
    {
        $this->placeHolders[$key] = $value;
    }
}