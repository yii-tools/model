<?php

declare(strict_types=1);

namespace Yii\Model;

use Yii\Model\Tests\TestSupport\Model\Model;

/**
 * The modelInterface is the interface that should be implemented by classes supporting form data binding.
 */
interface ModelInterface
{
    /**
     * @param string $attribute
     *
     * @return mixed The value (raw data) for the specified attribute.
     */
    public function getAttributeValue(string $attribute): mixed;

    /**
     * @return array The raw data for the model.
     */
    public function getData(): array;

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
    public function getFormName(): string;

    /**
     * If there is such attribute in the set.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function has(string $attribute): bool;

    /**
     * Whether the form model is empty.
     */
    public function isEmpty(): bool;

    /**
     * Populates the model with input data.
     *
     * Which, with `load()` can be written as:
     *
     * ```php
     * $body = $request->getParsedBody();
     * $method = $request->getMethod();
     *
     * if ($method === Method::POST && $loginForm->load($body)) {
     *     // handle success
     * }
     * ```
     *
     * `load()` gets the `'FormName'` from the {@see getFormName()} method (which you may override), unless the
     * `$formName` parameter is given. If the form name is empty string, `load()` populates the model with the whole of
     * `$data` instead of `$data['FormName']`.
     *
     * @param array $data The data array to load, typically server request attributes.
     * @param string|null $formName The scope from which to get data
     *
     * @return bool `true` if the form is successfully populated with some data, `false` otherwise.
     *
     * @psalm-param array<string, mixed> $data
     */
    public function load(array $data, string $formName = null): bool;

    /**
     * Set specified attribute
     *
     * @param string $name The attribute to set
     * @param mixed $value The value to set.
     */
    public function setValue(string $name, mixed $value): void;

    /**
     * Set values for attributes.
     *
     * @param array $data The key-value pairs to set for the attributes.
     *
     * @psalm-param array<string, mixed> $data
     */
    public function setValues(array $data): void;

    /**
     * Returns type for attributes of the model instance.
     */
    public function types(): ModelType;
}
