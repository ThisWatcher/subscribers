<?php

namespace AppBundle\Controller;

use Composer\Json\JsonValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity;
use AppBundle\Form;
use AppBundle\Exception;
use AppBundle\Exception\BadRequestException;
use AppBundle\Exception\NotFoundException;

class SubscriberController extends Controller
{
    public function testAction(Request $request)
    {
        throw new BadRequestException(['1',2]);
        throw new NotFoundException('Order you are looking for cannot be found.');
        $request = new Request();
        $request->setMethod($request::METHOD_POST);

        $request->request->set('email', 'mdsada@sadam.com');
        //$request->request->set('name', 'martin');
        $data = ['xdd' => 'jonas', 'dxa' => '1998'];
//        $data['title'] = 'john';
//        $data['value'] = '1999-09-09';
        $request->request->set('fields', $data);

        $response = $this->forward('AppBundle\Controller\SubscriberController::putAction', array(
            'request' => $request,
            'id' => 2,
        ));
        return $response;
        die();
    }

    public function postAction(Request $request)
    {
        $subscriber = new Entity\Subscriber();

        $parameters = $request->request->all();

        $this->processForm($subscriber, $parameters, 'POST');

        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'message' => 'user succesfully added'
        ]);

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    public function getAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Entity\Subscriber::class);
        $subscriber = $repository->find($id);

        if (!$subscriber) {
            throw new NotFoundException('subscriber ' . $id .' not found');
        }

        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'data' => $subscriber
        ], 'json');

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    public function putAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Entity\Subscriber::class);
        $subscriber = $repository->find($id);

        if (!$subscriber) {
            throw new NotFoundException('subscriber ' . $id .' not found');
        }

        $this->processForm($subscriber, $request->request->all(), 'PUT');

        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'message' => 'user updated succesfully'
        ]);
        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    public function deleteAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Entity\Subscriber::class);
        $subscriber = $repository->find($id);

        if (!$subscriber) {
            throw new NotFoundException('subscriber ' . $id .' not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($subscriber);
        $em->flush();

        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'message' => 'user deleted succesfully'
        ]);

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    private function processForm(Entity\Subscriber $subscriber, array $parameters, $method = 'PUT')
    {
        if (isset($parameters['fields'])) {
            $fields = [];
            foreach ($parameters['fields'] as $key => $value) {
                if (is_string($key) & is_string($value)) {
                    $field['title'] = $key;
                    $field['value'] = $value;
                    $fields[] = $field;
                }
            }
            unset($parameters['fields']);
            $parameters['fields'] = $fields;
        }


        $form = $this->createForm(Form\SubscriberType::class, $subscriber, ['method' => $method]);

        $form->submit($parameters, 'PUT' !== $method);

        if ($form->isValid()) {
            $subscriber = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($subscriber);
            $em->flush();
        }

        throw new BadRequestException([$form->getErrors(true, true)]);
    }

}
