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

        //$response = $client->get('/subscriber/57');
//        $response = $client->post('/subscriber', [
//            'body' => json_encode($data)
//        ]);
//        dump($response->getBody());
//        die();
//
//        return new Response('Lets do this!');
//
//        throw new BadRequestException(['1',2]);
//        throw new NotFoundException('Order you are looking for cannot be found.');
       // $request = new Request();
        //$request->setMethod($request::METHOD_POST);

        $data = array(
            'email' => 'test1111@waxw.com',
            'name' => 'name',
            'state' => 'state',
            'fields' => [
                '21' => 'aaaaaaaa',
                '224' => 'aaaaaaaaaaaa'
            ]
        );


//
        $request->request->set('data',json_encode($data));

        $response = $this->forward('AppBundle\Controller\SubscriberController::postAction', array(
            'request' => $request,
            'id' => 56,
        ));
        return $response;
    }

    public function postAction(Request $request)
    {
        $subscriber = new Entity\Subscriber();

        $data = json_decode($request->getContent(), true);
        $data = json_decode($request->request->get('data'), true);

        $subscriber = $this->processForm($subscriber, $data, 'POST');

        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'data' => $subscriber
        ], 'json');

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

        $data = json_decode($request->getContent(), true);
        $this->processForm($subscriber, $data, 'PUT');

        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'message' => 'user updated succesfully'
        ], 'json');
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
            $fields = $parameters['fields'];
            unset($parameters['fields']);
        }

        $subscriberForm = $this->createForm(Form\SubscriberType::class, $subscriber, ['method' => $method]);
        $subscriberForm->submit($parameters, 'PUT' !== $method);

        if ($subscriberForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $subscriber = $subscriberForm->getData();
            $em->persist($subscriber);
            $em->flush();

            foreach ($fields as $key => $value) {//check if each additional field exists or need to create a new one
                if (is_string((string) $key) && is_string((string) $value)) {
                    $criteria = Criteria::create()->where(Criteria::expr()->eq("title", $key));
                    if ($subscriber->getFields()->matching($criteria)->isEmpty()) {      //doesn't exists so we create a new one
                        $field = new Entity\Field($subscriber);
                    } else {
                        $field = $subscriber->getFields()->matching($criteria)[0];
                    }
                    $fieldForm = $this->createForm(Form\FieldType::class, $field, ['method' => $method]);
                    $fieldForm->submit(['title' => $key, 'value' => $value], false);
                    if ($fieldForm->isValid()) {
                        $field = $fieldForm->getData();
                        $em->persist($field);
                        $em->flush();
                    } else {
                        throw new BadRequestException($this->getErrorMessages($fieldForm));
                    }
                }
            }
            return $subscriber;
        } else {
            throw new BadRequestException($this->getErrorMessages($subscriberForm));
        }
    }


    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = [];
        foreach ($form->getErrors(true, false) as $error) {
            $errors[] = $error->current()->getMessage();
        }

        return $errors;
    }
}
