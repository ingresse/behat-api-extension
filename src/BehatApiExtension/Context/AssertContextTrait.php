<?php

namespace Ingresse\Behat\ApiExtension\Context;

use PHPUnit_Framework_Assert as Assert;

trait AssertContextTrait
{
    /**
     * @Then response status code is :code
     */
    public function responseStatusCode($code)
    {
        Assert::assertSame((int)$code, $this->response->getStatusCode());
    }

    /**
     * @Then response :field is :value
     */
    public function responseFieldIs($field, $value)
    {
        Assert::assertEquals($value, $this->getResponseField($field));
    }

    /**
     * @Then response data :field is :value
     */
    public function responseDataFieldIs($field, $value)
    {
        Assert::assertEquals($value, $this->getResponseDataField($field));
    }

    /**
     * @Then the response :field contains :value
     */
    public function responseFieldContains($field, $value)
    {
        Assertions::assertRegExp(
            '/' . preg_quote($value) . '/i',
            $this->getResponseField($field)
        );
    }

    /**
     * @Then the response data :field contains :value
     */
    public function responseDataFieldContains($field, $value)
    {
        Assertions::assertRegExp(
            '/' . preg_quote($value) . '/i',
            $this->getResponseDataField($field)
        );
    }

    /**
     * @Then response :field is not empty
     */
    public function responseFieldIsNotEmpty($field)
    {
        Assertions::assertNotEmpty($this->getResponseField($field));
    }

    /**
     * @Then response data :field is not empty
     */
    public function responseDataFieldIsNotEmpty($field)
    {
        Assertions::assertNotEmpty($this->getResponseDataField($field));
    }

    /**
     * @Then response :field is :type equals to :value
     */
    public function responseFieldEquals($field, $type, $value)
    {
        Assert::assertEquals($value, $this->getResponseField($field));
        $this->assertTypeValue($this->getResponseField($field), $type, $value);
    }

    /**
     * @Then response data :field is :type equals to :value
     */
    public function responseDataFieldEquals($field, $type, $value)
    {
        Assert::assertEquals($value, $this->getResponseDataField($field));
        $this->assertTypeValue($this->getResponseDataField($field), $type, $value);
    }

    /**
     * @Then response :field is true
     */
    public function responseFieldIsTrue($field)
    {
        Assert::assertTrue($this->getResponseField($field));
    }

    /**
     * @Then response :field is false
     */
    public function responseFieldIsFalse($field)
    {
        Assert::assertFalse($this->getResponseField($field));
    }

    /**
     * @Then response :field is greater than :value
     */
    public function responseFieldValueIsGraterThan($field, $value)
    {
        Assert::assertGreaterThan($value, $this->getResponseField($field));
    }

    /**
     * @Then response :field is less than :value
     */
    public function responseFieldValueIsLessThan($field, $value)
    {
        Assert::assertLessThan($value, $this->getResponseField($field));
    }

    /**
     * @Then response :field exists
     */
    public function responseFieldExists($field)
    {
        Assert::assertArrayHasKey($field, $this->getResponseField($field));
    }

    /**
     * @return array
     */
    private function getResponse()
    {
        return json_decode((string)$this->response->getBody(), true);
    }

    /**
     * @return array
     */
    private function getResponseData()
    {
        return $this->getResponse()['data'];
    }

    /**
     * @param  string $field
     * @return mixed
     */
    private function getResponseField($field)
    {
        return $this->getField($this->getResponse(), $field);
    }

    /**
     * @param  string $field
     * @return mixed
     */
    private function getResponseDataField($field)
    {
        return $this->getField($this->getResponseData(), $field);
    }

    /**
     * @param  mixed $data
     * @param  string $fields
     * @return mixed
     */
    private function getField($data, $fields)
    {
        $fieldList = explode('.', $fields);

        foreach ($fieldList as $field) {
            $data = $data[$field];
        }

        return $data;
    }

    /**
     * @param  string $field
     * @param  string $type
     * @param  string $value
     * @return boolean
     */
    private function assertTypeValue($field, $type, $value)
    {
        $realValue = null;
        switch ($type) {
            case "boolean":
                $realValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;
            case "integer":
                $realValue = filter_var($value, FILTER_VALIDATE_INT);
                break;
            case "string":
            default:
                $realValue = (string) $value;
                break;
        }
        Assert::assertEquals($realValue, strval($field));
    }
}
