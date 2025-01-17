<?php

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\View;

use Netzmacht\Contao\Toolkit\View\Template\Exception\HelperNotFound;

/**
 * Interface describes the templates being used in the toolkit.
 */
interface Template
{
    /**
     * Parse the template.
     *
     * @return string
     *
     * phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function parse();

    /**
     * Get a template value.
     *
     * @param string $name The name.
     *
     * @return mixed
     */
    public function get(string $name);

    /**
     * Set a template var.
     *
     * @param string $name  The template var name.
     * @param mixed  $value The value.
     *
     * @return $this
     */
    public function set(string $name, $value): self;

    /**
     * Get an helper.
     *
     * @param string $name Name of the helper.
     *
     * @return mixed
     *
     * @throws HelperNotFound If helper not exists.
     */
    public function helper(string $name);

    /**
     * Get all the template data.
     *
     * @return array<string,mixed>
     *
     * phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function getData();

    /**
     * Set all the template data.
     *
     * @param array<string,mixed> $data Template data.
     *
     * @return void
     *
     * phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setData($data);
}
