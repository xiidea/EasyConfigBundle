<?php

namespace Xiidea\EasyConfigBundle\Exception;

class UnrecognizedEntityException extends \Exception
{
    protected $message = 'Entity must extend Xiidea\\EasyConfigBundle\\Model\\BaseConfiguration';
}
