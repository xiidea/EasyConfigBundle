<?php

namespace Xiidea\EasyConfigBundle\Tests\Utility;

use PHPUnit\Framework\TestCase;
use Xiidea\EasyConfigBundle\Model\BaseConfig;
use Xiidea\EasyConfigBundle\Utility\StringUtil;

class StringUtilTest extends TestCase
{
    public function testGetLabelFromClass()
    {
        $label = StringUtil::getLabelFromClass('App\\Entities\\UserEntity');
        $this->assertEquals('User entity', $label);

        $labelFromObject = StringUtil::getLabelFromClass(new BaseConfig('config.test.item'));
        $this->assertEquals('Base config', $labelFromObject);
    }

    public function testHumanize()
    {
        $text = StringUtil::humanize('getUserInfo');
        $this->assertEquals('get user info', $text);

        $textCamel = StringUtil::humanize('UserInfoData');
        $this->assertEquals('user info data', $textCamel);

        $textReplace = StringUtil::humanize('dont');
        $this->assertEquals("don't", $textReplace);

        $textReplace = StringUtil::humanize('this_is_new');
        $this->assertEquals("this is new", $textReplace);
    }

    public function testGetClassBaseName()
    {
        $baseName = StringUtil::getClassBaseName('App\\Entities\\UserEntity');
        $this->assertEquals('UserEntity', $baseName);

        $baseNameFromObject = StringUtil::getClassBaseName(new \DateTime());
        $this->assertEquals('DateTime', $baseNameFromObject);
    }
}