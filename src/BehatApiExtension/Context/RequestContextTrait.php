<?php

namespace Ingresse\Behat\ApiExtension\Context;

use GuzzleHttp\Psr7\Request;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Exception\RequestException;

trait RequestContextTrait
{
    /**
     * @param string $uri    relative uri
     *
     * @When I make request to :uri
     */
    public function iMakeGetRequest($uri)
    {
        $uri           = $this->prepareUri($uri);
        $this->request = new Request('GET', $uri, $this->headers);
        $this->sendRequest();
    }

    /**
     * @When I request :requestInfo
     */
    public function iMakeRequest($request, PyStringNode $json = null)
    {
        $method = 'GET';
        $uri    = '/';

        list($method, $uri) = explode(" ", $request);

        $uri  = $this->prepareUri($uri);

        if ($method != 'GET') {
            $json = $this->prepareJson($json);
        }

        $this->request = new Request($method, $uri, $this->headers, $json);
        $this->sendRequest();
    }

    /**
     * @param string       $method request method
     * @param string       $uri    relative uri
     * @param PyStringNode $json   request body
     *
     * @When I make :method request to :uri with json data:
     */
    public function iMakeRequestWithJson($method, $uri, PyStringNode $json)
    {
        $uri           = $this->prepareUri($uri);
        $jsonHeader    = ['Content-Type' => 'application/json'];
        $json          = $this->prepareJson(trim($json));
        $headers       = array_merge($this->headers, $jsonHeader);
        $this->request = new Request($method, $uri, $headers, $json);
        $this->sendRequest();
    }

    /**
     * @param string    $method request method
     * @param string    $uri    relative uri
     * @param TableNode $post   table of post values
     *
     * @When I make :method request to :uri with form data:
     */
    public function iMakeRequestWithForm($method, $uri, TableNode $post)
    {
        $uri        = $this->prepareUri($uri);
        $formHeader = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $headers    = array_merge($this->headers, $formHeader);
        $fields     = '';

        foreach ($post as $item) {
            $fields .= sprintf('%s=%s', $item['field'], $item['value']);
        }

        $this->request = new Request($method, $uri, $headers, $fields);
        $this->sendRequest();
    }

    /**
     * @param string $name  header name
     * @param string $value header value
     *
     * @Given I set header :name with value :value
     */
    public function iSetHeaderWithValue($name, $value)
    {
        $this->addHeader($name, $value);
    }

    /**
     * @param string $key
     * @param string $resource
     *
     * @Given I have :key of :resource from response
     */
    public function iHaveKeyFromResponse($key, $resource)
    {
        $placeholderKey                      = $resource . ucfirst($key);
        $this->placeholders[$placeholderKey] = $this->getResponseField($key);
    }

    /**
     * @return boolean
     */
    protected function sendRequest()
    {
        try {
            $this->response = $this->client->send($this->request);
            return true;
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
        }

        if (null === $this->response) {
            throw $e;
        }

        return false;
    }

    /**
     * @param  string $uri
     * @return string
     */
    protected function prepareUri($uri)
    {
        return ltrim($this->prepareData($uri), '/');
    }

    /**
     * @param  string $json
     * @return string
     */
    protected function prepareJson($json)
    {
        return ltrim($this->prepareData($json));
    }

    /**
     * @param  string $data
     * @return string
     */
    protected function prepareData($data)
    {
        foreach ($this->placeholders as $key => $val) {
            $data = str_replace(sprintf('{%s}', $key), $val, $data);
        }
        return $data;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setPlaceholder($key, $value)
    {
        $this->placeholders[$key] = $value;
    }

    /**
     * @param string $header
     * @param string $value
     */
    public function addHeader($header, $value)
    {
        $this->headers[$header] = $value;
    }
}
