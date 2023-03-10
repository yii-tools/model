<?php

declare(strict_types=1);

namespace Yii\Model\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yii\Model\Tests\Support\Model\PropertyType;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ModelTypeTest extends TestCase
{
    public function testGetAttributes(): void
    {
        $model = new PropertyType();

        $this->assertSame(
            [
                'array' => 'array',
                'bool' => 'bool',
                'float' => 'float',
                'int' => 'int',
                'nullable' => 'int',
                'object' => 'object',
                'string' => 'string',
            ],
            $model->types()->attributes()
        );
    }

    public function testPhpTypeCast(): void
    {
        $model = new PropertyType();

        $this->assertSame('1.1', $model->types()->phpTypeCast('string', 1.1));
        $this->assertSame(1.1, $model->types()->phpTypeCast('float', '1.1'));
    }

    public function testPhpTypeCastAttributeNoExist(): void
    {
        $model = new PropertyType();

        $this->assertSame(null, $model->types()->phpTypeCast('noExist', 1));
    }

    public function testPropertyStringable(): void
    {
        $model = new PropertyType();

        $objectStringable = new class () {
            public function __toString(): string
            {
                return 'joe doe';
            }
        };
        $model->setAttributeValue('string', $objectStringable);

        $this->assertSame('joe doe', $model->getAttributeValue('string'));
    }

    public function testSetValue(): void
    {
        $model = new PropertyType();

        // value is array
        $model->setAttributeValue('array', []);

        $this->assertSame([], $model->getAttributeValue('array'));

        // value is string
        $model->setAttributeValue('string', 'string');

        $this->assertSame('string', $model->getAttributeValue('string'));

        // value is int
        $model->setAttributeValue('int', 1);

        $this->assertSame(1, $model->getAttributeValue('int'));

        // value is bool
        $model->setAttributeValue('bool', true);

        $this->assertSame(true, $model->getAttributeValue('bool'));

        // value is null
        $model->setAttributeValue('object', null);

        $this->assertNull($model->getAttributeValue('object'));

        // value is null
        $model->setAttributeValue('nullable', null);

        $this->assertNull($model->getAttributeValue('nullable'));

        // value is int
        $model->setAttributeValue('nullable', 1);

        $this->assertSame(1, $model->getAttributeValue('nullable'));
    }

    public function testSetValueException(): void
    {
        $model = new PropertyType();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The value is not of type "string".');

        $model->setAttributeValue('string', []);
    }
}
