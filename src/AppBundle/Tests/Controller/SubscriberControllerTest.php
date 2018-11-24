<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\ApiTestCase;

class SubscriberControllerTest extends ApiTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->createSubscriber();
    }

    public function testPOST()
    {
        $data = array(
            'email' => 'foobar@foobar.com',
            'name' => 'test',
            'state' => 'state',
            'fields' => [
                'test' => 'test'
            ]
        );

        $response = $this->client->post('app_test.php/subscriber', [
            'body' => json_encode($data)
        ]);
        $finishedData = json_decode($response->getBody(true), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('status', $finishedData);
        $this->assertArrayHasKey('data', $finishedData);
        $this->assertEquals('foobar@foobar.com', $finishedData['data']['email']);
        $this->assertEquals('test', $finishedData['data']['name']);
        $this->assertEquals('test', $finishedData['data']['fields']['test']);
    }

    public function testGET()
    {
        $response = $this->client->get('app_test.php/subscriber/test@test.com');

        $this->assertEquals(200, $response->getStatusCode());
        $finishedData = json_decode($response->getBody(true), true);
        $this->assertEquals(array(
            'email',
            'name',
            'state',
        ), array_keys($finishedData['data']));
    }

    public function testPUT()
    {
        $data = array(
            'name' => 'test2',
            'fields' => [
                'test' => 'test2'
            ]
        );

        $response = $this->client->put('app_test.php/subscriber/test@test.com', [
            'body' => json_encode($data)
        ]);
        $finishedData = json_decode($response->getBody(true), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test2', $finishedData['data']['name']);
        $this->assertEquals('test2', $finishedData['data']['fields']['test']);
    }

    public function testDELETE()
    {
        $response = $this->client->delete('app_test.php/subscriber/test@test.com');
        $this->assertEquals(200, $response->getStatusCode());
    }
}
