<?php

namespace AppBundle\Controller;

use Composer\Json\JsonValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity;
use AppBundle\Form;
use AppBundle\Exception;

class SubscriberController extends Controller
{
    public function testAction(Request $request)
    {
        $request = new Request();
        $request->setMethod($request::METHOD_DELETE);
        $request->request->set('id', '85');
        $request->request->set('email', 'mdsada@sadam.com');
        //$request->request->set('name', 'martin');
        $data['title'] = 'john';
        $data['value'] = '1999-09-09';
        $request->request->set('fields', [$data]);

        $response = $this->forward('AppBundle\Controller\SubscriberController::deleteAction', array(
            'request' => $request,
            'id' => 1,
        ));
        return $response;
        die();
    }

    public function postAction(Request $request)
    {
        $subscriber = new Entity\Subscriber();

        $parameters = $request->request->all();

        dump($parameters);
        $persistedType = $this->processForm($subscriber, $parameters, 'POST');
        dump('galsa');
        dump($persistedType);
die('xd');
        return new JsonResponse('ok', JsonResponse::HTTP_OK, [], true);

    }

    public function getAction(Request $request, $id)
    {

        $repository = $this->getDoctrine()->getManager()->getRepository(Entity\Subscriber::class);
        $subscriber = $repository->find($id);

        if (!$subscriber) {
            return new JsonResponse(['error' => 'subscriber ' . $id .' not found'], JsonResponse::HTTP_NOT_FOUND, []);
        }

        $serializer = $this->container->get('serializer');
        $subscriberSerialized = $serializer->serialize($subscriber, 'json');

        return new JsonResponse($subscriberSerialized, JsonResponse::HTTP_OK, [], true);
    }

    public function putAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Entity\Subscriber::class);
        $subscriber = $repository->find($id);

        if (!$subscriber) {
            return new JsonResponse(['error' => 'subscriber ' . $id .' not found'], JsonResponse::HTTP_NOT_FOUND, []);
        }

        $this->processForm($subscriber, $request->request->all(), 'PUT');

        return new JsonResponse(['user updated succesfully'], JsonResponse::HTTP_OK, [], true);
    }

    public function deleteAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Entity\Subscriber::class);
        $subscriber = $repository->find($id);

        if (!$subscriber) {
            return new JsonResponse(['error' => 'subscriber ' . $id .' not found'], JsonResponse::HTTP_NOT_FOUND, []);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($subscriber);
        $em->flush();

        return new JsonResponse(['user updated succesfully'], JsonResponse::HTTP_OK, [], true);
    }

    private function processForm(Entity\Subscriber $subscriber, array $parameters, $method = 'PUT') {

        $form = $this->createForm(Form\SubscriberType::class, $subscriber, ['method' => $method]);

        $form->submit($parameters, 'PUT' !== $method);

        if ($form->isValid()) {
            $subscriber = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($subscriber);
            $em->flush();
        }

        return (string) $form->getErrors(true, true);
    }
}
