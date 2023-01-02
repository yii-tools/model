<?php

declare(strict_types=1);

namespace Yii\Model\Tests\TestSupport\Model;

use Yii\Model\AbstractModel;

final class Model extends AbstractModel
{
    public string $public = '';
    private null|string $login = null;
    private null|string $password = null;
}
