<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\ApiExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ApiExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->getException() instanceof ApiExceptionInterface) {
            return;
        }

        $response = new JsonResponse($this->buildResponseData($event->getException()));
        $response->setStatusCode($event->getException()->getCode());

        $event->setResponse($response);
    }

    private function buildResponseData(ApiExceptionInterface $exception)
    {
        $messages = json_decode($exception->getMessage());
        if (!is_array($messages)) {
            $messages = $exception->getMessage() ? [$exception->getMessage()] : [];
        }

        return [
            'status' => 'error',
            'code' => $exception->getCode(),
            'messages' => $messages
        ];
    }
}