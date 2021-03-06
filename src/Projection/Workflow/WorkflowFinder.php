<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/5/15 - 1:16 AM
 */
namespace Prooph\Link\ProcessManager\Projection\Workflow;

use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;
use Prooph\Link\ProcessManager\Projection\Tables;

/**
 * Class WorkflowFinder
 *
 * @package Prooph\Link\ProcessManager\Projection\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowFinder implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * Returns a list of workflows each with a uuid and name
     *
     * @return array
     */
    public function findAll()
    {
        return $this->connection->fetchAll('SELECT * FROM ' . Tables::WORKFLOW);
    }

    /**
     * @param string $id
     * @return array
     */
    public function find($id)
    {
        return $this->connection->fetchAssoc('SELECT * FROM '.Tables::WORKFLOW.' WHERE id = :id', ['id' => $id]);
    }

    /**
     * @param Connection $connection
     * @return mixed
     */
    public function setApplicationDb(Connection $connection)
    {
        $this->connection = $connection;
    }
}