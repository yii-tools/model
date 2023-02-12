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
     * @return array The raw data for the model.
     */
    public function getData(): array;

    /**
     * If there is such attribute in the set.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function has(string $attribute): bool;

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
    public function setAttributeValue(string $name, mixed $value): void;

    /**
     * Set values for attributes.
     *
     * @param array $data The key-value pairs to set for the attributes.
     *
     * @psalm-param array<string, mixed> $data
     */
    public function setAttributesValues(array $data): void;

    /**
     * Returns type for attributes of the model instance.
     */
    public function types(): ModelType;
}
