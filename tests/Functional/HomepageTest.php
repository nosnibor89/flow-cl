<?php

namespace Tests\Functional;

class HomepageTest extends BaseTestCase
{
    /**
     * Test that the index route returns a rendered response containing the text 'SlimFramework' but not a greeting
     */
    public function testGetBaseUrl()
    {
        $response = $this->runApp('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['API' => 'Flow-Payment-Gateway']),
            (string)$response->getBody()
        );
    }


    /**
     * Test that the index route won't accept a post request
     */
    public function testPostBaseUrlNotAllowed()
    {
        $response = $this->runApp('POST', '/', ['test']);

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'sorry buddy you are busted!']),
            (string)$response->getBody()
        );
    }

    /**
     * Test that the index route won't accept a post request
     */
    public function testNotFoundUrl()
    {
        $response = $this->runApp('GET', '/someCrazyUrl');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Hey hey! There is nothing to see here!']),
            (string)$response->getBody()
        );
    }
}