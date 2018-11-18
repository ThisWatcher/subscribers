<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        dump($request);
        $repo =$this->getDoctrine()->getManager()->getRepository('AppBundle:Subscriber');


// this returns a single item
        $found = $repo->find(1)->getFields()[0];
        dump($found);
        die();
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}
