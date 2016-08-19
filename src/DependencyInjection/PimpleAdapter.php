<?php

/**
 * @package    netzmacht
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2016 netzmacht David Molineus. All rights reserved.
 * @filesource
 *
 */

namespace Netzmacht\Contao\Toolkit\DependencyInjection;

use Interop\Container\ContainerInterface;
use Netzmacht\Contao\Toolkit\DependencyInjection\Exception\ContainerException;
use Netzmacht\Contao\Toolkit\DependencyInjection\Exception\ServiceNotFound;

/**
 * Class PimpleAdapter.
 *
 * @package Netzmacht\Contao\Toolkit\DependencyInjection
 */
final class PimpleAdapter implements ContainerInterface
{
    /**
     * Pimple adapter.
     *
     * @var \Pimple
     */
    private $pimple;

    /**
     * PimpleAdapter constructor.
     *
     * @param \Pimple $pimple Pimple container.
     */
    public function __construct(\Pimple $pimple)
    {
        $this->pimple = $pimple;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ServiceNotFound    When service id is not found.
     * @throws ContainerException When an exception is thrown during fetching the service.
     */
    public function get($serviceId)
    {
        try {
            return $this->pimple[$serviceId];
        } catch (\InvalidArgumentException $previous) {
            throw new ServiceNotFound(
                sprintf('Service with id "%s" not found.', $serviceId),
                $previous->getCode(),
                $previous
            );
        } catch (\Exception $previous) {
            throw new ContainerException('', $previous->getCode(), $previous);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function has($serviceId)
    {
        return isset($this->pimple[$serviceId]);
    }
}
