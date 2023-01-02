<?php

declare(strict_types=1);

namespace Yii\Model;

use Exception;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionNamedType;

final class ModelType
{
    private array $attributes;

    public function __construct(private readonly ModelInterface $model)
    {
        $this->attributes = $this->collectAttributes();
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return string The type of the attribute.
     */
    public function getType(string $attribute): string
    {
        return match (isset($this->attributes[$attribute]) && is_string($this->attributes[$attribute])) {
            true => $this->attributes[$attribute],
            false => '',
        };
    }

    /**
     * @param mixed $value The value to be converted.
     *
     * @return mixed The value of the attribute converted to the type specified by PHPDoc.
     */
    public function phpTypeCast(string $name, mixed $value): mixed
    {
        if ($this->model->has($name) === false) {
            return null;
        }

        if ($value === null) {
            return null;
        }

        try {
            return match ($this->attributes[$name]) {
                'bool' => (bool) $value,
                'float' => (float) $value,
                'int' => (int) $value,
                'string' => (string) $value,
                default => $value,
            };
        } catch (Exception $e) {
            throw new InvalidArgumentException(
                sprintf('The value is not of type "%s".', (string) $this->attributes[$name])
            );
        }
    }

    /**
     * Returns the list of attribute types indexed by attribute names.
     *
     * By default, this method returns all non-static properties of the class.
     *
     * @return array The list of attribute types indexed by attribute names.
     */
    private function collectAttributes(): array
    {
        $class = new ReflectionClass($this->model);
        $attributes = [];

        foreach ($class->getProperties() as $property) {
            if ($property->isStatic() === false) {
                /** @var ReflectionNamedType|null $type */
                $type = $property->getType();
                $attributes[$property->getName()] = $type !== null ? $type->getName() : '';
            }
        }

        return $attributes;
    }
}
