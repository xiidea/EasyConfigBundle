<?php

namespace Xiidea\EasyConfigBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Xiidea\EasyConfigBundle\Services\Manager\ConfigManager;

class ConfigApiController
{
    private ConfigManager $manager;

    public function __construct(ConfigManager $configManager)
    {
        $this->manager = $configManager;
    }

    /**
     * @param string $key
     * @return JsonResponse
     */
    public function getByGroup(string $key): JsonResponse
    {
        return new JsonResponse($this->manager->getConfigurationsByGroup($key));
    }

    /**
     * @param string $key
     * @return JsonResponse
     */
    public function getByKey(string $key): JsonResponse
    {
        return new JsonResponse($this->manager->getConfigurationValueByKey($key));
    }
}
