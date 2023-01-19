<?php

declare(strict_types=1);

namespace Yii\Model;

use Closure;
use InvalidArgumentException;
use Yiisoft\Strings\Inflector;

use function array_key_exists;
use function array_keys;
use function explode;
use function is_subclass_of;
use function property_exists;
use function str_contains;
use function strrchr;
use function substr;

abstract class AbstractModel implements ModelInterface
{
    private array $data = [];
    private Inflector|null $inflector = null;
    private readonly ModelType $modelTypes;

    public function __construct()
    {
        $this->modelTypes = new ModelType($this);
    }

    public function attributes(): array
    {
        return array_keys($this->modelTypes->attributes());
    }

    /**
     * @param string $attribute
     *
     * @return mixed The value (raw data) for the specified attribute.
     */
    public function getAttributeValue(string $attribute): mixed
    {
        return $this->readProperty($attribute);
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Returns the form name that this model class should use.
     *
     * The form name is mainly used by {@see Model} to determine how to name the input fields for the attributes in a
     * model.
     *
     * If the form name is "A" and an attribute name is "b", then the corresponding input name would be "A[b]".
     * If the form name is an empty string, then the input name would be "b".
     *
     * The purpose of the above naming schema is that for forms which contain multiple different models, the attributes
     * of each model are grouped in sub-arrays of the POST-data, and it is easier to differentiate between them.
     *
     * By default, this method returns the model class name (without the namespace part) as the form name. You may
     * override it when the model is used in different forms.
     *
     * @return string The form name class, without a namespace part or empty string when class is anonymous.
     *
     * {@see load()}
     */
    public function getFormName(): string
    {
        if (str_contains(static::class, '@anonymous')) {
            return '';
        }

        $className = strrchr(static::class, '\\');

        if (!$className) {
            return static::class;
        }

        return substr($className, 1);
    }

    /**
     * If there is such attribute in the set.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function has(string $attribute): bool
    {
        [$attribute, $nested] = $this->getNested($attribute);

        return $nested !== null || array_key_exists($attribute, $this->modelTypes->attributes());
    }

    /**
     * Whether the form model is empty.
     */
    public function isEmpty(): bool
    {
        return $this->data === [];
    }

    public function load(array $data, string $formName = null): bool
    {
        $this->data = [];
        $scope = $formName ?? $this->getFormName();

        /** @psalm-var array<string, string> */
        $this->data = match (isset($data[$scope])) {
            true => $data[$scope],
            false => $data,
        };

        foreach ($this->data as $name => $value) {
            $this->setValue($name, $value);
        }

        return $this->data !== [];
    }

    public function setValue(string $name, mixed $value): void
    {
        [$realName] = $this->getNested($name);

        /** @psalm-var mixed $valueTypeCast */
        $valueTypeCast = $this->modelTypes->phpTypeCast($realName, $value);

        $this->writeProperty($name, $valueTypeCast);
    }

    public function setValues(array $data): void
    {
        /** @psalm-var mixed $value */
        foreach ($data as $name => $value) {
            $name = $this->getInflector()->toCamelCase($name);

            if ($this->has($name)) {
                $this->setValue($name, $value);
            } else {
                throw new InvalidArgumentException(sprintf('Attribute "%s" does not exist', $name));
            }
        }
    }

    public function types(): ModelType
    {
        return $this->modelTypes;
    }

    private function getInflector(): Inflector
    {
        return match (empty($this->inflector)) {
            true => $this->inflector = new Inflector(),
            false => $this->inflector,
        };
    }

    /**
     * @psalm-return array{0: string, 1: null|string}
     */
    private function getNested(string $attribute): array
    {
        if (!str_contains($attribute, '.')) {
            return [$attribute, null];
        }

        [$attribute, $nested] = explode('.', $attribute, 2);
        $attributeNested = $this->modelTypes->getType($attribute);

        if (!is_subclass_of($attributeNested, self::class)) {
            throw new InvalidArgumentException("Attribute \"$attribute\" is not a nested attribute.");
        }

        if (!property_exists($attributeNested, $nested)) {
            throw new InvalidArgumentException("Undefined property: \"$attributeNested::$nested\".");
        }

        return [$attribute, $nested];
    }

    private function readProperty(string $attribute): mixed
    {
        $class = static::class;

        [$attribute, $nested] = $this->getNested($attribute);

        if (!property_exists($class, $attribute)) {
            throw new InvalidArgumentException("Undefined property: \"$class::$attribute\".");
        }

        /** @psalm-suppress MixedMethodCall */
        $getter = static fn (ModelInterface $class, string $attribute, string|null $nested): mixed => match ($nested) {
            null => $class->$attribute,
            default => $class->$attribute->getAttributeValue($nested),
        };

        $getter = Closure::bind($getter, null, $this);

        /** @psalm-var Closure $getter */
        return $getter($this, $attribute, $nested);
    }

    private function writeProperty(string $attribute, mixed $value): void
    {
        [$attribute, $nested] = $this->getNested($attribute);

        /** @psalm-suppress MixedMethodCall */
        $setter = static function (ModelInterface $class, string $attribute, mixed $value, string|null $nested): void {
            match ($nested) {
                null => $class->$attribute = $value,
                default => $class->$attribute->setValue($nested, $value),
            };
        };

        $setter = Closure::bind($setter, null, $this);

        /** @psalm-var Closure $setter */
        $setter($this, $attribute, $value, $nested);
    }
}
