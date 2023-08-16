# Easy Configuration


[![Coverage Status](https://coveralls.io/repos/xiidea/EasyConfigBundle/badge.svg?branch=main&service=github)](https://coveralls.io/github/xiidea/EasyConfigBundle?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xiidea/EasyConfigBundle/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/xiidea/EasyConfigBundle/?branch=main)
[![Latest Stable Version](https://poser.pugx.org/xiidea/easy-config/v/stable.png)](https://packagist.org/packages/xiidea/easy-config)
[![Total Downloads](https://poser.pugx.org/xiidea/easy-config/downloads.png)](https://packagist.org/packages/xiidea/easy-config)
[![License](http://poser.pugx.org/xiidea/easy-config/license)](https://packagist.org/packages/xiidea/easy-conifig)

### A Symfony Bundle for easy configuration management.

## Features Include
* Group wise dynamic form creation ability
* Store global configuration
* Store user wise configuration
* Get configuration through rest API

## Install
1. Download and Enable EasyConfigBundle
2. Register an Interface to Kernel file 
3. Create Config entity class 
4. Configure yaml file 
5. Update database schema 
6. Create your first form 
7. Register the bundle’s routes 
8. Overriding default EasyConfigBundle templates (optional)


### 1. Download and Enable EasyConfigBundle :
##### _Download through composer :_
Open a command console, enter project directory and execute the following command to download the latest stable version of this bundle:
```bash
$ composer require xiidea/easy-config
```
##### _Enable Bundle (No need when [Flex](https://symfony.com/doc/current/setup/flex.html) is available):_
The bundle should be automatically enabled by Symfony [Flex](https://symfony.com/doc/current/setup/flex.html). If you don't use [Flex](https://symfony.com/doc/current/setup/flex.html), you will need to enable the bundle manually by adding the following line in the config/bundles.php file of your project:

```php
<?php
// src/Kernel.php

return [
    // ...
    Xiidea\EasyConfigBundle\XiideaEasyConfigBundle::class => ['all' => true],
];
```

### 2. Register an Interface to Kernel file
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

### 3. Create Config entity class:
By default `EasyConfigBundle` supports Doctrine ORM. However, you must provide an Entity class and the class has to extend the class  `\Xiidea\EasyConfigBundle\Model\BaseConfig`. To configure the Entity class properly please follow the detailed [instructions](https://github.com/xiidea/EasyConfigBundle/blob/main/Resources/doc/config-entity.md).

### 4. Configure yaml file:
Create a file in the following directory `config/packages` with the exact name `xiidea_easy_config.yaml`
A sample config file is available in this path `Resources/config/config-sample.yml`. Copy this sample file's content and paste to just created file. Do not forget to change your Entity class name which has been mentioned in [Step 3](https://github.com/xiidea/EasyConfigBundle/blob/main/Resources/doc/config-entity.md).
```yaml
# Xiidea Easy Config Configuration Sample

xiidea_easy_config:
    config_class: App\Entity\Configuration
```
### 5. Update database schema:
It’s time to set up the database schema, open your command console, go to your project root path and execute the following command.

```bash
$ php bin/console doctrine:schema:update --force
```
### 6. Create form group and type:
Create a form group class and form type with your necessary fields. Please follow the [instructions](https://github.com/xiidea/EasyConfigBundle/blob/main/Resources/doc/form-group-and-type.md) to create the form group and type.

### 7. Register the bundle’s routes:
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

### 8. Overriding default EasyConfigBundle templates (optional)
Template overriding is not important to use **EasyConfigBundle** bundle but if you want to keep UI as similar as your application you can override the template, to do so follow the [instructions](https://github.com/xiidea/EasyConfigBundle/blob/main/Resources/doc/overriding_templates.md) 

**Congratulations !**

Your application is ready to store configurations, just browse the routes.

### License
The Easy Config Bundle is licensed under the MIT license. See the LICENSE file for more details.
