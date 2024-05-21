<?php

namespace Xiidea\EasyConfigBundle\Services\Repository;

interface ConfigRepositoryInterface
{
    public function getConfigurationByUsernameAndGroup(string $username, string $groupKey);

    public function getConfigurationByUsernameAndKey(string $username, string $key);

    public function loadAllByGroup($groupKey, $valueOnly = false, $frontendOnly = false);

    public function getValuesByGroupKey($configurationGroup);

    public function saveMultiple($baseKey, array $values = [], array $types = []);

    public function save($key, $value, $type = null, bool $locked = false, bool $force = false, bool $flush = true);

    public function removeByKey($key);

    public function getConfigurationValue($key);

    public function getGlobalAndUserConfigurationByKey(string $username, string $key);
}
