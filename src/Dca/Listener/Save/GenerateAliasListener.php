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

namespace Netzmacht\Contao\Toolkit\Dca\Listener\Save;

use Contao\Database\Result;
use Contao\DataContainer;
use Netzmacht\Contao\Toolkit\Assertion\Assertion;
use Netzmacht\Contao\Toolkit\Data\Alias\AliasGenerator;
use Netzmacht\Contao\Toolkit\Data\Alias\Factory\AliasGeneratorFactory;
use Netzmacht\Contao\Toolkit\Dca\Manager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use const E_USER_DEPRECATED;

/**
 * Class GenerateAliasCallback is designed to create an alias of a column.
 *
 * @package Netzmacht\Contao\Toolkit\Dca\Callback
 */
final class GenerateAliasListener
{
    /**
     * Dependency container.
     *
     * @var Container
     */
    private $container;

    /**
     * Data container manager.
     *
     * @var Manager
     */
    private $dcaManager;

    /**
     * Default alias generator factory service id.
     *
     * @var string
     */
    private $defaultFactoryServiceId;

    /**
     * Cache of created alias generators.
     *
     * @var AliasGenerator[][]
     */
    private $generators = [];

    /**
     * Construct.
     *
     * @param Container $container               Dependency container.
     * @param Manager   $dcaManager              Data container manager.
     * @param string    $defaultFactoryServiceId Default alias generator factory service id.
     */
    public function __construct(Container $container, Manager $dcaManager, $defaultFactoryServiceId)
    {
        $this->container               = $container;
        $this->defaultFactoryServiceId = $defaultFactoryServiceId;
        $this->dcaManager              = $dcaManager;
    }

    /**
     * Generate the alias value.
     *
     * @param mixed         $value         The current value.
     * @param DataContainer $dataContainer The data container driver.
     *
     * @return null|string
     *
     * @throws \Assert\AssertionFailedException If invalid data container is given.
     */
    public function onSaveCallback($value, $dataContainer): ?string
    {
        Assertion::isInstanceOf($dataContainer, DataContainer::class);
        Assertion::isInstanceOf($dataContainer->activeRecord, Result::class);

        $generator = $this->getGenerator($dataContainer);

        return $generator->generate($dataContainer->activeRecord, $value);
    }

    /**
     * Generate the alias value.
     *
     * @param mixed         $value         The current value.
     * @param DataContainer $dataContainer The data container driver.
     *
     * @return null|string
     *
     * @throws \Assert\AssertionFailedException If invalid data container is given.
     *
     * @deprecated Deprecated and removed in Version 4.0.0. Use self::handleSaveCallback instead.
     */
    public function handleSaveCallback($value, $dataContainer): ?string
    {
        // @codingStandardsIgnoreStart
        @trigger_error(
            sprintf(
                '%1$s::handleSaveCallback is deprecated and will be removed in Version 4.0.0. '
                . 'Use %1$s::onSaveCallback instead.',
                static::class
            ),
            E_USER_DEPRECATED
        );
        // @codingStandardsIgnoreEnd

        return $this->onSaveCallback($value, $dataContainer);
    }

    /**
     * Get the service id.
     *
     * @param DataContainer $dataContainer Data container.
     *
     * @return string
     */
    private function getFactoryServiceId($dataContainer): string
    {
        $definition = $this->dcaManager->getDefinition($dataContainer->table);
        $serviceId  = $definition->get(
            ['fields', $dataContainer->field, 'toolkit', 'alias_generator', 'factory'],
            $this->defaultFactoryServiceId
        );

        return $serviceId;
    }

    /**
     * Guard that service is an alias generator.
     *
     * @param mixed  $factory   Retrieved alias generator factory service.
     * @param string $serviceId Service id.
     *
     * @return void
     */
    private function guardIsAliasGeneratorFactory($factory, string $serviceId): void
    {
        Assertion::isInstanceOf(
            $factory,
            AliasGeneratorFactory::class,
            sprintf('Service %s is not an alias generator factory.', $serviceId)
        );
    }

    /**
     * Generate an alias generator.
     *
     * @param DataContainer $dataContainer Data container driver.
     *
     * @return AliasGenerator
     */
    private function getGenerator($dataContainer): AliasGenerator
    {
        if (isset($this->generators[$dataContainer->table][$dataContainer->field])) {
            return $this->generators[$dataContainer->table][$dataContainer->field];
        }

        /** @var AliasGeneratorFactory $factory */
        $serviceId  = $this->getFactoryServiceId($dataContainer);
        $factory    = $this->container->get($serviceId);
        $definition = $this->dcaManager->getDefinition($dataContainer->table);
        $fields     = (array) $definition->get(
            ['fields', $dataContainer->field, 'toolkit', 'alias_generator', 'fields'],
            ['id']
        );

        $this->guardIsAliasGeneratorFactory($factory, $serviceId);

        return $factory->create($dataContainer->table, $dataContainer->field, $fields);
    }
}
