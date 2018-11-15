<?php

namespace AppBundle\DataFixtures;

use Appbundle\Entity\Subscriber;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $subscriber = new Subscriber();
            $subscriber->setEmail('product '.$i);
            $manager->persist($subscriber);
        }

        $manager->flush();
    }
}