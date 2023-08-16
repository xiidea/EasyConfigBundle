<?php

namespace Xiidea\EasyConfigBundle\Services\FormGroup;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Xiidea\EasyConfigBundle\Utility\StringUtil;

abstract class BaseConfigGroup implements ConfigGroupInterface
{
    protected static TokenStorageInterface $tokenStorage;
    protected static UrlGeneratorInterface $urlGenerator;

    public function __construct(TokenStorageInterface $tokenStorage, UrlGeneratorInterface $urlGenerator)
    {
        self::$tokenStorage = $tokenStorage;
        self::$urlGenerator = $urlGenerator;
    }

    public function getLabel(): string
    {
        return StringUtil::getLabelFromClass(get_called_class());
    }

    public function getNameSpace(): string
    {
        return 'app';
    }

    public function getAuthorSecurityLevels(): ?string
    {
        return null;
    }

    public function getViewSecurityLevels(): ?string
    {
        return null;
    }

    public function getRouteName(): ?string
    {
        return 'xiidea_easy_config_form';
    }

    public function getName(): ?string
    {
        return 'name';
    }

    public function getGroupKey(): ?string
    {
        return 'miscellaneous';
    }

    public function getGroupLabel(): ?string
    {
        return StringUtil::humanize(static::getGroupKey());
    }

    public function getGroupIcon(): ?string
    {
        return null;
    }

    public function getPriority(): int
    {
        return 0;
    }
}
