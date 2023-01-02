<?php

declare(strict_types=1);

namespace Yii\Model;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * The FormModelInterface  defines a set of methods that must be implemented by classes that represent a form model.
 *
 * A form model is a class that represents a form in a web application and is used to validate user input and handle
 * form submissions. It is usually used in conjunction with a view template that renders the form, and a controller that
 * processes the form submission and performs any necessary business logic.
 */
interface FormModelInterface
{
    /**
     * Return array with names of all attributes
     *
     * @return array
     */
    public function attributes(): array;

    /**
     * @param string $attribute The attribute name.
     *
     * @return string the text label for the specified attribute.
     */
    public function getLabel(string $attribute): string;

    /**
     * Returns the attribute labels.
     *
     * Attribute labels are mainly used for display purpose. For example, given an attribute `firstName`, we can declare
     * a label `First Name` which is more user-friendly and can be displayed to end users.
     *
     * By default, an attribute label is generated automatically. This method allows you to explicitly specify attribute
     * labels.
     *
     * Note, in order to inherit labels defined in the parent class, a child class needs to merge the parent labels with
     * child labels using functions such as `array_merge()`.
     *
     * @return array The attribute labels (name => label).
     *
     * @psalm-return string[]
     *
     * {@see getLabel()}
     */
    public function getLabels(): array;

    /**
     * @param string $attribute The attribute name.
     *
     * @return string The text hint for the specified attribute.
     */
    public function getHint(string $attribute): string;

    /**
     * Returns the attribute hints.
     *
     * Attribute hints are mainly used for display purpose. For example, given an attribute `isPublic`, we can declare
     * a hint `Whether the post should be visible for not logged-in users`, which provides user-friendly description of
     * the attribute meaning and can be displayed to end users.
     *
     * Unlike label hint will not be generated, if its explicit declaration is omitted.
     *
     * Note, in order to inherit hints defined in the parent class, a child class needs to merge the parent hints with
     * child hints using functions such as `array_merge()`.
     *
     * @return array the attribute hints (name => hint).
     */
    public function getHints(): array;

    /**
     * Generates an appropriate input ID for the specified attribute name or expression.
     *
     * This method converts the result {@see getInputName()} into a valid input ID.
     *
     * For example, if {@see getInputName()} returns `Post[content]`, this method will return `post-content`.
     *
     * @param string $attribute The attribute name or expression. See {@see getName()} for explanation of attribute
     * expression.
     * @param string $charset The charset to use for the ID, default its `UTF-8`.
     *
     * @throws InvalidArgumentException If the attribute name contains non-word characters.
     * @throws UnexpectedValueException If charset is unknown.
     *
     * @return string The generated input ID.
     */
    public function getInputId(string $attribute, string $charset = 'UTF-8'): string;

    /**
     * Generates an appropriate input name for the specified attribute name or expression.
     *
     * This method generates a name that can be used as the input name to collect user input for the specified
     * attribute. The name is generated according to the of the form and the given attribute name. For example, if the
     * form name of the `Post` form is `Post`, then the input name generated for the `content` attribute would be
     * `Post[content]`.
     *
     * See {@see getName()} For explanation of attribute expression.
     *
     * @param string $attribute The attribute name or expression.
     *
     * @throws InvalidArgumentException If the attribute name contains non-word characters or empty form name for
     * tabular inputs
     *
     * @return string The generated input name.
     */
    public function getInputName(string $attribute): string;

    /**
     * Returns the real attribute name from the given attribute expression.
     *
     * If `$attribute` has neither prefix nor suffix, it will be returned without change.
     *
     * @param string $attribute The attribute name or expression
     *
     * @throws InvalidArgumentException If the attribute name contains non-word characters.
     *
     * @return string The attribute name without prefix and suffix.
     *
     * {@see parse}
     */
    public function getName(string $attribute): string;

    /**
     * @param string $attribute The attribute name.
     *
     * @return string The text placeholder for the specified attribute.
     */
    public function getPlaceholder(string $attribute): string;

    /**
     * Returns the attribute placeholders.
     *
     * Attribute placeholders are mainly used for display purpose. For example, given an attribute `firstName`, we can
     * declare a placeholder `John` which is more user-friendly and can be displayed to end users.
     *
     * Unlike label placeholder will not be generated, if its explicit declaration is omitted.
     *
     * @return array The attribute placeholder (name => placeholder).
     *
     * @psalm-return string[]
     */
    public function getPlaceholders(): array;
}
