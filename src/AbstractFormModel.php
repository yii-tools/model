<?php

declare(strict_types=1);

namespace Yii\Model;

use InvalidArgumentException;
use Yiisoft\Strings\StringHelper;

use function mb_strtolower;
use function preg_match;
use function str_replace;

abstract class AbstractFormModel extends AbstractModel implements FormModelInterface
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

    public function getInputId(string $attribute, string $charset = 'UTF-8'): string
    {
        $name = mb_strtolower($this->getInputName($attribute), $charset);

        return str_replace(['[]', '][', '[', ']', ' ', '.'], ['', '-', '-', '', '-', '-'], $name);
    }

    public function getInputName(string $attribute): string
    {
        $data = $this->parse($attribute);
        $formName = $this->getFormName();

        if ($formName === '' && $data['prefix'] === '') {
            return $attribute;
        }

        if ($formName !== '') {
            return "$formName{$data['prefix']}[{$data['name']}]{$data['suffix']}";
        }

        throw new InvalidArgumentException('formName() cannot be empty for tabular inputs.');
    }

    public function getLabel(string $attribute): string
    {
        $labels = $this->getLabels();
        $label = $labels[$attribute] ?? $this->generateLabel($attribute);
        $nestedLabel = $this->getNestedValue('getLabel', $attribute);

        return match ($this->has($attribute)) {
            true => $nestedLabel === '' ? $label : $nestedLabel,
            false => throw new InvalidArgumentException("Attribute '$attribute' does not exist."),
        };
    }

    public function getLabels(): array
    {
        return [];
    }

    public function getName(string $attribute): string
    {
        $attribute = $this->parse($attribute)['name'];

        if ($this->has($attribute) === false) {
            throw new invalidArgumentException("Attribute '$attribute' does not exist.");
        }

        return $attribute;
    }

    public function getPlaceholder(string $attribute): string
    {
        $placeHolders = $this->getPlaceholders();
        $placeHolder = $placeHolders[$attribute] ?? '';
        $nestedPlaceholder = $this->getNestedValue('getPlaceholder', $attribute);

        return match ($this->has($attribute)) {
            true => $nestedPlaceholder === '' ? $placeHolder : $nestedPlaceholder,
            false => throw new InvalidArgumentException("Attribute '$attribute' does not exist."),
        };
    }

    public function getPlaceholders(): array
    {
        return [];
    }

    /**
     * Generates a user-friendly attribute label based on the give attribute name.
     *
     * This is done by replacing underscores, dashes and dots with blanks and changing the first letter of each word to
     * upper case.
     *
     * For example, 'department_name' or 'DepartmentName' will generate 'Department Name'.
     *
     * @param string $name The column name.
     *
     * @return string The attribute label.
     */
    private function generateLabel(string $name): string
    {
        return StringHelper::uppercaseFirstCharacterInEachWord($this->getInflector()->toWords($name));
    }

    /**
     * This method parses an attribute expression and returns an associative array containing real attribute name,
     * prefix and suffix.
     *
     * For example: `['name' => 'content', 'prefix' => '', 'suffix' => '[0]']`.
     *
     * An attribute expression is an attribute name prefixed and/or suffixed with array indexes. It is mainly used in
     * tabular data input and/or input of array type. Below are some examples:
     *
     * - `[0]content` Is used in tabular data input to represent the "content" attribute for the first model in tabular
     *    input;
     * - `dates[0]` Represents the first array element of the "dates" attribute;
     * - `[0]dates[0]` Represents the first array element of the "dates" attribute for the first model in tabular input.
     *
     * @param string $attribute The attribute name or expression.
     *
     * @throws InvalidArgumentException If the attribute name contains non-word characters.
     *
     * @return string[] The attribute name, prefix and suffix.
     */
    private function parse(string $attribute): array
    {
        if (!preg_match('/(^|.*\])([\w\.\+\-_]+)(\[.*|$)/u', $attribute, $matches)) {
            throw new InvalidArgumentException('Attribute name must contain word characters only.');
        }

        return ['name' => $matches[2], 'prefix' => $matches[1], 'suffix' => $matches[3]];
    }
}
