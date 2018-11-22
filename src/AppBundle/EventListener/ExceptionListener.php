<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use AppBundle\Exception\ApiExceptionInterface;
use AppBundle\Exception\BadRequestException;

class ExceptionListener
{
    /** @var ContainerInterface */
    protected $serviceContainer;

    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    public function setServiceContainer($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($this->getServiceContainer()->get("kernel")->getEnvironment() == 'dev') {
            return;
        }

        if ($event->getException() instanceof ApiExceptionInterface) {
            $response = new JsonResponse($this->buildResponseData($event->getException()));
            $response->setStatusCode($event->getException()->getCode());

            $event->setResponse($response);
        } else {
            throw new BadRequestException(['page or method not found']);
        }
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
