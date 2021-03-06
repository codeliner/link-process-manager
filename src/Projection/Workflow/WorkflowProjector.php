<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 4/4/15 - 4:39 PM
 */
namespace Prooph\Link\ProcessManager\Projection\Workflow;

use Doctrine\DBAL\Connection;
use Prooph\Link\Application\Service\ApplicationDbAware;
use Prooph\Link\ProcessManager\Model\Workflow\Message;
use Prooph\Link\ProcessManager\Model\Workflow\MessageType;
use Prooph\Link\ProcessManager\Model\Workflow\StartMessageWasAssignedToWorkflow;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowNameWasChanged;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowWasCreated;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowWasReleased;
use Prooph\Link\ProcessManager\Projection\Tables;
use Prooph\Processing\Message\MessageNameUtils;

/**
 * Class WorkflowProjector
 *
 * @package Prooph\Link\ProcessManager\Projection\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class WorkflowProjector implements ApplicationDbAware
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param WorkflowWasCreated $event
     */
    public function onWorkflowWasCreated(WorkflowWasCreated $event)
    {
        $this->connection->insert(Tables::WORKFLOW, [
            'id' => $event->workflowId()->toString(),
            'name' => $event->workflowName(),
            'node_name' => $event->processingNodeName()->toString()
        ]);
    }

    /**
     * @param WorkflowNameWasChanged $event
     */
    public function onWorkflowNameWasChanged(WorkflowNameWasChanged $event)
    {
        $this->connection->update(Tables::WORKFLOW, ['name' => $event->newName()], ['id' => $event->workflowId()->toString()]);
    }

    /**
     * @param StartMessageWasAssignedToWorkflow $event
     */
    public function onStartMessageWasAssignedToWorkflow(StartMessageWasAssignedToWorkflow $event)
    {
        $this->connection->update(
            Tables::WORKFLOW,
            ['start_message' => $event->startMessage()->messageName()],
            ['id' => $event->workflowId()->toString()]
        );
    }

    /**
     * @param WorkflowWasReleased $event
     */
    public function onWorkflowWasReleased(WorkflowWasReleased $event)
    {
        $this->connection->update(
            Tables::WORKFLOW,
            ['current_release' => $event->releaseNumber(), 'last_published_at' => (new \DateTime())->format(\DateTime::ISO8601)],
            ['id' => $event->workflowId()->toString()]
        );
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