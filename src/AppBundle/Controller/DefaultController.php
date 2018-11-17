<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity;
use Doctrine\Common\Persistence\ObjectManager;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, ObjectManager $manager)
    {
        //$manager = new ObjectManager::getInstance();
        $subscriber = new Entity\Subscriber();
        $subscriber->setEmail('xss');//substr(md5(microtime()),rand(0,26),5) . '@' . substr(md5(microtime()),rand(0,26),5) . '.' . substr(md5(microtime()),rand(0,26),5));
        $subscriber->setName(substr(md5(microtime()),rand(0,26),5));
        $subscriber->setState(Entity\Subscriber::$statusList[rand ( 0, count(Entity\Subscriber::$statusList) - 1 )]);
        dump($subscriber);
        dump($manager->persist($subscriber));

        dump($manager->flush());
        // replace this example code with whatever you need
        dump(Entity\Subscriber::$statusList[rand ( 0, count(Entity\Subscriber::$statusList) )]);
        die();
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
