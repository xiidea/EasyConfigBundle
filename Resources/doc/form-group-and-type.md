# Step 6: Create Form group and type
### Create form group:
You can create this form group class under the service directory, in below example, a form group class has been created with the name `MasterDataConfigurationGroup` under the following `Service/Configuration/Group` directory. It is important that, this group class has to implement the base group class `BaseConfigurationGroup` which is being provided by the `EasyConfigBundle`.

For example:
```php
<?php
# src/Service/Configuration/Group/MasterDataConfigurationGroup.php

namespace App\Service\Configuration\Group;

use App\Form\Config\MasterDataConfigurationType;
use Xiidea\EasyConfigBundle\Services\FormGroup\BaseConfigGroup;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MasterDataConfigurationGroup extends BaseConfigurationGroup
{
    private AuthorizationCheckerInterface $checker;

    public function __construct(AuthorizationCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    public function getForm(FormFactory $formFactory, $data = null, array $options = []): FormInterface
    {
        $options['isReadonly'] = !$this->checker->isGranted(self::getAuthorSecurityLevels());
        $builder = $formFactory
            ->createBuilder(MasterDataConfigurationType::class, $data, $options);

        return $builder->getForm();
    }

    /**
     * @return string Base key of policy group
     */
    public static function getNameSpace(): string
    {
        return 'master-data';
    }

    public static function getLabel(): string
    {
        return 'Master data';
    }

    public static function getAuthorSecurityLevels(): ?string
    {
        return 'ROLE_S_ADMIN';
    }

    public static function getViewSecurityLevels(): ?string
    {
        return 'ROLE_APP_CONFIG_V';
    }
}
```
As you can see, the form factory inside `getForm` method expecting a from type `MasterDataConfigurationType::class`. This class is simple symfony's form type that you can create as your own with your necessary fields.
Even then, a very basic example with two fields is being given below:

### Create form type:

```php
<?php
#src/Form/Config/MasterDataConfigurationType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class MasterDataConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'allowedImageType',
                ChoiceType::class,
                [
                    'label' => 'Allowed Image Type',
                    'choices' => [
                        'JPG' => 'jpg',
                        'JPEG' => 'jpeg',
                        'PNG' => 'png',
                    ],
                    'multiple' => false,
                    'attr' => [
                        'placeholder' => 'Allowed Image Type(eg. .png, .jpg )',
                    ],
                ]
            )
            ->add(
                'allowedMaxImageSize',
                IntegerType::class,
                [
                    'label' => 'Max Allowed Image Size',
                    'constraints' => [
                        new NotBlank(),
                        new Assert\Range(['min' => 0, 'max' => 1024),
                    ],
                    'attr' => [
                        'placeholder' => 'Allowed Image Size (in Bytes)',
                        'data-rule-number' => true,
                        'pattern' => "\d*",
                    ],
                    'help' => 'Size in Bytes',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'attr' => [
                    'id' => 'test-config-form',
                    'novalidate' => 'novalidate',
                ],
            ]
        );
    }
}
```
