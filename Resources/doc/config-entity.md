# Step 4: Create Config entity class
The BaseConfig class does not include ORM Mapping by default, so it is necessary to create your own mappings by extending the BaseConfig model provided by the bundle and configuring it accordingly.

For example:
### Doctrine ORM Entity Class
```php
<?php
// src/Entity/Configuration.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Xiidea\EasyConfigBundle\Model\BaseConfig;

/**
 * @ORM\Entity
 * @ORM\Table("configurations")
 */
class Configuration extends BaseConfig
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=255)
     * @ORM\Id
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $locked;

    /**
     * @ORM\Column(name="data_type", type="string", length=50, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $frontend = false;

    /**
     * @ORM\Column( type="string", length=255, nullable=true)
     */
    private $module;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $label;

    /**
     * @ORM\Column(name="is_global", type="boolean")
     */
    private $isGlobal;

    public function __construct($id)
    {
        parent::__construct($id);
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function isLocked(): ?bool
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

    public function getDescriptionAndType($event, $changes = null): array
    {
        return [
            'type' => 'Configuration changed',
            'description' => sprintf('Value of \'%s\' changed', $this->id),
        ];
    }

    public function getFrontend(): bool
    {
        return $this->frontend;
    }

    public function setFrontend(bool $frontend): void
    {
        $this->frontend = $frontend;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setModule($module): void
    {
        $this->module = $module;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label): void
    {
        $this->label = $label;
    }

    public function isGlobal(): ?bool
    {
        return $this->isGlobal;
    }

    public function setIsGlobal(bool $global): self
    {
        $this->isGlobal = $global;

        return $this;
    }
}
```
#### Configure your Entity class
```yaml
# config/packages/xiidea_easy_config.yaml

xiidea_easy_config:
    config_class: App\Entity\Configuration
```