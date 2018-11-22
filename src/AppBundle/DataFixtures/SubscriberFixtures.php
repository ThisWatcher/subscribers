<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SubscriberFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 subscribers! Bam!
        for ($i = 0; $i < 20; $i++) {
            $subscriber = new Entity\Subscriber();
            $subscriber->setEmail('test' . $i .'@test.com');
            $subscriber->setName('test');
            $subscriber->setState(Entity\Subscriber::$statusList[rand ( 0, count(Entity\Subscriber::$statusList) - 1 )]);
            $manager->persist($subscriber);

            $this->addRandomFieldToSubscriber($subscriber, $manager);
        }

        $manager->flush();
    }

    function addRandomFieldToSubscriber(Entity\Subscriber $subscriber, ObjectManager $manager)
    {
        $field = new Entity\Field($subscriber);

        $type = Entity\Field::$typeList[rand(0, count(Entity\Field::$typeList) - 1)];
        $field->setSubscriber($subscriber);
        $field->setTitle('testTitle');

        switch ($type) {
            case Entity\Field::TYPE_DATE:
                $data = date("Y-m-d H:i:s");
                $field->setValue($data);
                break;
            case Entity\Field::TYPE_NUMBER:
                $field->setValue(5);
                break;
            case Entity\Field::TYPE_STRING:
                $field->setValue('TestValue');
                break;
            case Entity\Field::TYPE_BOOLEAN:
                $field->setValue(1);
                break;
        }

        $manager->persist($field);

        return $field;
    }
}