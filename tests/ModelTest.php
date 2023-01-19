<?php

declare(strict_types=1);

namespace Yii\Model\Tests;

use InvalidArgumentException;
use NonNamespaced;
use PHPUnit\Framework\TestCase;
use stdClass;
use Yii\Model\AbstractModel;
use Yii\Model\Tests\Support\Model\Model;
use Yii\Model\Tests\Support\Model\Nested;
use Yii\Model\Tests\Support\Model\PropertyType;

require __DIR__ . '/Support/Model/NonNamespaced.php';

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ModelTest extends TestCase
{
    public function testAttributes(): void
    {
        $model = new Model();

        $this->assertSame(['public', 'login', 'password'], $model->attributes());
    }

    public function testGetAttributeValue(): void
    {
        $model = new PropertyType();

        $model->setValue('array', [1, 2]);

        $this->assertIsArray($model->getAttributeValue('array'));
        $this->assertSame([1, 2], $model->getAttributeValue('array'));

        $model->setValue('bool', true);

        $this->assertIsBool($model->getAttributeValue('bool'));
        $this->assertSame(true, $model->getAttributeValue('bool'));

        $model->setValue('float', 1.2023);

        $this->assertIsFloat($model->getAttributeValue('float'));
        $this->assertSame(1.2023, $model->getAttributeValue('float'));

        $model->setValue('int', 1);

        $this->assertIsInt($model->getAttributeValue('int'));
        $this->assertSame(1, $model->getAttributeValue('int'));

        $model->setValue('object', new stdClass());

        $this->assertIsObject($model->getAttributeValue('object'));
        $this->assertInstanceOf(stdClass::class, $model->getAttributeValue('object'));

        $model->setValue('string', 'samdark');

        $this->assertIsString($model->getAttributeValue('string'));
        $this->assertSame('samdark', $model->getAttributeValue('string'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Undefined property: "Yii\Model\Tests\Support\Model\PropertyType::noExist".'
        );

        $model->getAttributeValue('noExist');
    }

    public function testGetData(): void
    {
        $model = new Model();

        $this->assertTrue($model->load(['Model' => ['login' => 'test', 'password' => 'test']]));
        $this->assertSame(['login' => 'test', 'password' => 'test'], $model->getData());
    }

    public function testGetFormName(): void
    {
        $model = new Model();

        $this->assertSame('Model', $model->getFormName());

        $model = new class () extends AbstractModel {
        };

        $this->assertSame('', $model->getFormName());

        $model = new NonNamespaced();

        $this->assertSame('NonNamespaced', $model->getFormName());
    }

    public function testGetNestedValue(): void
    {
        $model = new Nested();

        $this->assertSame('Write your id or email.', $model->getHint('user.login'));
    }

    public function testHas(): void
    {
        $model = new Model();

        $this->assertTrue($model->has('login'));
        $this->assertTrue($model->has('password'));
    }

    public function testIsEmpty(): void
    {
        $model = new Model();

        $this->assertTrue($model->isEmpty());
    }

    public function testLoad(): void
    {
        $model = new Model();

        $this->assertTrue($model->load(['Model' => ['login' => 'test', 'password' => 'test']]));
        $this->assertSame('test', $model->getAttributeValue('login'));
        $this->assertSame('test', $model->getAttributeValue('password'));
    }

    public function testLoadPublicField(): void
    {
        $model = new Model();

        $this->assertEmpty($model->public);

        $data = [
            'Model' => [
                'public' => 'samdark',
            ],
        ];

        $this->assertTrue($model->load($data));
        $this->assertSame('samdark', $model->public);
    }

    public function testLoadWithEmptyScope(): void
    {
        $model = new class () extends AbstractModel {
            private int $int = 1;
            private string $string = 'string';
            private float $float = 3.14;
            private bool $bool = true;
        };

        $model->load([
            'int' => '2',
            'float' => '3.15',
            'bool' => 'false',
            'string' => 555,
        ], '');

        $this->assertIsInt($model->getAttributeValue('int'));
        $this->assertIsFloat($model->getAttributeValue('float'));
        $this->assertIsBool($model->getAttributeValue('bool'));
        $this->assertIsString($model->getAttributeValue('string'));
    }

    public function testSetValue(): void
    {
        $model = new Model();
        $model->setValue('login', 'test');
        $model->setValue('password', 'test');

        $this->assertSame('test', $model->getAttributeValue('login'));
        $this->assertSame('test', $model->getAttributeValue('password'));
    }

    public function testSetValues(): void
    {
        $model = new PropertyType();

        // setValue attributes with array and to camel case disabled.
        $model->setValues(
            [
                'array' => [],
                'bool' => false,
                'float' => 1.434536,
                'int' => 1,
                'object' => new stdClass(),
                'string' => '',
            ],
        );

        $this->assertIsArray($model->getAttributeValue('array'));
        $this->assertIsBool($model->getAttributeValue('bool'));
        $this->assertIsFloat($model->getAttributeValue('float'));
        $this->assertIsInt($model->getAttributeValue('int'));
        $this->assertIsObject($model->getAttributeValue('object'));
        $this->assertIsString($model->getAttributeValue('string'));

        // setValue attributes with array and to camel case enabled.
        $model->setValues(
            [
                'array' => [],
                'bool' => 'false',
                'float' => '1.434536',
                'int' => '1',
                'object' => new stdClass(),
                'string' => '',
            ],
        );

        $this->assertIsArray($model->getAttributeValue('array'));
        $this->assertIsBool($model->getAttributeValue('bool'));
        $this->assertIsFloat($model->getAttributeValue('float'));
        $this->assertIsInt($model->getAttributeValue('int'));
        $this->assertIsObject($model->getAttributeValue('object'));
        $this->assertIsString($model->getAttributeValue('string'));
    }

    public function testSetValuesException(): void
    {
        $model = new PropertyType();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attribute "noExist" does not exist');

        $model->setValues(['noExist' => []]);
    }
}
