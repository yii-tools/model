<?php

declare(strict_types=1);

namespace Yii\Model\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Yii\Model\AbstractFormModel;
use Yii\Model\FormModelInterface;
use Yii\Model\Tests\TestSupport\FormModel\Login;
use Yii\Model\Tests\TestSupport\FormModel\PropertyType;
use Yii\Model\Tests\TestSupport\FormModel\PropertyVisibility;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class FormModelTest extends TestCase
{
    public function testGetAttributeValue(): void
    {
        $formModel = new PropertyType();

        $formModel->setValue('array', [1, 2]);

        $this->assertIsArray($formModel->getAttributeValue('array'));
        $this->assertSame([1, 2], $formModel->getAttributeValue('array'));

        $formModel->setValue('bool', true);

        $this->assertIsBool($formModel->getAttributeValue('bool'));
        $this->assertSame(true, $formModel->getAttributeValue('bool'));

        $formModel->setValue('float', 1.2023);

        $this->assertIsFloat($formModel->getAttributeValue('float'));
        $this->assertSame(1.2023, $formModel->getAttributeValue('float'));

        $formModel->setValue('int', 1);

        $this->assertIsInt($formModel->getAttributeValue('int'));
        $this->assertSame(1, $formModel->getAttributeValue('int'));

        $formModel->setValue('object', new stdClass());

        $this->assertIsObject($formModel->getAttributeValue('object'));
        $this->assertInstanceOf(stdClass::class, $formModel->getAttributeValue('object'));

        $formModel->setValue('string', 'samdark');

        $this->assertIsString($formModel->getAttributeValue('string'));
        $this->assertSame('samdark', $formModel->getAttributeValue('string'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Undefined property: "Yii\Model\Tests\TestSupport\FormModel\PropertyType::noExist".'
        );

        $formModel->getAttributeValue('noExist');
    }

    public function testGetHint(): void
    {
        $formModel = new Login();

        $this->assertSame('Write your id or email.', $formModel->getHint('login'));
        $this->assertSame('Write your password.', $formModel->getHint('password'));
    }

    public function testGetHintException(): void
    {
        $formModel = new Login();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Attribute 'noExist' does not exist.");

        $formModel->getHint('noExist');
    }

    public function testGetHints(): void
    {
        $formModel = new PropertyVisibility();

        $this->assertSame([], $formModel->getHints());
    }

    public function testGetInputId(): void
    {
        $formModel = new Login();

        $this->assertSame('login-login', $formModel->getInputId('login'));
    }

    /**
     * @dataProvider \Yii\Model\Tests\Provider\FormModelProvider::getInputName()
     */
    public function testGetInputName(FormModelInterface $formModel, string $attribute, string $expected): void
    {
        $this->assertSame($expected, $formModel->getInputname($attribute));
    }

    public function testGetInputNameException(): void
    {
        $anonymousForm = new class () extends AbstractFormModel {
        };

        $this->expectExceptionMessage('formName() cannot be empty for tabular inputs.');

        $anonymousForm->getInputName('[0]dates[0]');
    }

    public function testGetLabel(): void
    {
        $formModel = new Login();

        $this->assertSame('Login:', $formModel->getLabel('login'));
    }

    public function testGetLabelException(): void
    {
        $formModel = new Login();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Attribute 'noExist' does not exist.");

        $formModel->getLabel('noExist');
    }

    public function testGetName(): void
    {
        $formModel = new Login();

        $this->assertSame('login', $formModel->getName('[0]login'));
        $this->assertSame('login', $formModel->getName('login[0]'));
        $this->assertSame('login', $formModel->getName('[0]login[0]'));
    }

    public function testGetNameException(): void
    {
        $formModel = new Login();

        $this->expectExceptionMessage("Attribute 'noExist' does not exist.");

        $formModel->getName('noExist');
    }

    public function testGetNameInvalid(): void
    {
        $formModel = new Login();

        $this->expectExceptionMessage('Attribute name must contain word characters only.');

        $formModel->getName('content body');
    }

    public function testGetLabels(): void
    {
        $formModel = new PropertyVisibility();

        $this->assertSame([], $formModel->getLabels());
    }

    public function testGetPlaceHolder(): void
    {
        $formModel = new Login();

        $this->assertSame('Write Username or Email.', $formModel->getPlaceHolder('login'));
        $this->assertSame('Write Password.', $formModel->getPlaceHolder('password'));
    }

    public function testGetPlaceException(): void
    {
        $formModel = new Login();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Attribute 'noExist' does not exist.");

        $formModel->getPlaceHolder('noExist');
    }

    public function testGetPlaceHolders(): void
    {
        $formModel = new PropertyVisibility();

        $this->assertSame([], $formModel->getPlaceHolders());
    }

    public function testHas(): void
    {
        $formModel = new Login();

        $this->assertTrue($formModel->has('login'));
        $this->assertTrue($formModel->has('password'));
        $this->assertTrue($formModel->has('rememberMe'));
        $this->assertFalse($formModel->has('noExist'));
        $this->assertFalse($formModel->has('extraField'));
    }

    public function testMultibyteGetName(): void
    {
        $formModel = new class () extends AbstractFormModel {
            private string $登录 = '';
        };

        $this->assertSame('登录', $formModel->getName('[0]登录'));
        $this->assertSame('登录', $formModel->getName('登录[0]'));
        $this->assertSame('登录', $formModel->getName('[0]登录[0]'));
    }

    public function testMultibyteGetInputId(): void
    {
        $formModel = new class () extends AbstractFormModel {
            private string $mĄkA = '';
        };

        $this->assertSame('mąka', $formModel->getInputId('mĄkA'));
    }

    public function testSet(): void
    {
        $formModel = new PropertyType();

        $formModel->setValue('array', []);

        $this->assertIsArray($formModel->getAttributeValue('array'));

        $formModel->setValue('bool', false);

        $this->assertIsBool($formModel->getAttributeValue('bool'));

        $formModel->setValue('bool', 'false');

        $this->assertIsBool($formModel->getAttributeValue('bool'));

        $formModel->setValue('float', 1.434536);

        $this->assertIsFloat($formModel->getAttributeValue('float'));

        $formModel->setValue('float', '1.434536');

        $this->assertIsFloat($formModel->getAttributeValue('float'));

        $formModel->setValue('int', 1);

        $this->assertIsInt($formModel->getAttributeValue('int'));

        $formModel->setValue('int', '1');

        $this->assertIsInt($formModel->getAttributeValue('int'));

        $formModel->setValue('object', new stdClass());

        $this->assertIsObject($formModel->getAttributeValue('object'));

        $formModel->setValue('string', '');

        $this->assertIsString($formModel->getAttributeValue('string'));
    }

    public function testSets(): void
    {
        $formModel = new PropertyType();

        // setValue attributes with array and to camel case disabled.
        $formModel->setValues(
            [
                'array' => [],
                'bool' => false,
                'float' => 1.434536,
                'int' => 1,
                'object' => new stdClass(),
                'string' => '',
            ],
        );

        $this->assertIsArray($formModel->getAttributeValue('array'));
        $this->assertIsBool($formModel->getAttributeValue('bool'));
        $this->assertIsFloat($formModel->getAttributeValue('float'));
        $this->assertIsInt($formModel->getAttributeValue('int'));
        $this->assertIsObject($formModel->getAttributeValue('object'));
        $this->assertIsString($formModel->getAttributeValue('string'));

        // setValue attributes with array and to camel case enabled.
        $formModel->setValues(
            [
                'array' => [],
                'bool' => 'false',
                'float' => '1.434536',
                'int' => '1',
                'object' => new stdClass(),
                'string' => '',
            ],
        );

        $this->assertIsArray($formModel->getAttributeValue('array'));
        $this->assertIsBool($formModel->getAttributeValue('bool'));
        $this->assertIsFloat($formModel->getAttributeValue('float'));
        $this->assertIsInt($formModel->getAttributeValue('int'));
        $this->assertIsObject($formModel->getAttributeValue('object'));
        $this->assertIsString($formModel->getAttributeValue('string'));
    }

    public function testSetsException(): void
    {
        $formModel = new PropertyType();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Attribute "noExist" does not exist');

        $formModel->setValues(['noExist' => []]);
    }
}
