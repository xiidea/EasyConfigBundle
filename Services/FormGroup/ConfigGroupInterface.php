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
    public static function getLabel(): string;

    /**
     * @return string Base key of policy group
     */
    public static function getNameSpace(): string;

    /**
     * @return string|null role for allowing editing policy
     */
    public static function getAuthorSecurityLevels(): ?string;

    /**
     * @return string|null role for viewing policy
     */
    public static function getViewSecurityLevels(): ?string;
}
