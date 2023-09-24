<?php

namespace Xiidea\EasyConfigBundle\Model;

class BaseConfig implements BaseConfigInterface
{
    private $id;

    private $value;

    private bool $locked = false;

    private $type;

    private bool $frontend = false;

    private $module;

    private $label;

    private $isGlobal = true;

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
     * @param  string  $id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }


    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }


    public function getLocked(): bool
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


    public function isFrontend(): bool
    {
        return $this->frontend;
    }


    public function setFrontend(bool $frontend): self
    {
        $this->frontend = $frontend;

        return $this;
    }


    public function getModule(): ?string
    {
        return $this->module;
    }

    /**
     * @param  mixed  $module
     */
    public function setModule(?string $module): self
    {
        $this->module = $module;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }


    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }


    public function getIsGlobal(): bool
    {
        return $this->isGlobal;
    }


    public function setIsGlobal(bool $isGlobal): self
    {
        $this->isGlobal = $isGlobal;

        return $this;
    }
}
