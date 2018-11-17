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
        $repo =$this->getDoctrine()->getManager()->getRepository('AppBundle:Subscriber');


// this returns a single item
        $found = $repo->find(259)->getFields()[0];
        dump($found);
        die();
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
