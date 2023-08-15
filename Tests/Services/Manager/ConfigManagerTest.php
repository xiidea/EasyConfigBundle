<?php

namespace Xiidea\EasyConfigBundle\Tests\Services\Manager;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Xiidea\EasyConfigBundle\Services\FormGroup\ConfigGroupInterface;
use Xiidea\EasyConfigBundle\Services\Manager\ConfigManager;
use Xiidea\EasyConfigBundle\Services\Repository\ConfigRepositoryInterface;

class ConfigManagerTest extends TestCase
{
    private $repositoryMock;
    private $formFactoryMock;
    private $tokenStorageMock;
    private $authCheckerMock;
    private $configManager;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(ConfigRepositoryInterface::class);
        $this->formFactoryMock = $this->createMock(FormFactoryInterface::class);
        $this->tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $this->authCheckerMock = $this->createMock(AuthorizationCheckerInterface::class);
        $this->configManager = new ConfigManager(
            $this->repositoryMock,
            $this->formFactoryMock,
            $this->tokenStorageMock,
            $this->authCheckerMock
        );
    }

    public function testGetConfigurationGroups()
    {
        $tokenMock = $this->createMock(TokenInterface::class);
        $userMock = $this->createMock(UserInterface::class);

        $username = 'testuser';
        $userMock->expects(self::any())->method('getUserIdentifier')->willReturn($username);
        $tokenMock->method('getUser')->willReturn($userMock);

        $this->tokenStorageMock->expects(self::any())->method('getToken')->willReturn($tokenMock);

        // Mock a ConfigGroup
        $configGroupMock = $this->createMock(ConfigGroupInterface::class);
        $configGroupMock->method('getNameSpace')->willReturn($username.'.someconfig');

        $this->configManager->addConfigGroup($configGroupMock);
        $groups = $this->configManager->getConfigurationGroups();

        $this->assertArrayHasKey('someconfig', $groups);
        $this->assertSame($configGroupMock, $groups['someconfig']);
    }

    public function testGetConfigurationValueByKey()
    {
        $key = 'some.key';
        $username = 'testuser';
        $expectedValue = 'Expected Value';

        $tokenMock = $this->createMock(TokenInterface::class);
        $userMock = $this->createMock(UserInterface::class);

        $userMock->method('getUserIdentifier')->willReturn($username);
        $tokenMock->method('getUser')->willReturn($userMock);

        $this->tokenStorageMock->method('getToken')->willReturn($tokenMock);

        $this->repositoryMock->expects($this->once())
            ->method('getConfigurationByUsernameAndKey')
            ->with($username, $key)
            ->willReturn(
                [(new \Xiidea\EasyConfigBundle\Model\BaseConfig('config.item.id'))->setValue($expectedValue)]
            );

        $result = $this->configManager->getConfigurationValueByKey($key);

        $this->assertEquals($expectedValue, $result);
    }

    public function testConcatUsernameWithKey()
    {
        $tokenMock = $this->createMock(TokenInterface::class);
        $userMock = $this->createMock(UserInterface::class);

        $username = 'testuser';
        $userMock->method('getUserIdentifier')->willReturn($username);
        $tokenMock->method('getUser')->willReturn($userMock);

        $this->tokenStorageMock->method('getToken')->willReturn($tokenMock);

        // Test when key doesn't start with username
        $key = "somekey";
        $expected = "{$username}.{$key}";
        $this->assertEquals($expected, $this->configManager->concatUsernameWithKey($key));

        // Test when key starts with username
        $key = "{$username}.otherkey";
        $this->assertEquals($key, $this->configManager->concatUsernameWithKey($key));
    }

    public function testGetConfigurationValueWithConcatenatedKey()
    {
        $username = 'testuser';
        $id = 'someconfig';
        $expectedValue = 'TestValue';

        $tokenMock = $this->createMock(TokenInterface::class);
        $userMock = $this->createMock(UserInterface::class);

        $userMock->method('getUserIdentifier')->willReturn($username);
        $tokenMock->method('getUser')->willReturn($userMock);

        $this->tokenStorageMock->method('getToken')->willReturn($tokenMock);
        $this->repositoryMock->expects($this->once())
            ->method('getConfigurationValue')
            ->with("{$username}.{$id}")
            ->willReturn($expectedValue);

        $result = $this->configManager->getConfigurationValue($id);

        $this->assertEquals($expectedValue, $result);
    }

    public function testGetConfigurationValueWithoutConcatenatedKey()
    {
        $username = 'testuser';
        $id = 'someconfig';
        $expectedValue = 'TestValue';

        $tokenMock = $this->createMock(TokenInterface::class);
        $userMock = $this->createMock(UserInterface::class);

        $userMock->method('getUserIdentifier')->willReturn($username);
        $tokenMock->method('getUser')->willReturn($userMock);

        $this->tokenStorageMock->method('getToken')->willReturn($tokenMock);
        $this->repositoryMock->method('getConfigurationValue')
            ->willReturnOnConsecutiveCalls(null, $expectedValue);

        $result = $this->configManager->getConfigurationValue($id);

        $this->assertEquals($expectedValue, $result);
    }

    public function testGetConfigurationValueWithTypeCast()
    {
        $username = 'testuser';
        $id = 'someconfig';
        $originalValue = '2023-01-01';
        $expectedValue = new \DateTime($originalValue);

        $tokenMock = $this->createMock(TokenInterface::class);
        $userMock = $this->createMock(UserInterface::class);

        $userMock->method('getUserIdentifier')->willReturn($username);
        $tokenMock->method('getUser')->willReturn($userMock);

        $this->tokenStorageMock->method('getToken')->willReturn($tokenMock);
        $this->repositoryMock->expects($this->once())
            ->method('getConfigurationValue')
            ->with("{$username}.{$id}")
            ->willReturn($originalValue);

        $result = $this->configManager->getConfigurationValue($id, 'date');

        $this->assertEquals($expectedValue, $result);
    }
}
