<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/22/15 - 7:52 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\Link\ProcessManager\Model\Task\TaskId;

/**
 * Event TaskWasUnlinked
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TaskWasUnlinked extends AggregateChanged
{
    private $workflowId;

    private $processId;

    private $taskId;

    /**
     * @param WorkflowId $workflowId
     * @param ProcessId $processId
     * @param TaskId $taskId
     * @return TaskWasUnlinked
     */
    public static function fromConnection(WorkflowId $workflowId, ProcessId $processId, TaskId $taskId)
    {
        $event = self::occur($workflowId->toString(), [
            'process_id' => $processId->toString(),
            'task_id' => $taskId->toString()
        ]);

        $event->workflowId = $workflowId;
        $event->processId  = $processId;
        $event->taskId = $taskId;

        return $event;
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        if (is_null($this->workflowId)) {
            $this->workflowId = WorkflowId::fromString($this->aggregateId());
        }
        return $this->workflowId;
    }

    /**
     * @return ProcessId
     */
    public function processId()
    {
        if (is_null($this->processId)) {
            $this->processId = ProcessId::fromString($this->payload['process_id']);
        }
        return $this->processId;
    }

    /**
     * @return TaskId
     */
    public function taskId()
    {
        if (is_null($this->taskId)) {
            $this->taskId = TaskId::fromString($this->payload['task_id']);
        }
        return $this->taskId;
    }
} 