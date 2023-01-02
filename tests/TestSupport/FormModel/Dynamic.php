<?php

declare(strict_types=1);

namespace Yii\Model\Tests\TestSupport\FormModel;

use Yii\Model\AbstractFormModel;

final class Dynamic extends AbstractFormModel
{
    public function __construct(private array $attributes = [])
    {
        parent::__construct();
    }

    public function has(string $attribute): bool
    {
        return array_key_exists($attribute, $this->attributes);
    }

    public function getValue(string $attribute): mixed
    {
        if ($this->has($attribute)) {
            return $this->attributes[$attribute];
        }

        return null;
    }

    public function setValue(string $name, $value): void
    {
        if ($this->has($name)) {
            $this->attributes[$name] = $value;
        }
    }
}
