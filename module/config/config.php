<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

$GLOBALS['TL_HOOKS']['initializeDependencyContainer'][] = ['Netzmacht\Contao\Toolkit\Boot', 'initialize'];