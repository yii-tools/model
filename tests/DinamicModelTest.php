<?php

declare(strict_types=1);

namespace Yii\Model\Tests;

use PHPUnit\Framework\TestCase;
use Yii\Model\Tests\TestSupport\FormModel\Dynamic;

use function array_column;
use function array_fill_keys;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class DinamicModelTest extends TestCase
{
    /**
     * @dataProvider \Yii\Model\Tests\Provider\FormModelProvider::dynamicAttributes()
     */
    public function testUUIDInputName(array $fields): void
    {
        $keys = array_column($fields, 'name');
        $form = new Dynamic(array_fill_keys($keys, null));

        /** @psalm-var string[][] $fields */
        foreach ($fields as $field) {
            $inputName = $form->getInputName($field['name']);

            $this->assertSame($field['expected'], $inputName);
            $this->assertTrue($form->has($field['name']));
            $this->assertNull($form->getValue($field['name']));

            $form->setValue($field['name'], $field['value']);

            $this->assertSame($field['value'], $form->getValue($field['name']));
        }
    }
}
