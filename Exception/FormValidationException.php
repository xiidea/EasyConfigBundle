<?php

namespace Xiidea\EasyConfigBundle\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FormValidationException extends BadRequestHttpException
{
    /**
     * @var FormInterface
     */
    private FormInterface $form;

    public function __construct(FormInterface $form)
    {
        parent::__construct();
        $this->form = $form;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }
}
