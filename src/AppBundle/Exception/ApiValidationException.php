<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiValidationException extends \Exception
{
    public function __construct(string $message = null, \Exception $previous = null, int $code = 0, array $headers = array())
    {
        return 'xd';
    }
}