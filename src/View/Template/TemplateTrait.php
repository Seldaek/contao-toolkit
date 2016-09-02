<?php

/**
 * @package    contao-toolkit
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015-2016 netzmacht David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Contao\Toolkit\View\Template;

use Netzmacht\Contao\Toolkit\View\Template\Exception\HelperNotFound;

/**
 * Trait extends the default Contao template classes.
 *
 * @package Netzmacht\Contao\Toolkit\View
 */
trait TemplateTrait
{
    /**
     * Template helper.
     *
     * @var callable[]
     */
    protected $helpers = [];

    /**
     * Get a template value.
     *
     * @param string $name The name.
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->$name;
    }

    /**
     * Set a template var.
     *
     * @param string $name  The template var name.
     * @param mixed  $value The value.
     *
     * @return $this
     */
    public function set($name, $value)
    {
        $this->$name = $value;

        return $this;
    }

    /**
     * Get the helper.
     *
     * @param string $name Name of the view helper.
     *
     * @return mixed
     * @throws HelperNotFound If helper is not registered.
     */
    public function helper($name)
    {
        if (!isset($this->helpers[$name])) {
            throw new HelperNotFound($name);
        }

        return $this->helpers[$name];
    }

    /**
     * Insert a template.
     *
     * @param string $name The template name.
     * @param array  $data An optional data array.
     *
     * @return void
     */
    public function insert($name, array $data = null)
    {
        $template = new static($name, $this->helpers, $this->strContentType);

        if ($data !== null) {
            $template->setData($data);
        }

        echo $template->parse();
    }
}
