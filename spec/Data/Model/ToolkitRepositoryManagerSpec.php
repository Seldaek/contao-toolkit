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

namespace spec\Netzmacht\Contao\Toolkit\Data\Model;

use Contao\Model;
use Doctrine\DBAL\Connection;
use Netzmacht\Contao\Toolkit\Assertion\AssertionFailed;
use Netzmacht\Contao\Toolkit\Data\Model\ContaoRepository;
use Netzmacht\Contao\Toolkit\Data\Model\Repository;
use Netzmacht\Contao\Toolkit\Data\Model\RepositoryManager;
use Netzmacht\Contao\Toolkit\Data\Model\ToolkitRepositoryManager;
use Netzmacht\Contao\Toolkit\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;

class ToolkitRepositoryManagerSpec extends ObjectBehavior
{
    function let(Connection $connection)
    {
        $this->beConstructedWith($connection, []);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType(ToolkitRepositoryManager::class);
    }

    function it_is_a_repository_manager()
    {
        $this->shouldImplement(RepositoryManager::class);
    }

    function it_gets_registered_repository(Connection $connection, Repository $repository)
    {
        $this->beConstructedWith($connection, ['example' => $repository]);
        $this->getRepository('example')->shouldReturn($repository);
    }

    function it_created_default_repository_for_non_registered_contao_models()
    {
        $this->getRepository(SampleModel::class)->shouldHaveType(ContaoRepository::class);
    }

    function it_throws_invalid_argument_exception_when_no_repository_is_registered_nor_a_contao_model_is_passed()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('getRepository', ['foo']);
    }

    function it_throws_exception_if_no_repository_is_passed(Connection $connection)
    {
        $this->beConstructedWith($connection, ['foo' => new \stdClass()]);
        $this->shouldThrow(AssertionFailed::class)->duringInstantiation();
    }

    function it_has_the_database_connection(Connection $connection)
    {
        $this->getConnection()->shouldReturn($connection);
    }
}

class SampleModel extends Model
{

}
