<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/22/15 - 7:38 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow\Exception;

use Prooph\Link\ProcessManager\Model\Task\TaskId;
use Prooph\Link\ProcessManager\Model\Workflow\ProcessId;
use Prooph\Link\ProcessManager\Model\Workflow\WorkflowId;

/**
 * Exception UnlinkTaskFailed
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow\Exception
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class UnlinkTaskFailed extends \RuntimeException
{
    /**
     * @param WorkflowId $workflowId
     * @param ProcessId $processId
     * @param TaskId $taskId
     * @return UnlinkTaskFailed
     */
    public static function connectionData(WorkflowId $workflowId, ProcessId $processId, TaskId $taskId)
    {
        return new self(sprintf(
            'Unlink Task (%s) from Process (%s) of Workflow (%s) failed',
            $taskId->toString(),
            $processId->toString(),
            $workflowId->toString()
        ));
    }
} 