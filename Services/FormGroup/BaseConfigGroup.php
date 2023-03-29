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

    public static function getLabel(): string
    {
        return StringUtil::getLabelFromClass(get_called_class());
    }

    public static function getAuthorSecurityLevels(): ?string
    {
        return null;
    }

    public static function getViewSecurityLevels(): ?string
    {
        return null;
    }
    public static function getRouteName(): ?string
    {
        return 'xiidea_easy_config_form';
    }

    public function getName(): ?string
    {
        return 'name';
    }
}
