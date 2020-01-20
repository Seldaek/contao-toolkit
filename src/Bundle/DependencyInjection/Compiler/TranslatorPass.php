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

namespace Netzmacht\Contao\Toolkit\Bundle\DependencyInjection\Compiler;

use function is_subclass_of;
use Netzmacht\Contao\Toolkit\Translation\LangArrayTranslator;
use Netzmacht\Contao\Toolkit\Translation\LangArrayTranslatorBagTranslator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Translation\TranslatorBagInterface as TranslatorBag;

/**
 * TranslatorCompilerPass registers a translator using the globals lang array used in Contao.
 *
 * @package Netzmacht\Contao\Toolkit\DependencyInjection\CompilerPass
 */
final class TranslatorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('translator')) {
            return;
        }

        if ($container->hasDefinition('contao.translation.translator')
            || $container->hasDefinition('cca.translator.backport45translator')
        ) {
            return;
        }

        $definition      = $container->findDefinition('translator');
        $translatorClass = $container->getParameterBag()->resolveValue($definition->getClass());
        $decoratorClass  = is_subclass_of($translatorClass, TranslatorBag::class)
            ? LangArrayTranslatorBagTranslator::class
            : LangArrayTranslator::class;

        if ($container->getParameter('kernel.debug')) {
            $logger = new Reference('logger');
        } else {
            $logger = new Reference('netzmacht.contao_toolkit.logger.null_logger');
        }

        $definition = new Definition(
            $decoratorClass,
            [
                new Reference('netzmacht.contao_toolkit.translation.translator.inner'),
                new Reference('contao.framework'),
                $logger
            ]
        );

        $definition->setDecoratedService('translator');
        $definition->addTag('monolog.logger', ['channel' => 'translation']);

        $container->setDefinition('netzmacht.contao_toolkit.translation.translator', $definition);

        if ($container->getParameter('kernel.debug') && $container->hasDefinition('translator.data_collector')) {
            $container
                ->getDefinition('translator.data_collector')
                ->setDecoratedService('netzmacht.contao_toolkit.translation.translator');
        }
    }
}
