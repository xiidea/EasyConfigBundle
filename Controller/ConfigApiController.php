<?php

namespace Xiidea\EasyConfigBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Xiidea\EasyConfigBundle\Services\Manager\ConfigManager;

class ConfigApiController
{
    /**
     * @param  string  $key
     * @return JsonResponse
     */
    public function getByGroup(string $key, ConfigManager $manager): JsonResponse
    {
        return new JsonResponse($manager->getConfigurationsByGroup($key));
    }

    /**
     * @param  string  $key
     * @return JsonResponse
     */
    public function getByKey(string $key, ConfigManager $manager): JsonResponse
    {
        return new JsonResponse($manager->getConfigurationValueByKey($key));
    }
}
