<?php

namespace Xiidea\EasyConfigBundle\Services\FormGroup;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

interface ConfigGroupInterface
{
    /**
     * Build and return form to handle request binding for policy.
     *
     * @param null $data
     */
    public function getForm(FormFactory $formFactory, $data = null, array $options = []): FormInterface;

    /**
     * @return string User friendly string to describe this policies group
     */
    public function getLabel(): string;

    /**
     * @return string Base key of policy group
     */
    public function getNameSpace(): string;

    /**
     * @return string|null role for allowing editing policy
     */
    public function getAuthorSecurityLevels(): ?string;

    /**
     * @return string|null role for viewing policy
     */
    public function getViewSecurityLevels(): ?string;
}
