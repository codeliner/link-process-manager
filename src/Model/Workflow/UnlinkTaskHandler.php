<?php
/*
 * This file is part of prooph/link.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Date: 6/22/15 - 7:28 PM
 */
namespace Prooph\Link\ProcessManager\Model\Workflow;

use Prooph\Link\ProcessManager\Command\Workflow\UnlinkTask;
use Prooph\Link\ProcessManager\Model\Workflow\Exception\WorkflowNotFound;

/**
 * CommandHandler UnlinkTaskHandler
 *
 * Unlink Task from a Workflow Process connection
 *
 * @package Prooph\Link\ProcessManager\Model\Workflow
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class UnlinkTaskHandler 
{
    /**
     * @var WorkflowCollection
     */
    private $workflowCollection;

    /**
     * @param WorkflowCollection $workflowCollection
     */
    public function __construct(WorkflowCollection $workflowCollection)
    {
        $this->workflowCollection = $workflowCollection;
    }

    /**
     * @param UnlinkTask $command
     * @throws Exception\WorkflowNotFound
     */
    public function handle(UnlinkTask $command)
    {
        $workflow = $this->workflowCollection->get($command->workflowId());

        if (!$workflow) {
            throw WorkflowNotFound::withId($command->workflowId());
        }

        $workflow->unlinkTaskFromProcess($command->processId(), $command->taskId());
    }
} 