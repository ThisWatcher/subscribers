<?php

namespace AppBundle\DataFixtures;

use Appbundle\Entity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SubscriberFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 subscribers! Bam!
        for ($i = 0; $i < 20; $i++) {
            $subscriber = new Entity\Subscriber();
            $subscriber->setEmail(substr(md5(microtime()),rand(0,26),5) . '@' . substr(md5(microtime()),rand(0,26),5) . '.' . substr(md5(microtime()),rand(0,26),5));
            $subscriber->setName(substr(md5(microtime()),rand(0,26),5));
            $subscriber->setState(Entity\Subscriber::$statusList[rand ( 0, count(Entity\Subscriber::$statusList) - 1 )]);
            $subscriber = $this->addRandomFieldToSubscriber($subscriber, $manager);
            $manager->persist($subscriber);
        }

        $manager->flush();
    }

    function addRandomFieldToSubscriber(Entity\Subscriber $subscriber, ObjectManager $manager)
    {
        $field = new Entity\Field();

        $type = Entity\Field::$typeList[rand(0, count(Entity\Field::$typeList) - 1)];
        $field->setType($type);
        $field->setTitle(substr(md5(microtime()), rand(0, 26), 15));

        switch ($type) {
            case Entity\Field::TYPE_DATE:
                $timestamp = mt_rand(1, time());
                $randomDate = date("Y-m-d H:i:s", $timestamp);
                $field->setValue($randomDate);
                break;
            case Entity\Field::TYPE_NUMBER:
                $randomNumber = rand();
                $field->setValue($randomNumber);
                break;
            case Entity\Field::TYPE_STRING:
                $randomString = substr(md5(microtime()), rand(0, 26), 15);
                $field->setValue($randomString);
                break;
            case Entity\Field::TYPE_BOOLEAN:
                $field->setValue(rand(0, 1));
                break;
        }

        $manager->persist($field);

        return $subscriber->addField($field);
    }
}