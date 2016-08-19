<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Contao\Toolkit\Dca\Callback\Button;

use Backend;
use Controller;
use Image;
use Input;
use System;
use Netzmacht\Contao\Toolkit\Data\State\StateToggle;
use Netzmacht\Contao\Toolkit\Data\Exception\AccessDenied;

/**
 * StateButtonCallback creates the state toggle button known in Contao.
 *
 * @package Netzmacht\Contao\Toolkit\Dca\Button\Callback
 */
final class StateButtonCallback
{
    /**
     * The input.
     *
     * @var Input
     */
    private $input;

    /**
     * State column.
     *
     * @var string
     */
    private $stateColumn;

    /**
     * Disabled icon.
     *
     * @var string
     */
    private $disabledIcon;

    /**
     * If true state value is handled inverse.
     *
     * @var bool
     */
    private $inverse;

    /**
     * StateButtonCallback constructor.
     *
     * @param Input       $input        Request Input.
     * @param StateToggle $stateToggle  State toggle.
     * @param string      $stateColumn  Column name of the state value.
     * @param string|null $disabledIcon Disabled icon.
     * @param bool        $inverse      If true state value is handled inverse.
     */
    public function __construct(
        Input $input,
        StateToggle $stateToggle,
        $stateColumn,
        $disabledIcon = null,
        $inverse = false
    ) {
        $this->input        = $input;
        $this->toggler      = $stateToggle;
        $this->stateColumn  = $stateColumn;
        $this->disabledIcon = $disabledIcon;
        $this->inverse      = $inverse;
    }

    /**
     * Invoke the callback.
     *
     * @param array  $row        Current data row.
     * @param string $href       Button link.
     * @param string $label      Button label.
     * @param string $title      Button title.
     * @param string $icon       Enabled button icon.
     * @param string $attributes Html attributes as string.
     *
     * @return string
     */
    public function __invoke($row, $href, $label, $title, $icon, $attributes)
    {
        if ($this->input->get('tid')) {
            try {
                $this->toggler->toggle($this->input->get('tid'), ($this->input->get('state') == 1), $this);
                Controller::redirect(Controller::getReferer());
            } catch (AccessDenied $e) {
                System::log($e->getMessage(), __METHOD__, TL_ERROR);
                Controller::redirect('contao/main.php?act=error');
            }
        }

        if (!$this->toggler->hasUserAccess()) {
            return '';
        }

        $href .= '&amp;id='.$this->input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row[''];

        if (!$row[$this->stateColumn] || ($this->inverse && $row[$this->stateColumn])) {
            $icon = $this->disableIcon($icon);
        }

        return sprintf(
            '<a href="%s" title="%s"%s>%s</a> ',
            Backend::addToUrl($href),
            specialchars($title),
            $attributes,
            Image::getHtml($icon, $label)
        );
    }

    /**
     * Disable the icon.
     *
     * @param string $icon The enabled icon.
     *
     * @return string
     */
    private function disableIcon($icon)
    {
        if ($this->disabledIcon) {
            return $this->disabledIcon;
        }

        if ($icon === 'visible.gif') {
            return 'invisible.gif';
        }

        return preg_replace('\.([^\.]*)$', '._$1', $icon);
    }
}
