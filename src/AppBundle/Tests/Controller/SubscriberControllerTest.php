<?php

namespace AppBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;

class SubscriberControllerTest extends TestCase
{
    public function testPOST()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://myproject.local',
            'http_errors' => false,
        ]);
        $data = array(
            'email' => 'test@test.com',
            'name' => 'test',
            'state' => 'state',
            'fields' => [
                'test' => 'test'
            ]
        );
        $response = $client->post('/subscriber', [
            'body' => json_encode($data)
        ]);
        //var_dump($response->getBody(true));
        $this->assertEquals(200, $response->getStatusCode());
        $finishedData = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('status', $finishedData);
    }
}
