<?php

declare(strict_types=1);

namespace Yii\Model\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yii\Model\Tests\TestSupport\FormModel\Nested;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class FormModelNestedTest extends TestCase
{
    public function testgetRawDataNotNestedException(): void
    {
        $formModel = new Nested();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attribute "profile" is not a nested attribute.');
        $formModel->getAttributeValue('profile.user');
    }

    public function testgetAttributeValueUndefinedPropertyException(): void
    {
        $formModel = new Nested();
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Undefined property: "Yii\Model\Tests\TestSupport\FormModel\Login::noExist'
        );
        $formModel->getAttributeValue('user.noExist');
    }

    public function testGetHint(): void
    {
        $formModel = new Nested();
        $this->assertSame('Write your id or email.', $formModel->getHint('user.login'));
    }

    public function testGetLabel(): void
    {
        $formModel = new Nested();
        $this->assertSame('Login:', $formModel->getLabel('user.login'));
    }

    public function testGetPlaceHolder(): void
    {
        $formModel = new Nested();
        $this->assertSame('Write Username or Email.', $formModel->getPlaceHolder('user.login'));
    }

    public function testgetAttributeValue(): void
    {
        $formModel = new Nested();
        $formModel->setValue('user.login', 'admin');

        $this->assertSame('admin', $formModel->getAttributeValue('user.login'));
    }

    public function testHasException(): void
    {
        $form = new Nested();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Undefined property: "Yii\Model\Tests\TestSupport\FormModel\Login::noExist'
        );
        $form->has('user.noExist');
    }

    public function testLoadPublicField(): void
    {
        $formModel = new Nested();
        $this->assertEmpty($formModel->getAttributeValue('user.login'));
        $this->assertEmpty($formModel->getAttributeValue('user.password'));

        $data = [
            'Nested' => [
                'user.login' => 'joe',
                'user.password' => '123456',
            ],
        ];

        $this->assertTrue($formModel->load($data));
        $this->assertSame('joe', $formModel->getAttributeValue('user.login'));
        $this->assertSame('123456', $formModel->getAttributeValue('user.password'));
    }
}
