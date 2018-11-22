<?php

namespace AppBundle\Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use AppBundle\Entity;

class ApiTestCase extends KernelTestCase
{
    private static $staticClient;

    /**
     * @var History
     */
    private static $history;
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var FormatterHelper
     */
    private $formatterHelper;

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

    protected function onNotSuccessfulTest($e)
    {
        if (self::$history && $lastResponse = self::$history->getLastResponse()) {
            $this->printDebug('');
            $this->printDebug('<error>Failure!</error> when making the following request:');
            $this->printLastRequestUrl();
            $this->printDebug('');
            $this->debugResponse($lastResponse);
        }
        throw $e;
    }

    protected function printLastRequestUrl()
    {
        $lastRequest = self::$history->getLastRequest();
        if ($lastRequest) {
            $this->printDebug(sprintf('<comment>%s</comment>: <info>%s</info>', $lastRequest->getMethod(), $lastRequest->getUrl()));
        } else {
            $this->printDebug('No request was made.');
        }
    }

    protected function debugResponse(ResponseInterface $response)
    {
        $this->printDebug(AbstractMessage::getStartLineAndHeaders($response));
        $body = (string) $response->getBody();
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