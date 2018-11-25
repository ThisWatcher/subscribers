<?php

namespace AppBundle\Tests;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use AppBundle\Entity;

class ApiTestCase extends KernelTestCase
{
    private static $staticClient;

    /**
     * @var Client
     */
    protected $client;

    public static function setUpBeforeClass()
    {
        $baseUrl = getenv('TEST_BASE_URL');

        self::$staticClient = new Client([
            'base_uri' => $baseUrl,
            'http_errors' => false,
        ]);

        self::bootKernel();
    }
    protected function setUp()
    {
        $this->client = self::$staticClient;

        $this->purgeDatabase();
    }

    /**
     * Clean up Kernel usage in this test.
     */
    protected function tearDown()
    {

    }

    protected function getService($id)
    {
        return self::$kernel->getContainer()
            ->get($id);
    }

    private function purgeDatabase()
    {
        $purger = new ORMPurger($this->getService('doctrine')->getManager());
        $purger->purge();
    }

    protected function printDebug($string)
    {
        echo $string."\n";
    }

    protected function createSubscriber()
    {
        $user = new Entity\Subscriber();
        $user->setEmail('test@test.com');
        $user->setName('test');

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
        return $user;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getService('doctrine.orm.entity_manager');
    }
}