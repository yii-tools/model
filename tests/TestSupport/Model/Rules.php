<?php

declare(strict_types=1);

namespace Yii\Model\Tests\TestSupport\Model;

use Yii\Model\AbstractModel;

final class Rules extends AbstractModel
{
    private string $firstName = '';
    private string $lastName = '';
}
