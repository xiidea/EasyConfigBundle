<?php

namespace Xiidea\EasyConfigBundle\Services\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Xiidea\EasyConfigBundle\Exception\UnrecognizedEntityException;
use Xiidea\EasyConfigBundle\Model\BaseConfig;

class ConfigRepository implements ConfigRepositoryInterface
{
    private $repository;

    public function __construct(private EntityManagerInterface $em, private $entityClass)
    {
        $this->isRecognizedEntity($entityClass);
        $this->repository = $em->getRepository($entityClass);
    }

    /**
     * @param $entityClass
     * @return void
     * @throws UnrecognizedEntityException
     */
    private function isRecognizedEntity($entityClass): void
    {
        if (!new $entityClass('') instanceof BaseConfig) {
            throw new UnrecognizedEntityException();
        }
    }

    public function getConfigurationByUsernameAndGroup(string $username, string $groupKey)
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->setCacheable(true)
            ->setCacheRegion('config_group')
            ->where($qb->expr()->like('c.id', ':username'))
            ->setParameter('username', $username.'.'.$groupKey.'.%')
            ->orWhere(
                $qb->expr()->andX(
                    $qb->expr()->like('c.id', ':groupKey'),
                    $qb->expr()->eq('c.isGlobal', ':isGlobal')
                )
            )
            ->setParameter('groupKey', $groupKey.'.%')
            ->setParameter('isGlobal', 1)
            ->getQuery()
            ->getResult();
    }

    public function getConfigurationByUsernameAndKey(string $username, string $key)
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->setCacheable(true)
            ->setCacheRegion('config_key')
            ->where('c.id=:username')
            ->setParameter('username', $username.'.'.$key)
            ->orWhere('c.id=:key')
            ->setParameter('key', $key)
            ->getQuery()
            ->getResult();
    }

    private function createQueryBuilder(string $alias)
    {
        return $this->repository->createQueryBuilder($alias);
    }

    public function loadAllByGroup($groupKey, $valueOnly = false, $frontendOnly = false): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb = $qb
            ->where($qb->expr()->like('c.id', ':group_key'))
            ->setCacheable(true)
            ->setCacheRegion('master_entity_region')
            ->setParameter('group_key', $groupKey.'.%');

        if ($frontendOnly) {
            $qb
                ->andWhere($qb->expr()->eq('c.frontend', ':is_frontend'))
                ->setParameter('is_frontend', true);
        }

        $configurations = $qb->getQuery()->getResult();

        if (!$configurations) {
            return [];
        }

        return $this->getNormalizedArray($groupKey, $configurations, $valueOnly);
    }

    private function getNormalizedArray($groupKey, $configurations, $valueOnly): array
    {
        $keyLength = strlen($groupKey) + 1;
        $return = [];

        foreach ($configurations as $configuration) {
            $return[substr($configuration->getId(), $keyLength)] = $valueOnly ? $configuration->getAs(
            ) : $configuration;
        }

        return $return;
    }


    public function getValuesByGroupKey($configurationGroup)
    {
        return array_map([$this, 'getValue'], (array)$this->loadAllByGroup($configurationGroup));
    }

    protected function getValue($configuration)
    {
        return $configuration->getValue();
    }

    public function saveMultiple($baseKey, array $values = [], array $types = [])
    {
        foreach ($values as $key => $value) {
            $this->save($baseKey.".{$key}", $value, isset($types[$key]) ?? $types[$key], false, false, false);
        }

        $this->em->flush();
    }

    /**
     * @param $key
     * @param $value
     * @param $type
     * @param bool $locked
     * @param bool $force
     * @param bool $flush
     * @return BaseConfig
     */
    public function save($key, $value, $type = null, bool $locked = false, bool $force = false, bool $flush = true)
    {
        $configuration = $this->repository->find($key);

        if (!$configuration) {
            $configuration = new $this->entityClass($key);
            $configuration->setId($key);
        } elseif ($configuration->isLocked() && !$force) {
            return $configuration;
        }

        $configuration->setValue($value);
        $configuration->setType($type);
        $configuration->setLocked($locked);

        $this->em->persist($configuration);

        if ($flush) {
            $this->em->flush($configuration);
        }

        return $configuration;
    }

    public function removeByKey($key)
    {
        $config = $this->repository->find($key);

        if ($config) {
            $this->em->remove($config);
            $this->em->flush();
        }
    }

    public function getConfigurationValue($key)
    {
        $configuration = $this->repository->find($key);

        if (null == $configuration) {
            return null;
        }

        return $configuration->getValue();
    }

    public function getGlobalAndUserConfigurationByKey(string $username, string $key)
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->setCacheable(true)
            ->setCacheRegion('config_key')
            ->where('c.id=:username')
            ->setParameter('username', $username.'.'.$key)
            ->orWhere('c.id=:key')
            ->setParameter('key', $key)
            ->orderBy('c.isGlobal', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
