<?php

declare(strict_types=1);

namespace Yii\Model\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yii\Model\Tests\Support\Model\Nested;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ModelNestedTest extends TestCase
{
    public function testgetRawDataNotNestedException(): void
    {
        $model = new Nested();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attribute "profile" is not a nested attribute.');

        $model->getAttributeValue('profile.user');
    }

    public function testgetAttributeValueUndefinedPropertyException(): void
    {
        $model = new Nested();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Undefined property: "Yii\Model\Tests\Support\Model\Login::noExist'
        );

        $model->getAttributeValue('user.noExist');
    }

    public function testgetAttributeValue(): void
    {
        $model = new Nested();
        $model->setAttributeValue('user.login', 'admin');

        $this->assertSame('admin', $model->getAttributeValue('user.login'));
    }

    public function testHasException(): void
    {
        $model = new Nested();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Undefined property: "Yii\Model\Tests\Support\Model\Login::noExist'
        );

        $model->has('user.noExist');
    }

    public function testLoadPublicField(): void
    {
        $model = new Nested();

        $this->assertEmpty($model->getAttributeValue('user.login'));
        $this->assertEmpty($model->getAttributeValue('user.password'));

        $data = [
            'Nested' => [
                'user.login' => 'joe',
                'user.password' => '123456',
            ],
        ];

        $this->assertTrue($model->load($data));
        $this->assertSame('joe', $model->getAttributeValue('user.login'));
        $this->assertSame('123456', $model->getAttributeValue('user.password'));
    }
}
