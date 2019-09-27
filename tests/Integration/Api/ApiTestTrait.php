<?php

namespace tests\Integration\Api;

use PHPUnit_Framework_Assert as PHPUnit;


trait ApiTestTrait
{
    public function assertApiResponse(Array $actualData)
    {
        $this->assertApiSuccess();

        $response = json_decode($this->response->getContent(), true);
        $responseData = $response['data'];

        $this->assertNotEmpty($responseData['id']);
        $this->assertModelData($actualData, $responseData);
    }

    public function assertApiSuccess()
    {
        $this->assertResponseOk();
        $this->seeJson(['success' => true]);
    }

    public function assertModelData(Array $actualData, Array $expectedData)
    {
        foreach ($actualData as $key => $value) {
            $this->assertEquals($actualData[$key], $expectedData[$key]);
        }
    }
    /**
     * Assert that the client response has an OK status code.
     *
     * @return $this
     */
    public function assertResponseOk()
    {
        $actual = $this->response->getStatusCode();
        $response = $this->response->content();

        PHPUnit::assertTrue($this->response->isOk(), "Expected status code 200, got {$actual}. $response");

        return $this;
    }
}
