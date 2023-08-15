<?php

namespace Xiidea\EasyConfigBundle\Services\Manager;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Xiidea\EasyConfigBundle\Model\BaseConfig;
use Xiidea\EasyConfigBundle\Services\FormGroup\ConfigGroupInterface;
use Xiidea\EasyConfigBundle\Services\Repository\ConfigRepositoryInterface;

class ConfigManager
{
    /**
     * @var ConfigGroupInterface[]
     */
    private array $configurationGroups = [];
    private ConfigRepositoryInterface $repository;
    private FormFactoryInterface $formFactory;
    private TokenStorageInterface $tokenStorage;
    protected $container;
    private AuthorizationCheckerInterface $checker;

    public function __construct(
        ConfigRepositoryInterface $repository,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $checker
    ) {
        $this->repository = $repository;
        $this->formFactory = $formFactory;
        $this->tokenStorage = $tokenStorage;
        $this->checker = $checker;
    }

    public function addConfigGroup($group)
    {
        $this->configurationGroups[$group::getNameSpace()] = $group;
    }

    /**
     * @return ConfigGroupInterface[]
     */
    public function getConfigurationGroups(): array
    {
        $username = $this->getUsername();
        $groups = [];

        foreach ($this->configurationGroups as $key => $group) {
            $groups[str_replace($username.'.', '', $key)] = $group;
        }

        return $groups;
    }

    public function getConfigurationGroupForms(): array
    {
        $return = [];

        foreach ($this->configurationGroups as $groupKey => $policyGroup) {
            $return[] = [
                'key' => $groupKey,
                'label' => $policyGroup::getLabel(),
                'form' => $this->createFormView($policyGroup, $groupKey),
                'isEditable' => $this->checker->isGranted($policyGroup::getAuthorSecurityLevels()),
            ];
        }

        return $return;
    }

    public function getConfigurationsByGroup(string $groupKey): array
    {
        $username = $this->getUsername();
        $configurations = $this->repository->getConfigurationByUsernameAndGroup($username, $groupKey);
        $results = [];

        /**
         * @var BaseConfig $configuration
         */
        foreach ($configurations as $configuration) {
            $key = str_replace($username.'.', '', $configuration->getId());
            $key = str_replace($groupKey.'.', '', $key);

            if (str_contains($configuration->getId(), $username)) {
                $results[$key] = $configuration->getValue();
            } elseif (!array_key_exists($key, $results)) {
                $results[$key] = $configuration->getValue();
            }
        }

        return $results;
    }

    public function getConfigurationValueByKey(string $key): string
    {
        $username = $this->getUsername();
        $configurations = $this->repository->getConfigurationByUsernameAndKey($username, $key);
        $value = '';

        /**
         * @var BaseConfig $configuration
         */
        foreach ($configurations as $configuration) {
            $value = $configuration->getValue();

            if (str_contains($configuration->getId(), $username.$key)) {
                break;
            }
        }

        return $value;
    }

    /**
     * @param $policyGroup
     * @param $groupKey
     */
    protected function createFormView(ConfigGroupInterface $policyGroup, $groupKey): FormView
    {
        return $policyGroup
            ->getForm($this->formFactory, $this->getConfigurationsByGroupKey($groupKey))
            ->createView();
    }

    public function getConfigurationsByGroupKey($group): array
    {
        return $this->repository->getValuesByGroupKey($group);
    }

    public function getConfigurationGroup($key): ConfigGroupInterface
    {
        return $this->configurationGroups[$key];
    }

    private function guessType(FormInterface $item): ?string
    {
        $getNormData = $item->getNormData();

        if (is_string($getNormData)) {
            return null;
        }

        if ($getNormData instanceof \DateTime) {
            return Types::DATE_MUTABLE;
        }

        if (is_array($getNormData)) {
            return 'json';
        }

        if (is_int($getNormData)) {
            return Types::INTEGER;
        }

        return null;
    }

