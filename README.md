Easy Config Bundle
==================
### A Symfony Bundle for easy configuration management.

## Features Include
* Group wise dynamic form creation ability
* Store global configuration
* Store user wise configuration
* Get configuration through rest API

## Install
1. Download EasyConfigBundle using composer
2. Enable the bundle
3. Register an Interface to Kernel file
4. Create Config entity class
5. Configure yaml file
6. Update database schema 
7. Create your first form 
8. Register the bundle’s routes
9. Overriding default EasyConfigBundle templates (optional)


### 1. Download EasyConfigBundle using composer :
Open a command console, enter project directory and execute the following command to download the latest stable version of this bundle:
```bash
$ composer require xiidea/easy-config-bundle
```

### 2. Enable the Bundle:
The bundle should be automatically enabled by Symfony Flex. If you don't use [Flex](https://symfony.com/doc/current/setup/flex.html), you'll need to manually enable the bundle by adding the following line in the config/bundles.php file of your project:

```php
<?php
// src/Kernel.php

return [
    // ...
    Xiidea\EasyConfigBundle\XiideaEasyConfigBundle::class => ['all' => true],
];
```

### 3. Register an Interface to Kernel file
Open application Kernel.php File and add below code inside the build method of this file
```php
<?php
// src/Kernel.php

public function build(ContainerBuilder $container)
{
    // ...
    $container->registerForAutoconfiguration(ConfigGroupInterface::class)
	   ->addTag('xiidea.easy_config.group');
}
```

_Note: Do not forget to include the below line above in your src/Kernel.php_

`use Xiidea\EasyConfigBundle\Services\FormGroup\ConfigGroupInterface;`

### 4. Create Config entity class:
By default `XiideaEasyConfigBundle` supports Doctrine ORM. However, you must provide an Entity class and the class has to extend the class  `\Xiidea\EasyConfigBundle\Model\BaseConfig`. To configure the Entity class properly please follow the detailed [instructions](https://github.com/xiidea/EasyConfigBundle/blob/main/Resources/doc/config-entity.md).

### 5. Configure yaml file:
Create a file in the following directory `config/packages` with the exact name `xiidea_easy_config.yaml`
A sample config file is available in this path `Resources/config/config-sample.yml`. Copy this sample file's content and paste to just created file. Do not forget to change your Entity class name which has been mentioned in [Step 4](https://github.com/xiidea/EasyConfigBundle/blob/main/Resources/doc/config-entity.md).
```yaml
# Xiidea Easy Config Configuration Sample

xiidea_easy_config:
    config_class: App\Entity\Configuration
```
### 6. Update database schema:
It’s time to set up the database schema, open your command console, go to your project root path and execute the following command.

```bash
$ php bin/console doctrine:schema:update --force
```
### 7. Create form group and type:
Create a form group class and form type with your necessary fields. Please follow the [instructions](https://github.com/xiidea/EasyConfigBundle/blob/main/Resources/doc/form-group-and-type.md) to create the form group and type.

### 8. Register the bundle’s routes:
Now it's time to access the form you have just created, for that you have to include bundle's routes to your application by the following way.
```yaml
xiidea_config_route:
    resource: "@XiideaEasyConfigBundle/Resources/config/routes.yaml"
    prefix: '/config'
```
_Note: You may change the prefix as your wish_

**Following routes are available in this bundle:**
* Index route `(prefix/)` : List of all forms
* Form group route `(prefix/group_key)` : Form of specific group key

### 9. Overriding default EasyConfigBundle templates (optional)
Template overriding is not important to use **EasyConfigBundle** bundle but if you want to keep UI as similar as your application you can override the template, to do so follow the [instructions](https://github.com/xiidea/EasyConfigBundle/blob/main/Resources/doc/overriding_templates.md) 

**Congratulations !**

Your application is ready to store configurations, just browse the routes.

### License
The Easy Config Bundle is licensed under the MIT license. See the LICENSE file for more details.