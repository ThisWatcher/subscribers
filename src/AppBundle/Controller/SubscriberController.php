<?php

namespace AppBundle\Controller;

use Composer\Json\JsonValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity;
use AppBundle\Form;
use AppBundle\Exception;
use AppBundle\Exception\BadRequestException;
use AppBundle\Exception\NotFoundException;
use Doctrine\Common\Collections\Criteria;

class SubscriberController extends Controller
{
    public function testAction(Request $request)
    {
//        $client = new \GuzzleHttp\Client([
//            'base_uri' => 'http://myproject.local',
//            'http_errors' => false,
//        ]);
//        $response = $client->get('/subscriber/2');
//        dump($response);
//        die();
//
//        return new Response('Lets do this!');
//
//        throw new BadRequestException(['1',2]);
//        throw new NotFoundException('Order you are looking for cannot be found.');
        $request = new Request();
        $request->setMethod($request::METHOD_POST);

        $request->request->set('email', 'mdsada@sadam.com');
        $request->request->set('name', 'martin');
        $data = ['name_7' => 'tom', 'date_8' => '2225'];

        $request->request->set('fields', $data);

        $response = $this->forward('AppBundle\Controller\SubscriberController::putAction', array(
            'request' => $request,
            'id' => 2,
        ));
        return $response;
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
                if (is_string($key) && is_string($value)) {
                    $field['title'] = $key;
                    $field['value'] = $value;
                    $fields[] = $field;
                }
            }
            unset($parameters['fields']);
        }

        $form = $this->createForm(Form\SubscriberType::class, $subscriber, ['method' => $method]);
        $form->submit($parameters, 'PUT' !== $method);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($fields as $fieldData) {   //check if each additional field exists or need to create a new one
                $criteria = Criteria::create()->where(Criteria::expr()->eq("title", $fieldData['title']));
                if ($subscriber->getFields()->matching($criteria)->isEmpty()) {      //doesn't exists so we create a new one
                    $fieldEntity = new Entity\Field();
                    $fieldEntity->setSubscriber($subscriber);
                } else {
                    $fieldEntity = $subscriber->getFields()->matching($criteria)[0];
                }
                $fieldForm = $this->createForm(Form\FieldType::class, $fieldEntity, ['method' => $method]);
                $fieldForm->submit($fieldData, 'PUT' !== $method);

                if ($fieldForm->isValid()) {
                    $fieldEntity = $fieldForm->getData();
                    $em->persist($fieldEntity);
                } else {
                    throw new BadRequestException($this->getErrorMessages($fieldForm));
                }
            }
            $em->flush();
        } else {
            throw new BadRequestException($this->getErrorMessages($form));
        }
    }


    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}
