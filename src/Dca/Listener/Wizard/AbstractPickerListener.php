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

namespace Netzmacht\Contao\Toolkit\Dca\Listener\Wizard;

/**
 * AbstractPicker is the base class for a picker wizard.
 *
 * @package Netzmacht\Contao\Toolkit\View\Wizard
 */
abstract class AbstractPickerListener extends AbstractWizardListener
{
    /**
     * Template name.
     *
     * @var string
     */
    protected $template = 'toolkit:be:be_wizard_picker.html5';
}
