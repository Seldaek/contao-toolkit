<?php

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\Dca\Listener\Wizard;

use function array_merge;

use const E_USER_DEPRECATED;

final class ColorPickerListener extends AbstractPickerListener
{
    /**
     * Template name.
     *
     * @var string
     */
    protected $template = 'toolkit:be:be_wizard_color_picker.html5';

    /**
     * Generate the color picker.
     *
     * @param string $dataContainerName Data container name.
     * @param string $fieldName         Field name.
     */
    public function generate(string $dataContainerName, string $fieldName): string
    {
        $config          = $this->getConfig($dataContainerName, $fieldName);
        $config['field'] = $fieldName;

        return $this->render($this->template, $config);
    }

    /**
     * {@inheritDoc}
     */
    public function onWizardCallback($dataContainer): string
    {
        return $this->generate($dataContainer->table, $dataContainer->field);
    }

    /**
     * {@inheritDoc}
     */
    public function handleWizardCallback($dataContainer): string
    {
        // @codingStandardsIgnoreStart
        @trigger_error(
            sprintf(
                '%1$s::handleWizardCallback is deprecated and will be removed in Version 4.0.0. '
                . 'Use %1$s::onWizardCallback instead.',
                static::class
            ),
            E_USER_DEPRECATED
        );
        // @codingStandardsIgnoreEnd

        return $this->onWizardCallback($dataContainer);
    }

    /**
     * Get the picker configuration.
     *
     * @param string $dataContainerName Data container name.
     * @param string $fieldName         Field name.
     *
     * @return array<string,mixed>
     */
    public function getConfig(string $dataContainerName, string $fieldName): array
    {
        $definition = $this->dcaManager->getDefinition($dataContainerName);
        $config     = [
            'title'      => $this->translator->trans('MSC.colorpicker', [], 'contao_default'),
            'template'   => $this->template,
            'icon'       => 'pickcolor.svg',
            'replaceHex' => false,
        ];

        return array_merge(
            $config,
            (array) $definition->get(['fields', $fieldName, 'toolkit', 'color_picker'])
        );
    }
}