    public function saveGroupData($key, FormInterface $form)
    {
        $types = [];

        foreach ($form->all() as $item) {
            $types[$item->getName()] = $this->guessType($item);
        }

        $this->repository->saveMultiple($key, $form->getData(), $types);
    }

    public function saveUserGroupData($key, FormInterface $form)
    {
        $types = [];

        foreach ($form->all() as $item) {
            $types[$item->getName()] = $this->guessType($item);
        }

        $formData = $form->getData();

        foreach ($formData as $k => $val) {
            $checkBoxKey = $k.'Preference';

            if (array_key_exists($checkBoxKey, $formData)) {
                if ($formData[$checkBoxKey]) {
                    unset($formData[$k]);
                    $this->repository->removeByKey($key.'.'.$k);
                }

                unset($types[$checkBoxKey]);
                unset($formData[$checkBoxKey]);
            }
        }

        $this->repository->saveMultiple($key, $formData, $types);
    }

    public function getConfigurationGroupForm($group, $data = null): FormInterface
    {
        if (null === $data) {
            $data = $this->getConfigurationsByGroupKey($group);
        }

        return $this->configurationGroups[$group]->getForm($this->formFactory, $data);
    }

    public function getConfigurationGroupLabel($group): string
    {
        return $this->configurationGroups[$group]->getLabel();
    }

    public function getUserConfigurationValuesByGroupKey($groupKey): array
    {
        $username = $this->getUsername();
        $configurations = $this->repository->getConfigurationByUsernameAndGroup($username, $groupKey);
        $values = [];

        foreach ($configurations as $configuration) {
            $key = str_replace("{$username}.", '', $configuration->getId());
            $key = str_replace("{$groupKey}.", '', $key);

            if (str_contains($configuration->getId(), $username)) {
                $values[$key] = $configuration->getValue();
                $values[$key.'Preference'] = false;
            } elseif (!array_key_exists($key, $values)) {
                $values[$key] = $configuration->getValue();
                $values[$key.'Preference'] = true;
            }
        }

        return $values;
    }

    public function concatUsernameWithKey($key): string
    {
        $username = $this->getUsername();

        if (!str_starts_with($key, $username)) {
            $key = "{$username}.{$key}";
        }

        return $key;
    }

    public function getValueByKey(int $isGlobal, string $key): string
    {
        $username = $this->getUsername();
        $key = str_replace("{$username}.", '', $key);

        if (!$isGlobal) {
            $key = $username.'.'.$key;
        }

        $result = $this->repository->getConfigurationValue($key);

        if ($result === null) {
            $result = '';
        }

        return $result;
    }

    private function getUsername(): ?string
    {
        if (null == $this->tokenStorage->getToken()) {
            return '';
        }

        return $this->tokenStorage->getToken()->getUser()->getUsername();
    }

    protected function typeCast($value, $type)
    {
        if (empty($value)) {
            return null;
        }

        switch ($type) {
            case Types::DATE_MUTABLE:
            case Types::DATETIME_MUTABLE:
                return new \DateTime($value);
            case Types::BOOLEAN:
                return (bool)$value;
            case Types::INTEGER:
                return (int)$value;
            case Types::JSON:
                return json_decode($value);
            default:
                return $value;
        }
    }

    public function getConfigurationValue($id, $type = null)
    {
        $key = $this->concatUsernameWithKey($id);
        $value = $this->repository->getConfigurationValue($key);
        if (null === $value) {
            $value = $this->repository->getConfigurationValue($id);
        }

        if (null === $type) {
            return $value;
        }

        return $this->typeCast($value, $type);
    }

    public function getFrontendConfigValuesByGroup($group): array
    {
        $items = $this->repository->loadAllByGroup($group, true, true);
        $userItems = $this->repository->loadAllByGroup($this->concatUsernameWithKey($group), true, true);
        foreach ($userItems as $key => $item) {
            if ('DEFAULT' === $item) {
                continue;
            }
            $items[$key] = $item;
        }

        return $items;
    }
}
