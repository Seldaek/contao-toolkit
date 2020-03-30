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

namespace Netzmacht\Contao\Toolkit\Translation;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface as ContaoFramework;
use Netzmacht\Contao\Toolkit\Assertion\Assertion;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\TranslatorBagInterface as TranslatorBag;
use Symfony\Component\Translation\TranslatorInterface as Translator;

/**
 * LangArrayTranslator is a translator implementation using the globals of Contao.
 *
 * It's a backport of https://github.com/contao/core-bundle/blob/develop/src/Translation/Translator.php
 * introduced in Contao 4.5.
 */
class LangArrayTranslatorBagTranslator extends LangArrayTranslator implements TranslatorBag
{
    /**
     * Constructor.
     *
     * @param Translator      $translator The translator to decorate.
     * @param ContaoFramework $framework  Contao framework.
     * @param LoggerInterface $logger     Logger.
     *
     * @throws \Assert\AssertionFailedException When a translator is passed not implementing TranslatorBag interface.
     */
    public function __construct(Translator $translator, ContaoFramework $framework, LoggerInterface $logger)
    {
        Assertion::implementsInterface($translator, TranslatorBag::class);

        parent::__construct($translator, $framework, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function getCatalogue($locale = null)
    {
        /** @var TranslatorBag $inner */
        $inner = $this->getInnerTranslator();

        return $inner->getCatalogue($locale);
    }
}
