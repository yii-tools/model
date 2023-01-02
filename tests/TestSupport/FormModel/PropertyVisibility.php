<?php

declare(strict_types=1);

namespace Yii\Model\Tests\TestSupport\FormModel;

use Yii\Model\AbstractFormModel;

final class PropertyVisibility extends AbstractFormModel
{
    public string $public = '';
    protected string $protected = '';
    private string $private = '';
    private static string $static = '';
}
