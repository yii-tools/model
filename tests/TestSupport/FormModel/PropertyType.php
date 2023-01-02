<?php

declare(strict_types=1);

namespace Yii\Model\Tests\TestSupport\FormModel;

use Yii\Model\AbstractFormModel;

final class PropertyType extends AbstractFormModel
{
    private array $array = [];
    private bool $bool = false;
    private float $float = 0;
    private int $int = 0;
    private int|null $nullable = null;
    private object|null $object = null;
    private string $string = '';
}
