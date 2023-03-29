<?php

namespace Xiidea\EasyConfigBundle\Model;

/**
 *
 */
class BaseConfig implements BaseConfigInterface
{
    private $id;

    private $value;

    private $locked;

    /**
     * @var string
     */
    private $type;

    private $frontend = false;

    private $module;

    private $label;

    private $isGlobal;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocked()
    {
        return $this->locked;
    }

    public function setLocked(bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFrontend(): bool
    {
        return $this->frontend;
    }

    /**
     * @param bool $frontend
     */
    public function setFrontend(bool $frontend): void
    {
        $this->frontend = $frontend;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param mixed $module
     */
    public function setModule($module): void
    {
        $this->module = $module;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getIsGlobal()
    {
        return $this->isGlobal;
    }

    /**
     * @param mixed $isGlobal
     */
    public function setIsGlobal(bool $isGlobal): self
    {
        $this->isGlobal = $isGlobal;

        return $this;
    }
}
