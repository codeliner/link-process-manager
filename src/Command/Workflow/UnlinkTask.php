<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/22/15 - 7:12 PM
 */
namespace Prooph\Link\ProcessManager\Command\Workflow;

use Assert\Assertion;
use Prooph\Common\Messaging\Command;
use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Workflow\ProcessId;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;

/**
 * Command UnlinkTask
 *
 * Unlink connection between Task and Workflow Process.
 *
 * @package Prooph\Link\ProcessManager\Command\Task
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class UnlinkTask extends Command
{
    /**
     * @param string $workflowId
     * @param string $processId
     * @param string $taskId
     * @return UnlinkTask
     */
    public static function fromConnection($workflowId, $processId, $taskId)
    {
        Assertion::uuid($workflowId);
        Assertion::uuid($processId);
        Assertion::uuid($taskId);

        return new self(__CLASS__, [
            'workflow_id' => $workflowId,
            'process_id' => $processId,
            'task_id' => $taskId
        ]);
    }

    /**
     * @return WorkflowId
     */
    public function workflowId()
    {
        return WorkflowId::fromString($this->payload['workflow_id']);
    }

    /**
     * @return ProcessId
     */
    public function processId()
    {
        return ProcessId::fromString($this->payload['process_id']);
    }

    /**
     * @return TaskId
     */
    public function taskId()
    {
        return TaskId::fromString($this->payload['task_id']);
    }
} 