<?php

/**
 * Contao toolkit.
 *
 * @package    contao-toolkit
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015-2020 netzmacht David Molineus.
 * @license    LGPL-3.0-or-later https://github.com/netzmacht/contao-toolkit/blob/master/LICENSE
 * @filesource
 */

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\Data\Alias\Filter;

/**
 * RawValueFilter uses the values as given.
 *
 * @package Netzmacht\Contao\Toolkit\Data\Alias\Filter
 */
final class RawValueFilter extends AbstractValueFilter
{
    /**
     * {@inheritdoc}
     */
    public function apply($model, $value, string $separator): string
    {
        $values = array();

        foreach ($this->columns as $column) {
            $values[] = $model->$column;
        }

        return $this->combine($value, $values, $separator);
    }
}
