<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity;
use AppBundle\Form;
use AppBundle\Exception\BadRequestException;
use AppBundle\Exception\NotFoundException;
use Doctrine\Common\Collections\Criteria;

class SubscriberController extends Controller
{
    public function postAction(Request $request)
    {
        $subscriber = new Entity\Subscriber();

        $data = json_decode($request->getContent(), true);

        $subscriber = $this->processSubscriber($subscriber, $data, 'POST');

        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'data' => $subscriber
        ], 'json');

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    public function getAction(Request $request, $email)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Entity\Subscriber::class);
        $subscriber = $repository->findOneBy(['email' => $email]);

        if (!$subscriber) {
            throw new NotFoundException('subscriber ' . $email .' not found');
        }

        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'data' => $subscriber
        ], 'json');

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    public function putAction(Request $request, $email)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Entity\Subscriber::class);
        $subscriber = $repository->findOneBy(['email' => $email]);

        if (!$subscriber) {
            throw new NotFoundException('subscriber ' . $email .' not found');
        }

        $data = json_decode($request->getContent(), true);

        $subscriber = $this->processSubscriber($subscriber, $data, 'PUT');

        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'data' => $subscriber
        ], 'json');

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    public function deleteAction(Request $request, $email)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Entity\Subscriber::class);
        $subscriber = $repository->findOneBy(['email' => $email]);

        if (!$subscriber) {
            throw new NotFoundException('subscriber ' . $email .' not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($subscriber);
        $em->flush();

        $serializer = $this->container->get('serializer');
        $data = $serializer->serialize([
            'status' => 'success',
            'code' => JsonResponse::HTTP_OK,
            'message' => 'user ' . $email . ' deleted succesfully'
        ], 'json');

        return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
    }

    private function processSubscriber(Entity\Subscriber $subscriber, array $parameters, $method = 'PUT')
    {
        $isEdit = $method == 'PUT' ? true : false;

        $subscriberForm = $this->createForm(Form\SubscriberType::class, $subscriber, ['method' => $method, 'is_edit' => $isEdit]);
        $subscriberForm->submit($parameters, false);

        if ($subscriberForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $subscriber = $subscriberForm->getData();
            $em->persist($subscriber);
            $em->flush();
            if(!empty($parameters['fields'])) {
                foreach ($parameters['fields'] as $key => $value) {//check if each additional field exists or need to create a new one
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
            }
            $em->refresh($subscriber);

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
