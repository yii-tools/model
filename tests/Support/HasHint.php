<?php

declare(strict_types=1);

namespace Yii\Model\Tests\Support;

trait HasHint
{
    public function getHint(string $attribute): string
    {
        $hints = $this->getHints();
        /** @psalm-var string $hint */
        $hint = $hints[$attribute] ?? '';
        $nestedHint = $this->getNestedValue('getHint', $attribute);

        return match ($this->has($attribute)) {
            true => $nestedHint === '' ? $hint : $nestedHint,
            false => throw new InvalidArgumentException("Attribute '$attribute' does not exist."),
        };
    }

    public function getHints(): array
    {
        return [];
    }
}
