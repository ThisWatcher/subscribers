<?php

namespace AppBundle\DataFixtures;

use Appbundle\Entity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use \Datetime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 subscribers! Bam!
        for ($i = 0; $i < 20; $i++) {
            $subscriber = new Entity\Subscriber();
            $subscriber->setEmail(substr(md5(microtime()),rand(0,26),5) . '@' . substr(md5(microtime()),rand(0,26),5) . '.' . substr(md5(microtime()),rand(0,26),5));
            $subscriber->setName(substr(md5(microtime()),rand(0,26),5));
            $subscriber->setState(Entity\Subscriber::$statusList[rand ( 0, count(Entity\Subscriber::$statusList) - 1 )]);
            $manager->persist($subscriber);
        }

        $manager->flush();
    }
}