<?php

declare(strict_types=1);

namespace Yii\Model\Tests\Provider;

use Yii\Model\AbstractFormModel;
use Yii\Model\Tests\TestSupport\FormModel\Login;

final class FormModelProvider
{
    public function dynamicAttributes(): array
    {
        return [
            [
                [
                    [
                        'name' => '7aeceb9b-fa64-4a83-ae6a-5f602772c01b',
                        'value' => 'some uuid value',
                        'expected' => 'Dynamic[7aeceb9b-fa64-4a83-ae6a-5f602772c01b]',
                    ],
                    [
                        'name' => 'test_field',
                        'value' => 'some test value',
                        'expected' => 'Dynamic[test_field]',
                    ],
                ],
            ],
        ];
    }

    public function getInputName(): array
    {
        $loginModel = new Login();
        $anonymousModel = new class () extends AbstractFormModel {
        };

        return [
            [$loginModel, '[0]content', 'Login[0][content]'],
            [$loginModel, 'dates[0]', 'Login[dates][0]'],
            [$loginModel, '[0]dates[0]', 'Login[0][dates][0]'],
            [$loginModel, 'age', 'Login[age]'],
            [$anonymousModel, 'dates[0]', 'dates[0]'],
            [$anonymousModel, 'age', 'age'],
        ];
    }
}
